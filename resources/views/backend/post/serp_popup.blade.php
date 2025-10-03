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
        display: inline;
        width: calc(100% - 150px);
        word-wrap: break-word;
    }
</style>
<div class="modal fade" id="serpModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-warning">
                <h4 id="modal-title" class="text-center m-2">Google Arama Motoru Optimizasyonu</h4>
                <button type="button" id="close-model" class="btn-close text-white" data-bs-dismiss="modal"
                    aria-label="Kapat"></button>
            </div>
            <hr class="mt-0">
            <div class="modal-body">

                <div class="serp-preview">
                    <div class="serp-header">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" alt="Google"
                            height="30">
                        <div class="search-bar">
                            <input type="text" disabled>
                            <div class="search-icons">
                                <span>⨯</span>
                                <span><img src="https://upload.wikimedia.org/wikipedia/commons/e/e8/Google_mic.svg"
                                        alt="" class="img-fluid" style="width: 20px; height: 20px;"></span>
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
                        <div class="serp-url" id="preview-url"></div>
                        <div class="serp-title" id="preview-title">SEO için optimize edilmiş başlığınız buraya gelecek
                        </div>
                        <div>
                            <div class="serp-date" id="preview-date">
                                {{ \Carbon\Carbon::now()->locale('tr')->isoFormat('D MMMM YYYY') }} -
                            </div>
                            <div class="serp-description" id="preview-description">Bu, web sayfanız için bir meta
                                açıklama örneğidir. İzin verilen piksel sayısını aşmadığınızdan emin olun.</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        // URL'yi ayarla
        document.getElementById('preview-url').textContent = window.location.origin;

        // Ana formdaki inputları seç
        const mainTitleInput = document.getElementById('title');
        const mainDescriptionInput = document.getElementById('description');
        const mainTitleWidth = document.getElementById('titleWidth');
        const mainDescriptionWidth = document.getElementById('descriptionWidth');

        // Preview elementlerini seç
        const previewTitle = document.getElementById('preview-title');
        const previewDescription = document.getElementById('preview-description');
        const pixelCalculator = document.getElementById('pixelCalculator');

        function measurePixelWidth(text) {
            if (!pixelCalculator) return 0;
            pixelCalculator.style.fontFamily = 'Arial, sans-serif';
            pixelCalculator.style.fontSize = '20px'; // Title için
            pixelCalculator.textContent = text;
            return pixelCalculator.offsetWidth;
        }

        function measureDescriptionPixelWidth(text) {
            if (!pixelCalculator) return 0;
            pixelCalculator.style.fontFamily = 'Arial, sans-serif';
            pixelCalculator.style.fontSize = '14px'; // Description için
            pixelCalculator.textContent = text;
            return pixelCalculator.offsetWidth;
        }

        function updateFieldLimit(inputEl, widthEl, limit, isTitle = true) {
            const width = isTitle ? measurePixelWidth(inputEl.value) : measureDescriptionPixelWidth(inputEl.value);
            if (widthEl) {
                widthEl.textContent = width + "px";

                // Piksel limitini aşınca kırmızı uyarı göster
                if (width > limit) {
                    widthEl.parentElement.classList.add("text-danger");
                    inputEl.classList.add("border-danger");
                } else {
                    widthEl.parentElement.classList.remove("text-danger");
                    inputEl.classList.remove("border-danger");
                }
            }
        }

        function updatePreview() {
            // Title güncelleme
            if (mainTitleInput && previewTitle) {
                const titleText = mainTitleInput.value || 'SEO için optimize edilmiş başlığınız buraya gelecek';
                previewTitle.textContent = titleText;
                updateFieldLimit(mainTitleInput, mainTitleWidth, 580, true);
            }

            // Description güncelleme
            if (mainDescriptionInput && previewDescription) {
                const descText = mainDescriptionInput.value || 'Bu, web sayfanız için bir meta açıklama örneğidir. İzin verilen piksel sayısını aşmadığınızdan emin olun.';
                previewDescription.textContent = descText;
                updateFieldLimit(mainDescriptionInput, mainDescriptionWidth, 990, false);
            }
        }

        // Event listeners
        if (mainTitleInput) {
            mainTitleInput.addEventListener('input', updatePreview);
        }

        if (mainDescriptionInput) {
            mainDescriptionInput.addEventListener('input', updatePreview);
        }

        // Modal açıldığında preview'i güncelle
        $('#serpModal').on('shown.bs.modal', function () {
            updatePreview();
        });

        // Sayfa yüklendiğinde ilk güncellemeyi yap
        updatePreview();
    });

</script>