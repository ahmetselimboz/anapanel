@extends('backend.layout')


@section("custom_css")
    <style>
        .serp-preview {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            font-family: Arial, sans-serif;
            max-width: 100%;
            overflow-x: hidden;
        }

        .serp-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .search-bar {
            display: flex;
            flex-grow: 1;
            border: 1px solid #ddd;
            border-radius: 24px;
            margin-left: 15px;
            padding: 8px 15px;
            align-items: center;
            min-width: 200px;
        }

        .search-bar input {
            flex-grow: 1;
            border: none;
            outline: none;
            background: transparent;
            width: 100%;
        }

        .search-icons {
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }

        .serp-tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .serp-tabs::-webkit-scrollbar {
            display: none;
        }

        .serp-tab {
            padding: 8px 15px;
            color: #5f6368;
            cursor: pointer;
            font-size: 14px;
            white-space: nowrap;
        }

        .serp-tab.active {
            color: #1a73e8;
            border-bottom: 3px solid #1a73e8;
        }

        .serp-result {
            font-family: Arial, sans-serif;
            max-width: 100%;
            word-wrap: break-word;
        }

        .serp-url {
            color: #202124;
            font-size: 14px;
            word-break: break-all;
        }

        .serp-title {
            color: #1a0dab;
            font-size: 20px;
            margin: 5px 0;
            cursor: pointer;
            word-wrap: break-word;
        }

        .serp-title:hover {
            text-decoration: underline;
        }

        .serp-date {
            color: #70757a;
            font-size: 14px;
            display: inline;
            width: fit-content;
            padding-right: 5px;
        }

        .serp-description {
            color: #4d5156;
            font-size: 14px;
            line-height: 1.58;

            width: 100%;
            word-wrap: break-word;
        }

        #pixelCalculator {
            font-family: Arial, sans-serif;
            font-size: 16px;
            white-space: nowrap;
            visibility: hidden;
            position: absolute;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .form-group.row {
                margin-bottom: 1rem;
            }

            .col-form-label {
                margin-bottom: 0.5rem;
            }

            .titleWidth,
            .urlWidth,
            .descriptionWidth {
                text-align: left;
                margin-top: 0.5rem;
            }

            .search-bar {
                margin-left: 0;
                width: 100%;
            }

            .serp-header img {
                height: 24px;
            }

            .serp-title {
                font-size: 18px;
            }

            .serp-description {
                font-size: 13px;
                padding-left: 10px;
            }

            .card-body {
                padding: 1rem;
            }

            .form-control {
                font-size: 14px;
            }

            .box-title {
                font-size: 1.2rem;
                text-align: center;
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 576px) {
            .serp-preview {
                padding: 10px;
            }

            .search-icons span {
                font-size: 12px;
            }

            .search-icons img {
                width: 16px !important;
                height: 16px !important;
            }

            .form-check {
                margin-left: 0;
            }

            .col-md-2,
            .col-md-8 {
                padding-right: 5px;
                padding-left: 5px;
            }


            .serp-description {

                padding-left: 10px;
            }
        }

        /* Form improvements */
        .form-control:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 0.2rem rgba(26, 115, 232, 0.25);
        }

        .form-check-input:checked {
            background-color: #1a73e8;
            border-color: #1a73e8;
        }

        .text-muted {
            font-size: 0.875rem;
        }
    </style>


@endsection

