<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Service\AnalyticsData;
use Carbon\Carbon;
use Google\Client;
use App\Models\Settings;

class AnalyticController extends Controller
{
    public function getGoogleAnalyticPage()
    {
        return view("backend.google.analytic");
    }

    public function getAnalyticsClient()
    {
        // Google Analytics'e erişim için client oluştur
        $client = new Client();

        // Erişim token'ı al
        $token = session('google_access_token');

        // Kullanıcı giriş yapmışsa token'ı kullan
        if ($token) {
            $client->setAccessToken($token);
            return new AnalyticsData($client);
        } else {
            // Token yoksa login sayfasına yönlendir
            return redirect()->route('google.connect')->send();
        }
    }

    public function getArticleStats(Request $request)
    {
        try {

            $url = $request->query('url');

            if (!$url) {
                return response()->json(['error' => 'URL parametresi gerekli'], 400);
            }

            $settings = Settings::first();
            $jsondata = json_decode($settings->magicbox);
            $google_property_id = $jsondata->google_property_id;

            $analytics = $this->getAnalyticsClient();

            $propertyId = $google_property_id;

            $responseData = $this->fetchArticleData($analytics, $propertyId, $url);

            $responseDevice = $this->fetchArticleDeviceCategory($analytics, $propertyId, $url);
            $responseCityStats = $this->fetchCityStats($analytics, $propertyId, $url);


            $result = [
                $responseData,
                $responseDevice,
                $responseCityStats
            ];

            return $result;
        } catch (\Google\Service\Exception $e) {
            // Google Analytics API'ye özel hata mesajı

            return response()->json(['error' => $e->getMessage()], 500);

        } catch (\Exception $e) {
            // Genel hata yakalama

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function fetchArticleData($analytics, $propertyId, $url)
    {
        $startDate = Carbon::now()->subYear(2)->format('Y-m-d'); // Son 30 gün
        $endDate = Carbon::now()->format('Y-m-d');

        $requestBody = new AnalyticsData\RunReportRequest([
            'property' => "properties/{$propertyId}",
            'dateRanges' => [['startDate' => $startDate, 'endDate' => $endDate]],
            'dimensions' => [['name' => 'pagePath']],
            'metrics' => [
                ['name' => 'screenPageViews'],
                ['name' => 'activeUsers'],
                ['name' => 'eventCount'],
            ],
            'dimensionFilter' => [
                'filter' => [
                    'fieldName' => 'pagePath',
                    'stringFilter' => [
                        'matchType' => 'EXACT', // Daha esnek filtreleme
                        'value' => $url
                    ]
                ]
            ]
        ]);

        return $analytics->properties->runReport("properties/{$propertyId}", $requestBody);
    }


    public function fetchArticleDeviceCategory($analytics, $propertyId, $url)
    {
        $startDate = Carbon::now()->subYear(2)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        $requestBody = new AnalyticsData\RunReportRequest([
            'property' => 'properties/' . $propertyId,
            'dateRanges' => [['startDate' => $startDate, 'endDate' => $endDate]],
            'dimensions' => [
                ['name' => 'pagePath'],
                ['name' => 'deviceCategory'] // Cihaz kategorisi eklendi
            ],
            'metrics' => [
                ['name' => 'screenPageViews'],
                ['name' => 'activeUsers'] // Etkin kullanıcı metriği eklendi
            ],
            'dimensionFilter' => [
                'filter' => [
                    'fieldName' => 'pagePath',
                    'stringFilter' => [
                        'matchType' => 'EXACT',
                        'value' => $url
                    ]
                ]
            ]
        ]);

        try {
            $result = $analytics->properties->runReport('properties/' . $propertyId, $requestBody);
        } catch (Exception $e) {

            return [];
        }

        $data = [];

        foreach ($result->getRows() as $row) {
            $data[] = [
                'url' => $row->getDimensionValues()[0]->getValue(),
                'deviceCategory' => $row->getDimensionValues()[1]->getValue(), // Cihaz kategorisi
                'views' => $row->getMetricValues()[0]->getValue(), // Görüntüleme sayısı
                'activeUsers' => $row->getMetricValues()[1]->getValue(), // Etkin kullanıcı sayısı
            ];
        }

        return $data;
    }

    public function fetchCityStats($analytics, $propertyId, $url)
    {
        $startDate = Carbon::now()->subYear(2)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        $requestBody = new AnalyticsData\RunReportRequest([
            'property' => 'properties/' . $propertyId,
            'dateRanges' => [['startDate' => $startDate, 'endDate' => $endDate]],
            'dimensions' => [
                ['name' => 'pagePath'],
                ['name' => 'city'] // Şehir bilgisi
            ],
            'metrics' => [
                ['name' => 'screenPageViews'] // Etkin kullanıcı sayısı
            ],
            'dimensionFilter' => [
                'filter' => [
                    'fieldName' => 'pagePath',
                    'stringFilter' => [
                        'matchType' => 'EXACT',
                        'value' => $url
                    ]
                ]
            ]
        ]);

        $result = $analytics->properties->runReport('properties/' . $propertyId, $requestBody);

        $cities = [];

        foreach ($result->getRows() as $row) {
            $cities[] = [
                'url' => $row->getDimensionValues()[0]->getValue(),
                'city' => $row->getDimensionValues()[1]->getValue(), // Şehir adı
                'screenPageViews' => $row->getMetricValues()[0]->getValue(), // Etkin kullanıcı sayısı
            ];
        }

        return $cities;
    }

    public function getHomepageStats(Request $request)
    {
        try {

            $settings = Settings::first();
            $jsondata = json_decode($settings->magicbox);
            $google_property_id = $jsondata->google_property_id;

            $analytics = $this->getAnalyticsClient();

            $propertyId = $google_property_id;

            $countryData = $this->fetchCountryStats($analytics, $propertyId);

            $last1Day = $this->fetchVisitorsAndPageViews($analytics, $propertyId, 1);
            $last7Day = $this->fetchVisitorsAndPageViews($analytics, $propertyId, 7);
            $last30Day = $this->fetchVisitorsAndPageViews($analytics, $propertyId, 30);
            //$last30Day2 = Analytics::fetchVisitorsAndPageViews(Period::days(30));

            if (count($last1Day) == 0) {
                $last1Day = [
                    [
                        "pageTitle" => "Veri Yok!",
                        "activeUsers" => 0,
                        "screenPageViews" => 0
                    ]
                ];
            }

            if (count($last7Day) == 0) {
                $last7Day = [
                    [
                        "pageTitle" => "Veri Yok!",
                        "activeUsers" => 0,
                        "screenPageViews" => 0
                    ]
                ];
            }

            if (count($last30Day) == 0) {
                $last30Day = [
                    [
                        "pageTitle" => "Veri Yok!",
                        "activeUsers" => 0,
                        "screenPageViews" => 0
                    ]
                ];
            }


            $result = [
                $countryData,
                $last1Day,
                $last7Day,
                $last30Day,

            ];

            return $result;
        } catch (\Google\Service\Exception $e) {
            // Google Analytics API'ye özel hata mesajı

            return response()->json(['error' => $e->getMessage()], 500);

        } catch (\Exception $e) {
            // Genel hata yakalama

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function fetchCountryStats($analytics, $propertyId)
    {
        $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        $requestBody = new AnalyticsData\RunReportRequest([
            'property' => 'properties/' . $propertyId,
            'dateRanges' => [['startDate' => $startDate, 'endDate' => $endDate]],
            'dimensions' => [
                ['name' => 'country'],
                ['name' => 'countryId']
            ],
            'metrics' => [
                ['name' => 'screenPageViews']
            ]
        ]);

        $result = $analytics->properties->runReport('properties/' . $propertyId, $requestBody);

        $countries = [];

        if ($result->getRows()) {
            foreach ($result->getRows() as $row) {
                $countries[] = [
                    'country' => $row->getDimensionValues()[0]->getValue(),
                    'countryCode' => $row->getDimensionValues()[1]->getValue(),
                    'screenPageViews' => $row->getMetricValues()[0]->getValue(),
                ];
            }
        }

        return $countries;
    }

    public function fetchVisitorsAndPageViews($analytics, $propertyId, $days)
    {
        $startDate = Carbon::now()->subDays($days)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        $requestBody = new AnalyticsData\RunReportRequest([
            'property' => 'properties/' . $propertyId,
            'dateRanges' => [['startDate' => $startDate, 'endDate' => $endDate]],
            'dimensions' => [
                ['name' => 'pageTitle'] // Sayfa başlığı
            ],
            'metrics' => [
                ['name' => 'activeUsers'],      // Aktif kullanıcı sayısı
                ['name' => 'screenPageViews']   // Sayfa görüntüleme sayısı
            ]
        ]);

        $result = $analytics->properties->runReport('properties/' . $propertyId, $requestBody);

        $pageStats = [];

        foreach ($result->getRows() as $row) {
            $pageStats[] = [
                'pageTitle' => $row->getDimensionValues()[0]->getValue(),
                'activeUsers' => (int) $row->getMetricValues()[0]->getValue(),
                'screenPageViews' => (int) $row->getMetricValues()[1]->getValue()
            ];
        }

        return $pageStats;
    }


    public function getPropertyId()
    {
        $settings = Settings::first();
        $jsondata = json_decode($settings->magicbox);
        $google_property_id = $jsondata->google_property_id;
        return $google_property_id;
    }

    public function fetchAllData(Request $request)
    {
        try {
            $analytics = $this->getAnalyticsClient();
            $propertyId = $this->getPropertyId();

            $days = (int) ($request->query("date") ?? 30);


            $startDate = Carbon::now()->subDays($days)->format('Y-m-d');
            $endDate = Carbon::now()->format('Y-m-d');

            // Yeni metrikleri ekledik
            $requestBody = new AnalyticsData\RunReportRequest([
                'property' => 'properties/' . $propertyId,
                'dateRanges' => [['startDate' => $startDate, 'endDate' => $endDate]],
                'metrics' => [
                    ['name' => 'activeUsers'],           // Etkin kullanıcı sayısı
                    ['name' => 'screenPageViews'],       // Sayfa görüntüleme sayısı
                    ['name' => 'newUsers'],              // Yeni kullanıcı sayısı
                    ['name' => 'totalUsers'],            // Toplam kullanıcı sayısı
                    ['name' => 'eventCount'],            // Etkinlik sayısı
                    ['name' => 'averageSessionDuration'] // Ortalama oturum süresi
                ]
            ]);

            $result = $analytics->properties->runReport('properties/' . $propertyId, $requestBody);

            $pageStats = [];

            foreach ($result->getRows() as $row) {
                $sessionDurationSeconds = (float) $row->getMetricValues()[5]->getValue();
                $minutes = str_pad(floor($sessionDurationSeconds / 60), 2, '0', STR_PAD_LEFT); // Dakikaya çevrildi ve iki basamaklı yapıldı
                $seconds = str_pad($sessionDurationSeconds % 60, 2, '0', STR_PAD_LEFT);        // Kalan saniye hesaplandı ve iki basamaklı yapıldı

                $pageStats[] = [
                    'activeUsers' => (int) $row->getMetricValues()[0]->getValue(),
                    'screenPageViews' => (int) $row->getMetricValues()[1]->getValue(),
                    'newUsers' => (int) $row->getMetricValues()[2]->getValue(),
                    'totalUsers' => (int) $row->getMetricValues()[3]->getValue(),
                    'eventCount' => (int) $row->getMetricValues()[4]->getValue(),
                    'averageSessionDurationMinutes' => $minutes,
                    'averageSessionDurationSeconds' => $seconds,

                ];
            }

            return response()->json($pageStats);

        } catch (\Google\Service\Exception $e) {
            // Google Analytics API'ye özel hata mesajı

            return response()->json(['error' => $e->getMessage()], 500);

        } catch (\Exception $e) {
            // Genel hata yakalama

            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function fetchAllDeviceCategory(Request $request)
    {
        try {
            $analytics = $this->getAnalyticsClient();
            $propertyId = $this->getPropertyId();
            $days = (int) ($request->query("date") ?? 30);


            $startDate = Carbon::now()->subDays($days)->format('Y-m-d');
            $endDate = Carbon::now()->format('Y-m-d');

            $requestBody = new AnalyticsData\RunReportRequest([
                'property' => 'properties/' . $propertyId,
                'dateRanges' => [['startDate' => $startDate, 'endDate' => $endDate]],
                'dimensions' => [
                    ['name' => 'deviceCategory'] // Cihaz kategorisi eklendi
                ],
                'metrics' => [
                    ['name' => 'screenPageViews'],

                ],

            ]);

            $result = $analytics->properties->runReport('properties/' . $propertyId, $requestBody);

            $data = [];

            foreach ($result->getRows() as $row) {
                $data[] = [
                    'deviceCategory' => $row->getDimensionValues()[0]->getValue(), // Cihaz kategorisi
                    'screenPageViews' => $row->getMetricValues()[0]->getValue(), // Görüntüleme sayısı
                ];
            }

            return response()->json($data);
        } catch (\Google\Service\Exception $e) {
            // Google Analytics API'ye özel hata mesajı

            return response()->json(['error' => $e->getMessage()], 500);

        } catch (\Exception $e) {
            // Genel hata yakalama

            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function fetchAllOperatingSystem(Request $request)
    {
        try {
            $analytics = $this->getAnalyticsClient();
            $propertyId = $this->getPropertyId();
            $days = (int) ($request->query("date") ?? 30);


            $startDate = Carbon::now()->subDays($days)->format('Y-m-d');
            $endDate = Carbon::now()->format('Y-m-d');

            $requestBody = new AnalyticsData\RunReportRequest([
                'property' => 'properties/' . $propertyId,
                'dateRanges' => [['startDate' => $startDate, 'endDate' => $endDate]],
                'dimensions' => [
                    ['name' => 'operatingSystem'] // Cihaz kategorisi eklendi
                ],
                'metrics' => [
                    ['name' => 'screenPageViews'],

                ],

            ]);

            $result = $analytics->properties->runReport('properties/' . $propertyId, $requestBody);

            $data = [];

            foreach ($result->getRows() as $row) {
                $data[] = [
                    'operatingSystem' => $row->getDimensionValues()[0]->getValue(), // Cihaz kategorisi
                    'screenPageViews' => $row->getMetricValues()[0]->getValue(), // Görüntüleme sayısı
                ];
            }

            return response()->json($data);
        } catch (\Google\Service\Exception $e) {
            // Google Analytics API'ye özel hata mesajı

            return response()->json(['error' => $e->getMessage()], 500);

        } catch (\Exception $e) {
            // Genel hata yakalama

            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function fetchAllBrowser(Request $request)
    {
        try {
            $analytics = $this->getAnalyticsClient();
            $propertyId = $this->getPropertyId();
            $days = (int) ($request->query("date") ?? 30);


            $startDate = Carbon::now()->subDays($days)->format('Y-m-d');
            $endDate = Carbon::now()->format('Y-m-d');

            $requestBody = new AnalyticsData\RunReportRequest([
                'property' => 'properties/' . $propertyId,
                'dateRanges' => [['startDate' => $startDate, 'endDate' => $endDate]],
                'dimensions' => [
                    ['name' => 'browser'] // Cihaz kategorisi eklendi
                ],
                'metrics' => [
                    ['name' => 'screenPageViews'],

                ],

            ]);

            $result = $analytics->properties->runReport('properties/' . $propertyId, $requestBody);

            $data = [];

            foreach ($result->getRows() as $row) {
                $data[] = [
                    'browser' => $row->getDimensionValues()[0]->getValue(), // Cihaz kategorisi
                    'screenPageViews' => $row->getMetricValues()[0]->getValue(), // Görüntüleme sayısı
                ];
            }

            return response()->json($data);
        } catch (\Google\Service\Exception $e) {
            // Google Analytics API'ye özel hata mesajı

            return response()->json(['error' => $e->getMessage()], 500);

        } catch (\Exception $e) {
            // Genel hata yakalama

            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

}