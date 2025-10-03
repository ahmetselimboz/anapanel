@extends('backend.layout')

@section('custom_css')
    <style>
        .trend-item {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            transition: background-color 0.2s;
        }

        .trend-item:hover {
            background-color: #f8f9fa;
        }

        .trend-title {
            font-size: 16px;
            color: #202124;
            margin-bottom: 8px;
            cursor: pointer;
        }

        .trend-title:hover {
            text-decoration: underline;
        }

        .trend-stats {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .search-volume {
            color: #70757a;
            font-size: 14px;
        }

        .trend-time {
            color: #70757a;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .trend-time i {
            margin-right: 4px;
            font-size: 16px;
        }

        .trend-status {
            color: #0f9d58;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .trend-status i {
            margin-right: 4px;
        }

        .trend-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .trend-tag {
            color: #1a0dab;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
        }

        .trend-tag:hover {
            text-decoration: underline;
        }

        .trend-tag:not(:last-child):after {
            content: "•";
            color: #70757a;
            margin-left: 8px;
        }

        .trend-graph {
            width: 120px;
            height: 40px;
            margin-left: auto;
        }

        .trend-graph img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .trend-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .trend-header h5 {
            margin: 0;
            color: #202124;
        }

        .trend-header .trend-info {
            color: #70757a;
            font-size: 14px;
        }

        .options-card {

        }

        .options-card:hover {
            background-color: #00000024;
        }


    </style>
@endsection

@section('content')
    <div class="modal fade" id="aboutNewsModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable ">
            <div class="modal-content rounded-3">
                <div class="modal-header bg-primary">
                    <h4 id="modal-title" class="text-center m-2">İlgili Haberler</h4>
                    <button type="button" id="close-model" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Kapat"></button>
                </div>
                <hr class="m-0">
                <div class="modal-body my-3">

                </div>
            </div>

        </div>
    </div>


    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Google Trendler</h5>
                            <div class="box-controls pull-right">
                                <span class="trend-info">Güncelleme: {{ now()->format('H:i') }}</span>
                            </div>

                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr class="text-center">
                                        <th>Trendler</th>
                                        <th>Arama Hacmi</th>
                                        <th>Başlangıç</th>
                                        <th>İlgili Haberler</th>
                                    </tr>
                                </thead>

                                <tbody id="trend-container">
                                    <tr class="text-center">
                                        <td>
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('custom_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function formatNumber(rawNumber) {
                const isPlus = rawNumber.includes('+');
                const number = parseInt(rawNumber.replace('+', ''), 10);
                if (isNaN(number)) return rawNumber;
                return number >= 1000 ? (number / 1000).toFixed(0) + (isPlus ? ' B+' : ' B') : number + (isPlus ? '+' : '');
            }

            function encodeHTML(html) {
                return btoa(unescape(encodeURIComponent(html)));
            }

            function decodeHTML(encoded) {
                return decodeURIComponent(escape(atob(encoded)));
            }

            function updateTrends() {
                fetch('/secure/get-trends')
                    .then(response => response.json())
                    .then(data => {
                        const container = document.querySelector('#trend-container');
                        container.innerHTML = '';

                        data.forEach((trend, index) => {
                            let newsHtml = '';
                            trend.news.forEach(news => {
                                newsHtml += `
                                                <a href="${news.url}" target="_blank" class="row mb-2 text-decoration-none text-dark py-2 options-card">
                                                    <div class="col-4">
                                                        <img src="${news.image}" alt="" class="img-fluid rounded">
                                                    </div>
                                                    <div class="col-8 text-start">
                                                        <h4 style="font-size: 16px;">${news.title}</h4>
                                                        <p style="font-size: 13px; margin-top: 4px; margin-bottom: 0;">Kaynak: ${news.source}</p>
                                                    </div>
                                                </a>
                                            `;
                            });

                            const encodedNews = encodeHTML(newsHtml);

                            const trendHtml = `
                                            <tr class="text-center">
                                                <td><h6>${trend.title}</h6></td>
                                                <td><p class="mb-0">${formatNumber(trend.traffic)}</p></td>
                                                <td>${trend.timeAgo}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <button type="button"
                                                            class="btn btn-primary open-news-modal"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#aboutNewsModal"
                                                            data-news='${encodedNews}'>
                                                            İlgili Haberler
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        `;

                            container.innerHTML += trendHtml;
                        });

                        // Modal içerik yerleştirme
                        document.querySelectorAll('.open-news-modal').forEach(button => {
                            button.addEventListener('click', function () {
                                const modalBody = document.querySelector('#aboutNewsModal .modal-body');
                                const encodedHtml = this.getAttribute('data-news');
                                const decodedHtml = decodeHTML(encodedHtml);
                                modalBody.innerHTML = decodedHtml;
                            });
                        });
                    })
                    .catch(error => console.error('Trend verileri alınamadı:', error));
            }

            updateTrends();
            setInterval(updateTrends, 300000); // 5 dakika
        });
    </script>



@endsection