@section('content')
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12 pt-4">
                    <div class="box">
                        <div
                            class="box-header with-border d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                            <h4 class="box-title">Google Arama Motoru Optimizasyon Sayfası (SERP)</h4>

                        </div>
                        <div class="box-body no-padding">
                            <div class="row justify-content-center mt-4">
                                <div class="col-md-10">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="d-flex align-items-center">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg"
                                                    alt="Google" height="30">

                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <form id="serpForm">
                                                <div class="form-group row align-items-center">
                                                    <label for="title" class="col-md-2 col-form-label">Başlık</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" id="title" name="title"
                                                            placeholder="SEO için optimize edilmiş başlığınız buraya gelecek.">
                                                        <small class="form-text text-muted"><span id="titleChars"
                                                                class="d-none"></span></small>
                                                    </div>
                                                    <div class="col-md-2 text-muted titleWidth">
                                                        <span id="titleWidth">0px</span> / 580px
                                                    </div>
                                                </div>

                                                <div class="form-group row align-items-center">
                                                    <label for="url" class="col-md-2 col-form-label">URL</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" id="url" name="url"
                                                            placeholder="domain.com/webpage">
                                                        <small class="form-text text-muted"><span id="urlChars"
                                                                class="d-none"></span></small>
                                                    </div>
                                                    <div class="col-md-2 text-muted urlWidth">
                                                        <span id="urlWidth">0px</span> / 385px
                                                    </div>
                                                </div>

                                                <div class="form-group row align-items-center">
                                                    <label for="description"
                                                        class="col-md-2 col-form-label">Açıklama</label>
                                                    <div class="col-md-8">
                                                        <textarea class="form-control" id="description" name="description"
                                                            rows="3"
                                                            placeholder="Bu, web sayfanız için bir meta açıklama örneğidir. İzin verilen piksel sayısını aşmadığınızdan emin olun."></textarea>
                                                        <small class="form-text text-muted"><span id="descriptionChars"
                                                                class="d-none"></span></small>
                                                    </div>

                                                    <div class="col-md-2 text-muted descriptionWidth">
                                                        <span id="descriptionWidth">0px</span> / 990px
                                                    </div>
                                                </div>

                                                <div class="form-group row align-items-center">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-8">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="richSnippet">
                                                            <label class="form-check-label" for="richSnippet">
                                                                Zengin İçerikli Arama Sonucu
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row align-items-center" id="dateToggle">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-8">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="showDate">
                                                            <label class="form-check-label" for="showDate">
                                                                Tarih
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <span id="pixelCalculator"></span>

                                            <hr>

                                            <h5>Önizleme:</h5>
                                            <div class="serp-preview">
                                                <div class="serp-header">
                                                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg"
                                                        alt="Google" height="30">
                                                    <div class="search-bar">
                                                        <input type="text" disabled>
                                                        <div class="search-icons">
                                                            <span>⨯</span>
                                                            <span><img
                                                                    src="https://upload.wikimedia.org/wikipedia/commons/e/e8/Google_mic.svg"
                                                                    alt="" class="img-fluid"
                                                                    style="width: 20px; height: 20px;"></span>
                                                            <span><i class="fa fa-search text-info"></i></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="serp-tabs">
                                                    <div class="serp-tab active">Tümü</div>
                                                    <div class="serp-tab">Görseller</div>
                                                    <div class="serp-tab">Videolar</div>
                                                    <div class="serp-tab">Haberler</div>
                                                    <div class="serp-tab">Haritalar</div>
                                                    <div class="serp-tab">Daha Fazla</div>
                                                </div>

                                                <div class="serp-result">
                                                    <div class="serp-url" id="preview-url">domain.com/web-sayfası</div>
                                                    <div class="serp-title" id="preview-title">SEO için optimize edilmiş
                                                        başlığınız buraya gelecek</div>


                                                    <div id="preview-rating" style="display: none;">
                                                        <span style="color: #ffa500;">★★★★★</span>
                                                        <span style="color:#70757a;">Puanlama: 9.4/10 - 112 oy</span>
                                                    </div>
                                                    <div class="row">
                                                        <div class="serp-date" id="preview-date" style="display: none;">
                                                            {{ \Carbon\Carbon::now()->locale('tr')->isoFormat('D MMMM YYYY') }}
                                                            -
                                                        </div>
                                                        <div class="serp-description " id="preview-description">
                                                            Bu, web sayfanız için bir meta açıklama örneğidir. İzin verilen
                                                            piksel sayısını aşmadığınızdan emin olun.
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

