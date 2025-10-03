<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ads;
use App\Models\Page;
use Carbon\Carbon;

class AdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {



        $data = [
            [
                'id' => 1,
                'title' => "Popup Reklam - Responsivepx",
            ],
            [
                'id' => 2,
                'title' => "Anasayfa Reklam 1 - G: 1175px Y: 180px",
            ],
            [
                'id' => 3,
                'title' => "Anasayfa Reklam 2 - G: 1175px Y: 180px",
            ],
            [
                'id' => 4,
                'title' => "Anasayfa Reklam 3 - G: 1175px Y: 180px",
            ],
            [
                'id' => 5,
                'title' => "Anasayfa Reklam 4 - G: 1175px Y: 180px",
            ],
            [
                'id' => 6,
                'title' => "Anasayfa Reklam 5 - Ana Manşet Altı - G: 1175px Y: 180px",
            ],
            [
                'id' => 7,
                'title' => "Anasayfa Reklam 6 - Yazarlar Üstü - G: 1175px Y: 180px",
            ],
            [
                'id' => 8,
                'title' => "Anasayfa Reklam 7 - Yazarlar Altı - G: 1175px Y: 180px",
            ],
            [
                'id' => 9,
                'title' => "Anasayfa Reklam 8 - Videolar Altı - G: 1175px Y: 180px",
            ],
            [
                'id' => 10,
                'title' => "Anasayfa Reklam 9 - Footer Üzeri - G: 1175px Y: 180px",
            ],
            [
                'id' => 11,
                'title' => "Haber Kategorisi Reklam 1 - G: 1175px Y: 180px",
            ],
            [
                'id' => 12,
                'title' => "Haber Kategorisi Reklam 2 - G: 1175px Y: 180px",
            ],
            [
                'id' => 13,
                'title' => "Haber Kategorisi Reklam 3 - G: 1175px Y: 180px",
            ],
            [
                'id' => 14,
                'title' => "Haber Kategorisi Reklam 4 - Son Dakika Bandı Altı - G: 1175px Y: 180px",
            ],
            [
                'id' => 15,
                'title' => "Haber Kategorisi Reklam 5 - Ana Manşet Altı - G: 1175px Y: 180px",
            ],
            [
                'id' => 16,
                'title' => "Haber Kategorisi Reklam 6 - Diğer Haberler Altı - G: 1175px Y: 180px",
            ],
            [
                'id' => 17,
                'title' => "Haber Detayı Reklam 1 - Son Dakika Bandı Altı - G: 1175px Y: 180px",
            ],
            [
                'id' => 18,
                'title' => "Haber Detayı Reklam 2 - Başlık Altı - G: 1175px Y: 180px",
            ],
            [
                'id' => 19,
                'title' => "Haber Detayı Reklam 3 - Resim Altı - G: 1175px Y: 180px",
            ],
            [
                'id' => 20,
                'title' => "Haber Detayı Reklam 4 - Yorum Alanı Üstü - G: 1175px Y: 180px",
            ],
            [
                'id' => 21,
                'title' => "Haber Detayı Reklam 5 - Haber Sonu - G: 1175px Y: 180px",
            ],

            [
                'id' => 22,
                'title' => "Video Galeriler Listesi Reklam 1 - G: 385px Y: 440px",
            ],
            [
                'id' => 23,
                'title' => "Video Galeriler Listesi Reklam 2 - G: 1175px Y: 180px",
            ],
            [
                'id' => 24,
                'title' => "Video Galeri Detayı Reklam 1 - G: 385px Y: 440px",
            ],
            [
                'id' => 25,
                'title' => "Yazar Listesi Reklam 1 - G: 350px Y: 250px",
            ],
            [
                'id' => 26,
                'title' => "Yazar Detayı Reklam 1 - G: 1175px Y: 180px",
            ],
            [
                'id' => 27,
                'title' => "Yazar Detayı Reklam 2 - G: 777px Y: 125px",
            ],
            [
                'id' => 28,
                'title' => "Yazar Detayı Reklam 3 - G: 375px Y: 250px",
            ],
            [
                'id' => 29,
                'title' => "Manşet Sabit Reklam - G: 777px Y: 510px",
            ],

            [
                'id' => 30,
                'title' => "Logo Yanı Reklam Alanı - G: 728px Y: 90px",
                'images' => "/frontend-v2/assets/images/mbg.png",
            ],
            [
                'id' => 31,
                'title' => "Anasayfa Canlı Reklam",
                'images' => "",
            ],
            // [
            //     'id' => 24,
            //     'title' => "Haber kategori Reklam Alanı 1- G: 728px Y: 90px",
            //     'images'=>"/frontend-v2/assets/images/mbg.png",
            // ],

            // [
            //     'id' => 25,
            //     'title' => "Haber kategori Reklam Alanı 1 - G: 728px Y: 90px",
            //     'images'=>"/frontend-v2/assets/images/mbg.png",
            // ]
        ];

        foreach ($data as $item) {
            Ads::firstOrCreate(
                ['id' => $item['id']],
                [
                    'title' => $item['title'],
                    'images' => isset($item['images']) ? $item['images'] : null,
                    'publish' => 1,
                    'created_at' => Carbon::now(),
                ]
            );
        }




