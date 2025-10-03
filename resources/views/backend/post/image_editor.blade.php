<style>
    .tooltip-container {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }

    .tooltip {
        visibility: hidden;
        background-color: #333;
        color: #fff;
        text-align: left;
        padding: 10px;
        border-radius: 6px;
        width: 300px;
        position: absolute;
        top: 35px;
        /*left: -60px;*/
        z-index: 10;
        opacity: 0;
        transition: opacity 0.3s, visibility 0.3s;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        font-size: 14px;
        line-height: 1.5;
    }

    .tooltip-container:hover .tooltip {
        visibility: visible;
        opacity: 1;
    }
</style>
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" style="max-width: 1024px">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-primary">
                <h4 id="modal-title" class="text-center m-2">Resim Tasarla</h4>
                <button type="button" id="close-model" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Kapat"></button>
            </div>
            <hr class="mt-0">
            <div class="modal-body d-flex align-items-center justify-content-center" id="imageModalBody"
                 style="min-height: 400px;">
                <div class="row g-3 p-3 border rounded d-flex flex-column align-items-center justify-content-center"
                    id="imageBox" style="width: 100%">
                    <div class="row align-items-center justify-content-center">
                    <div class="col-md-3" style="height: fit-content">
                        <label for="imageWidth" class="form-label">Genişlik</label>
                            <input type="text" name="imageWidth" id="imageWidth" class="form-control" value="777"
                                placeholder="Genişlik">
                    </div>
                    <div class="col-md-3" style="height: fit-content">
                        <label for="imageHeight" class="form-label">Yükseklik</label>
                            <input type="text" name="imageHeight" id="imageHeight" class="form-control" value="510"
                                placeholder="Yükseklik">
                        </div>
                    </div>

                    <div class="col-md-3 text-center mt-3">
                        <input type="file" name="chooseImage" id="chooseImage" class="d-none">
                        <label for="chooseImage" class="btn btn-primary">Resim Seç</label>
                    </div>
                    <div class="col-md-3 text-center mt-3">
                        <label class="form-label">Ya da</label>
                    </div>
                    <div class="col-md-4 text-center mt-3">

                        <button class="btn btn-primary" id="openGalleryBtn">Galeriden Seç</button>
                    </div>

                </div>
                <div class="d-none" id="editorBox" style="width: 100%">
                    <div id="editor_container" style="width: 100%">

                    </div>

                </div>

            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-secondary" id="close-model" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script
    src="https://scaleflex.cloudimg.io/v7/plugins/filerobot-image-editor/latest/filerobot-image-editor.min.js"></script>