@section('custom_js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const titleInput = document.getElementById('title');
            const urlInput = document.getElementById('url');
            const descriptionInput = document.getElementById('description');
            const richSnippetCheckbox = document.getElementById('richSnippet');
            const showDateCheckbox = document.getElementById('showDate');
            const dateToggle = document.getElementById('dateToggle');
            const previewRating = document.getElementById('preview-rating');
            const titleCharsCount = document.getElementById('titleChars');
            const descriptionCharsCount = document.getElementById('descriptionChars');

            const previewTitle = document.getElementById('preview-title');
            const previewUrl = document.getElementById('preview-url');
            const previewDescription = document.getElementById('preview-description');
            const previewDate = document.getElementById('preview-date');

            // Update character counts on load
            titleCharsCount.textContent = titleInput.value.length;
            descriptionCharsCount.textContent = descriptionInput.value.length;

            // Update preview and counts when inputs change
            titleInput.addEventListener('input', function () {
                previewTitle.textContent = this.value || 'SEO için optimize edilmiş başlığınız buraya gelecek';
                titleCharsCount.textContent = this.value.length;
            });

            urlInput.addEventListener('input', function () {
                previewUrl.textContent = this.value || 'domain.com/web-sayfası';
            });

            descriptionInput.addEventListener('input', function () {
                previewDescription.textContent = this.value || 'Bu, web sayfanız için bir meta açıklama örneğidir. İzin verilen piksel sayısını aşmadığınızdan emin olun.';
                descriptionCharsCount.textContent = this.value.length;
            });

            richSnippetCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    previewRating.style.display = 'block';
                } else {
                    showDateCheckbox.checked = false;
                    previewDate.style.display = 'none';
                    previewRating.style.display = 'none';
                }
            });

            // Toggle date display
            showDateCheckbox.addEventListener('change', function () {
                previewDate.style.display = this.checked ? 'inline' : 'none';
            });


            const pixelCalculator = document.getElementById('pixelCalculator');
            const titleWidth = document.getElementById('titleWidth');
            const urlWidth = document.getElementById('urlWidth');
            const descriptionWidth = document.getElementById('descriptionWidth');

            function measurePixelWidth(text) {
                pixelCalculator.textContent = text;
                return pixelCalculator.offsetWidth;
            }

            function updateFieldLimit(inputEl, widthEl, limit, countWrapperSelector) {
                const width = measurePixelWidth(inputEl.value);
                widthEl.textContent = width + "px";

                const countWrapper = document.querySelector(countWrapperSelector);

                if (width > limit) {
                    inputEl.classList.add("border-danger");
                    if (countWrapper) countWrapper.classList.add("text-danger");
                } else {
                    inputEl.classList.remove("border-danger");
                    if (countWrapper) countWrapper.classList.remove("text-danger");
                }
            }

            function updatePreviewFields() {
                previewTitle.textContent = titleInput.value || 'SEO için optimize edilmiş başlığınız buraya gelecek';
                previewUrl.textContent = urlInput.value || 'domain.com/web-sayfası';
                previewDescription.textContent = descriptionInput.value || 'Bu, web sayfanız için bir meta açıklama örneğidir. İzin verilen piksel sayısını aşmadığınızdan emin olun.';

                titleCharsCount.textContent = titleInput.value.length;
                descriptionCharsCount.textContent = descriptionInput.value.length;



                updateFieldLimit(titleInput, titleWidth, 580, ".titleWidth");
                updateFieldLimit(urlInput, urlWidth, 385, ".urlWidth");
                updateFieldLimit(descriptionInput, descriptionWidth, 990, ".descriptionWidth");
            }

            titleInput.addEventListener('input', updatePreviewFields);
            urlInput.addEventListener('input', updatePreviewFields);
            descriptionInput.addEventListener('input', updatePreviewFields);

            // İlk yüklemede çalıştır
            updatePreviewFields();
        });



    </script>
@endsection