        $pages = [
            [
                'title' => 'KÜNYE ',
                'slug' => ' kunye',
                'content' => '
                <div class="in" id="bik-kunye-main">
                    <ul>
                        <li>
                        <h4>Ticaret &Uuml;nvanı</h4>

                        <div id="bik-kunye-ticaret-unvani">[....] GAZETECİLİK MATBAACILIK SİNEMACILIK TUR. ORG. SAN. TİC. A.Ş.</div>
                        </li>
                        <li>
                        <h4>T&uuml;zel Kişi Temsilcisi</h4>

                        <div id="bik-kunye-tuzel-kisi-temsilcisi">ENDER ALKO&Ccedil;LAR</div>
                        </li>
                        <li>
                        <h4>Genel Yayın Y&ouml;netmeni</h4>

                        <div id="bik-genel-yayin-yonetmeni">SABRİ &Ccedil;AĞLAR</div>
                        </li>
                        <li>
                        <h4>Yayıncı</h4>

                        <div id="bik-kunye-yayinci">www.lidergazete.com</div>
                        </li>
                        <li>
                        <h4>Sorumlu M&uuml;d&uuml;r/Yazı İşleri M&uuml;d&uuml;r&uuml;</h4>

                        <div id="bik-kunye-sorumlu-yim">EMRE G&Uuml;NDOĞDU</div>
                        </li>
                        <li>
                        <h4>Edit&ouml;r</h4>

                        <div>S&Uuml;LEYMAN SOMER - M&Uuml;NEVVER IRMAKSOY - ENİS NAZLI - ŞERİF ALİ DUMAN</div>
                        </li>
                        <li>
                        <h4>Muhabirler</h4>

                        <div>&Ouml;ZGE TOPTAŞ - HAYRETTİN S&Uuml;RAL - İREM KOCA</div>
                        </li>
                        <li>
                        <h4>Yazar</h4>

                        <div>ELİF DENKTAŞ</div>
                        </li>
                        <li>
                        <h4>Reklam Satış Direkt&ouml;r&uuml;</h4>

                        <div>Musa Ustalar - musa.ustalar@liderhabertv.com - 0 535 520 86 29</div>
                        </li>
                        <li>
                        <h4>Reklam Rezervasyon</h4>

                        <div>rezervasyon@liderhabertv.com</div>
                        </li>
                        <li>
                        <h4>Y&ouml;netim Yeri(Şube)</h4>

                        <div id="bik-kunye-yonetim-yeri">Karşıyaka Mah. Sakarya Bul. No:274/A 07090 Kepez / ANTALYA</div>
                        </li>
                        <li>
                        <h4>İletişim Telefonu</h4>

                        <div id="bik-kunye-telefon">0539 777 77 00</div>
                        </li>
                        <li>
                        <h4>Kurumsal E-Posta</h4>

                        <div id="bik-kunye-eposta">muhasebe@lidermedya.com.tr</div>
                        </li>
                        <li>
                        <h4>Ulusal Elektronik Tebligat Adresi (UETS)</h4>

                        <div id="bik-kunye-uets">25878-19309-54249</div>
                        </li>
                        <li>
                        <h4>Yer Sağlayıcı Ticaret &Uuml;nvanı</h4>

                        <div id="bik-kunye-yer-saglayici-unvan">VELİ METİN G&Ouml;K&Ccedil;EK - VMG MEDYA</div>
                        </li>
                        <li>
                        <h4>Yer Sağlayıcı Adresi</h4>

                        <div id="bik-kunye-yer-saglayici-adres">ATAK&Ouml;Y 7-8-9-10. KISIM MAH. &Ccedil;OBAN&Ccedil;EŞME E-5 YAN YOL CAD. B BLOK NO: 12 /2 İ&Ccedil; KAPI NO: 59 BAKIRK&Ouml;Y/ İSTANBUL V.D:BAKIRK&Ouml;Y V.N:&nbsp;48163191340 İSTANBUL</div>
                        </li>
                        <li>
                        <h4>Web Aracı</h4>

                        <div>VELİ METİN G&Ouml;K&Ccedil;EK - VMG MEDYA</div>
                        </li>
                    </ul>
                </div>



                ',
                'created_at' => Carbon::now(),
            ],
            [
                'title' => ' GİZLİLİK POLİTİKASI',
                'slug' => 'gizlilik-politikasi',
                'content' => 'GİZLİLİK POLİTİKASI',
                'created_at' => Carbon::now(),
            ],
            [
                'title' => 'İLETİŞİM',
                'slug' => 'iletisim',
                'content' => '<p><strong>Adres&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;:&nbsp;G&uuml;lbahar Mah. Ak&ccedil;e sok no:12 Merkez/RİZE<br />
                    Telefon&nbsp; &nbsp; &nbsp; &nbsp;:&nbsp;0(464) 214 26 15<br />
                    WhatsApp&nbsp;&nbsp;:&nbsp;05431730353<br />
                    E-Mail&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;:&nbsp;haberrizecomtr@gmail.com</strong></p>
                    <p><strong><a href="https://vmgmedya.com" target="_blank">HABER YAZILIMI FİRMAMIZ : VMG MEDYA</a></strong></p>',
                'created_at' => Carbon::now(),
            ],

        ];


        foreach ($pages as $item) {
            Page::firstOrCreate(
                ['slug' => $item['slug']],
                [
                    'title' => $item['title'],
                    'detail' => $item['content'],
                    'publish' => 1,
                    'created_at' => Carbon::now(),
                ]
            );
        }
    }
}