<script>
    $(document).ready(function () {


        let selectedImage = null;
        let selectedImageName = null;
        let selectedMethod = null;

        $('.imageModal').click(function () {

            $("#imageModal").modal("show");
            selectedMethod = $(this).data("method")
            console.log(selectedMethod)
            if (selectedMethod === "edit") {
                selectedImage = $("#currentImage").attr('src');
            }


            if (selectedImage) {
                showImageEditor();
            } else {
                $('#editorBox').addClass('d-none'); // Başlangıçta editör gizlenir
                $('#imageBox').removeClass('d-none'); // Başlangıçta editör gizlenir
            }
        });

        $('#chooseImage').on('change', function (event) {
            const file = event.target.files[0];

            selectedImageName = file.name;
            selectedImageName = selectedImageName.substring(0, selectedImageName.lastIndexOf(".")) || selectedImageName;
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const width = parseInt($('#imageWidth').val()) || 777; // Varsayılan genişlik
                    const height = parseInt($('#imageHeight').val()) || 510; // Varsayılan yükseklik

                    resizeImage(e.target.result, width, height, function (resizedImage) {
                        selectedImage = resizedImage;

                        showImageEditor();
                    });

                };
                reader.readAsDataURL(file);
            } else {
                showWarning(); // Eğer resim seçilmezse uyarı ver
            }
        });

        function showImageEditor() {
            console.log(selectedImage);
            if (!selectedImage) return;

            $("#editorBox").removeClass("d-none");
            $("#imageBox").addClass("d-none");
            $("#chooseImage").val('');

            // document.querySelector("input[placeholder='Ad']").removeAttribute("disabled");
            // document.querySelector("input[placeholder='Ad']").removeAttribute("readonly");

            if (window.filerobotImageEditor) {
                window.filerobotImageEditor.terminate(); // Önceki editörü kapat
            }

            const { TABS, TOOLS } = window.FilerobotImageEditor;
            const config = {
                source: selectedImage,
                onSave: (editedImageObject, designState) => {
                    console.log("Kaydedildi:", editedImageObject);
                    //  addImagePreview(editedImageObject.imageBase64);

                    const imageInput = document.getElementById("images");

                    // Resmi selected_image_from_gallery'ye kaydet
                    const selectedImageElement = document.getElementById("selected_image_from_gallery");
                    if (selectedImageElement) {
                        selectedImageElement.src = editedImageObject.imageBase64;
                    }

                    let fileName = editedImageObject.name;
                    fileName = fileName.substring(0, fileName.lastIndexOf(".")) || fileName;
                    console.log(fileName);
                    const file = new File([dataURLtoBlob(editedImageObject.imageBase64)], fileName, { type: editedImageObject.mimeType });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    imageInput.files = dataTransfer.files;
                    selectedImage = null;
                    if (document.querySelector('#toastMessage')) {
                        document.querySelector('#toastMessage').remove();
                    }

                    filerobotImageEditor.terminate();
                    $("#imageModal").modal('hide');
                },


                Text: { text: 'VMG Medya...' },

                language: "tr",  // Türkçe dilini kullan
                useBackendTranslations: true,  // Backend çeviri servisini kapat, kendi çevirilerimizi kullanacağız
                defaultSavedImageName: selectedImageName,
                defaultSavedImageType: 'jpeg',
                translations: {
                    name: 'Ad',
                    save: 'Kaydet',
                    saveAs: 'Farklı Kaydet',
                    back: 'Geri',
                    loading: 'Yükleniyor...',
                    resetOperations: 'Tüm işlemleri sıfırla/sil',
                    changesLoseWarningHint:
                        '“Sıfırla” düğmesine basarsanız değişiklikleriniz kaybolacaktır. Devam etmek istiyor musunuz?',
                    discardChangesWarningHint:
                        'Modülü kapatırsanız, son değişikliğiniz kaydedilmeyecek.',
                    cancel: 'İptal',
                    apply: 'Uygula',
                    warning: 'Uyarı',
                    confirm: 'Onayla',
                    discardChanges: 'Değişiklikleri at',
                    undoTitle: 'Son işlemi geri al',
                    redoTitle: 'Son işlemi yinele',
                    showImageTitle: 'Orijinal resmi göster',
                    zoomInTitle: 'Yakınlaştır',
                    zoomOutTitle: 'Uzaklaştır',
                    toggleZoomMenuTitle: 'Yakınlaştırma menüsünü aç/kapat',
                    adjustTab: 'Ayarlar',
                    finetuneTab: 'İnce Ayar',
                    filtersTab: 'Filtreler',
                    watermarkTab: 'Filigran',
                    annotateTabLabel: 'Açıklama Ekle',
                    resize: 'Yeniden Boyutlandır',
                    resizeTab: 'Boyutlandır',
                    imageName: 'Resim adı',
                    invalidImageError: 'Geçersiz resim sağlandı.',
                    uploadImageError: 'Resim yüklenirken hata oluştu.',
                    areNotImages: 'görsel değil',
                    isNotImage: 'görsel değil',
                    toBeUploaded: 'yüklenecek',
                    cropTool: 'Kırp',
                    original: 'Orijinal',
                    custom: 'Özel',
                    square: 'Kare',
                    landscape: 'Yatay',
                    portrait: 'Dikey',
                    ellipse: 'Elips',
                    classicTv: 'Klasik TV',
                    cinemascope: 'Sinemaskop',
                    arrowTool: 'Ok',
                    blurTool: 'Bulanıklık',
                    brightnessTool: 'Parlaklık',
                    contrastTool: 'Kontrast',
                    ellipseTool: 'Elips',
                    unFlipX: 'X Ekseni Eski Haline Getir',
                    flipX: 'X Ekseni Çevir',
                    unFlipY: 'Y Ekseni Eski Haline Getir',
                    flipY: 'Y Ekseni Çevir',
                    hsvTool: 'HSV',
                    hue: 'Renk Tonu',
                    brightness: 'Parlaklık',
                    saturation: 'Doygunluk',
                    value: 'Değer',
                    imageTool: 'Resim',
                    importing: 'İçeri aktarılıyor...',
                    addImage: '+ Resim ekle',
                    uploadImage: 'Resim yükle',
                    fromGallery: 'Galeriden',
                    lineTool: 'Çizgi',
                    penTool: 'Kalem',
                    polygonTool: 'Çokgen',
                    sides: 'Kenarlar',
                    rectangleTool: 'Dikdörtgen',
                    cornerRadius: 'Köşe Yarıçapı',
                    resizeWidthTitle: 'Genişlik (piksel)',
                    resizeHeightTitle: 'Yükseklik (piksel)',
                    toggleRatioLockTitle: 'Oran kilidini aç/kapat',
                    resetSize: 'Orijinal boyuta sıfırla',
                    rotateTool: 'Döndür',
                    textTool: 'Metin',
                    textSpacings: 'Metin Aralıkları',
                    textAlignment: 'Metin Hizalama',
                    fontFamily: 'Yazı Tipi',
                    size: 'Boyut',
                    letterSpacing: 'Harf Aralığı',
                    lineHeight: 'Satır Yüksekliği',
                    warmthTool: 'Sıcaklık',
                    addWatermark: '+ Filigran ekle',
                    addTextWatermark: '+ Metin Filigranı Ekle',
                    addWatermarkTitle: 'Filigran türünü seçin',
                    uploadWatermark: 'Filigran yükle',
                    addWatermarkAsText: 'Metin olarak ekle',
                    padding: 'Dolgu',
                    paddings: 'Dolgular',
                    shadow: 'Gölge',
                    horizontal: 'Yatay',
                    vertical: 'Dikey',
                    blur: 'Bulanıklık',
                    opacity: 'Şeffaflık',
                    transparency: 'Saydamlık',
                    position: 'Pozisyon',
                    stroke: 'Çizgi',
                    saveAsModalTitle: 'Farklı Kaydet',
                    extension: 'Uzantı',
                    format: 'Biçim',
                    nameIsRequired: 'Ad gereklidir.',
                    quality: 'Kalite',
                    imageDimensionsHoverTitle: 'Kaydedilen resim boyutu (genişlik x yükseklik)',
                    cropSizeLowerThanResizedWarning:
                        'Not: Seçilen kırpma alanı uygulanan yeniden boyutlandırmadan küçük olabilir ve kalite düşebilir',
                    actualSize: 'Gerçek boyut (100%)',
                    fitSize: 'Sığdır',
                    addImageTitle: 'Eklemek için resim seçin...',
                    mutualizedFailedToLoadImg: 'Resim yüklenemedi.',
                    tabsMenu: 'Menü',
                    download: 'İndir',
                    width: 'Genişlik',
                    height: 'Yükseklik',
                    plus: '+',
                    cropItemNoEffect: 'Bu kırpma öğesi için önizleme yok',
                },
                tabsIds: [TABS.ADJUST, TABS.ANNOTATE, TABS.WATERMARK, TABS.FINETUNE, TABS.FILTERS],
                defaultTabId: TABS.ADJUST,

            };

            const filerobotImageEditor = new FilerobotImageEditor(
                document.querySelector('#editor_container'),
                config,
            );

            filerobotImageEditor.render({
                onClose: (closingReason) => {
                    selectedImage = null;
                    filerobotImageEditor.terminate();
                    if (document.querySelector('#toastMessage')) {
                        document.querySelector('#toastMessage').remove();
                    }

                },
            });
        }


        function resizeImage(base64, width, height, callback) {
            const img = new Image();
            img.src = base64;
            img.onload = function () {
                const canvas = document.createElement("canvas");
                canvas.width = width;
                canvas.height = height;

                const ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0, width, height);

                const resizedImage = canvas.toDataURL("image/png");
                callback(resizedImage);
            };
        }


        function addImagePreview(imageBase64) {
            const imagePreview = document.createElement("div");
            imagePreview.className = "image-preview mt-2";
            imagePreview.style.maxWidth = "100px";

            const img = document.createElement("img");
            img.id = "image-preview"
            img.src = imageBase64;
            img.style.width = "100%";
            img.style.height = "auto";
            img.style.borderRadius = "4px";

            imagePreview.appendChild(img);
            document.getElementById("images").insertAdjacentElement("afterend", imagePreview);
        }

        function showWarning() {
            $("#imageModal").modal('hide');
            const warning = document.createElement("div");
            warning.classList.add("warning-message", "mt-2", "mx-4");
            warning.style.color = "red";
            warning.textContent = "Lütfen bir resim seçin!";
            document.getElementById("images").insertAdjacentElement("afterend", warning);
        }

        function dataURLtoBlob(dataURL) {
            const byteString = atob(dataURL.split(",")[1]);
            const mimeString = dataURL.split(",")[0].split(":")[1].split(";")[0];
            const arrayBuffer = new ArrayBuffer(byteString.length);
            const uint8Array = new Uint8Array(arrayBuffer);

            for (let i = 0; i < byteString.length; i++) {
                uint8Array[i] = byteString.charCodeAt(i);
            }

            return new Blob([uint8Array], { type: mimeString });
        }

        const showToast = (message) => {
            const toast = document.createElement('div');
            toast.id = 'toastMessage';
            toast.innerText = message;
            toast.style.position = 'fixed';
            toast.style.bottom = '90px';
            toast.style.left = '50%';
            toast.style.transform = 'translateX(-50%)';
            toast.style.backgroundColor = '#e4272875';
            toast.style.color = '#950f10';
            toast.style.padding = '10px 20px';
            toast.style.borderRadius = '8px';
            toast.style.border = '2px solid #950f10';
            toast.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.2)';
            toast.style.zIndex = '9999';

            $("#imageModalBody").append(toast);

            // setTimeout(() => {
            //     toast.remove();
            // }, 3000); // 3 saniye sonra kaybolur
        };

        const waitForEditor = setInterval(() => {
            const editorContainer = document.querySelector('.FIE_root');

            if (editorContainer) {
                clearInterval(waitForEditor); // Intervali durdur

                const observer = new MutationObserver((mutations) => {
                    mutations.forEach(mutation => {
                        mutation.addedNodes.forEach(node => {
                            if (node.nodeType === 1 && node.classList.contains('FIE_text-tool-options')) {
                                const textToolButton = document.querySelector('.FIE_text-bold-option');
                                // "FIE_text-tool-button" kontrolü
                                if (textToolButton) {
                                    showToast('Metin eklemek için fareyi görsel üzerinde sürükleyip bırakınız. Metni değiştirmek için metin üzerinde çift tıklayınız')
                                } else {
                                    document.querySelector('#toastMessage').remove();
                                }
                            }
                        });
                        mutation.removedNodes.forEach(node => {
                            if (node.nodeType === 1 && node.classList.contains('FIE_text-tool-options')) {
                                document.querySelector('#toastMessage').remove();
                            }
                        });
                    });
                });

                observer.observe(editorContainer, { childList: true, subtree: true });
            }
        }, 500); // Her 500ms'de bir kontrol et

        // Galeriden Seç butonuna tıklandığında
        $('#openGalleryBtn').click(function () {
            loadGalleryModal();
            $('#imageSelectWithEditorModal').modal('show');
        });

        // Dinamik olarak içerik yüklendikten sonra form submit işlemi
        $(document).on('submit', '#modal-gallery-search-form', function (e) {
            e.preventDefault();
            loadGalleryModal($(this).serialize());
        });

        // Sayfalama linklerine tıklama
        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');
            let params = new URLSearchParams(url.split('?')[1]);
            loadGalleryModal(params.toString());
        });

        // Görsel seçilince işlem yap
        $(document).on('click', '.select-image', function () {
            const path = $(this).data('path');
            const width = parseInt($('#imageWidth').val()) || 777;
            const height = parseInt($('#imageHeight').val()) || 510;

            // Seçilen resmi editöre yükle
            loadImageFromUrl(path, width, height);

            // Modalı kapat
            $('#imageSelectWithEditorModal').modal('hide');
        });

        function loadImageFromUrl(url, width, height) {
            const img = new Image();
            img.crossOrigin = "Anonymous";
            img.onload = function () {
                resizeImage(img.src, width, height, function (resizedImage) {
                    selectedImage = resizedImage;
                    showImageEditor();
                });
            };
            img.src = url;
        }

        function loadGalleryModal(params = '') {
            $('#modal-content-gallery-with-editor').html('<div class="text-center my-5"><div class="spinner-border text-primary"></div></div>');

            $.ajax({
                url: '{{ route("gallery.modal.content") }}' + (params ? '?' + params : ''),
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    const images = response.images.data;
                    const search = response.search;
                    const paginationLinks = response.images.links;
                    let html = '';

                    // 🔍 Arama formu
                    html += `
                    <form method="GET" id="modal-gallery-search-form" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Resim adına göre ara..." value="${search ?? ''}">
                            <button type="submit" class="btn btn-sm btn-primary">Ara</button>
                        </div>
                    </form>
                    <div class="row">`;

                    // 🖼️ Görseller
                    if (images.length > 0) {
                        images.forEach(image => {
                            html += `
                            <div class="col-6 col-md-4 mb-3">
                                <div class="position-relative img-hover-zoom image-wrapper border rounded">
                                    <div class="spinner-overlay">
                                        <div class="spinner-border text-secondary" role="status" style="width: 2rem; height: 2rem;">
                                            <span class="visually-hidden">Yükleniyor...</span>
                                        </div>
                                    </div>
                                    <img src="/uploads/${image}" alt="${image}" class="img-fluid select-image"
                                         data-path="/uploads/${image}"
                                         onload="this.style.display='block'; this.closest('.image-wrapper').querySelector('.spinner-overlay').style.display='none';">
                                </div>
                                <p class="small text-muted text-center mt-1 text-truncate" title="${image}">${image}</p>
                            </div>`;
                        });
                    } else {
                        html += `<p class="text-muted px-3">Görsel bulunamadı.</p>`;
                    }

                    html += `</div>`;

                    // ⏩ Pagination
                    if (paginationLinks && paginationLinks.length > 3) {
                        html += `<div class="pagination-wrapper overflow-auto text-center mt-2">
                            <div class="d-inline-block">
                                <ul class="pagination pagination-sm justify-content-center mb-0">`;

                        paginationLinks.forEach(link => {
                            if (link.url === null) {
                                html += `<li class="page-item disabled"><span class="page-link">${link.label}</span></li>`;
                            } else {
                                html += `
                                <li class="page-item ${link.active ? 'active' : ''}">
                                    <a class="page-link" href="${link.url}">${link.label}</a>
                                </li>`;
                            }
                        });

                        html += `   </ul>
                            </div>
                        </div>`;
                    }

                    $('#modal-content-gallery-with-editor').html(html);
                },
                error: function () {
                    $('#modal-content-gallery-with-editor').html('<p class="text-danger">Galeriden içerik yüklenemedi.</p>');
                }
            });
        }

    });


</script>

<!-- Galeri Modal -->
<div class="modal fade" id="imageSelectWithEditorModal" tabindex="-1" aria-labelledby="imageSelectWithEditorModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" style="max-width: 1024px">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="imageSelectWithEditorModalLabel">Galeriden Resim Seç</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body p-4">
                <div class="" id="modal-content-gallery-with-editor">
                </div>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Geri Dön</button>
            </div>
        </div>
    </div>
</div>