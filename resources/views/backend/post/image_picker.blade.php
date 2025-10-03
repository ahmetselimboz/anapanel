<style>
    .card-img-top {
        height: 200px;
        object-fit: cover;
        border-radius: 4px;
    }

    .img-hover-zoom {
        overflow: hidden;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }

    .img-hover-zoom img {
        transition: transform 0.3s ease;
    }

    .img-hover-zoom:hover img {
        transform: scale(1.1);
    }

    .image-wrapper {
        position: relative;
        width: 100%;
        padding-top: 75%;
        /* oran korumak için (4:3) */
        background-color: #f3f3f3;
        overflow: hidden;
        border-radius: 8px;
    }

    .image-wrapper img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
        /* ilk başta gizli */
    }

    .spinner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1;
    }

    .pagination-wrapper {
        max-width: 100%;
        overflow-x: auto;
        padding: 10px 0;
        white-space: nowrap;
    }

    .select-image {
        cursor: pointer;
    }

    .modal-wide .modal-dialog {
        max-width: 1024px;
        width: 95%;
        transition: all 0.3s ease;
    }

    .preview-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.9);
        display: none;
        z-index: 9999;
        padding: 2rem;
    }

    .preview-modal img {
        max-width: 100%;
        max-height: 80vh;
        display: block;
        margin: 0 auto;
    }

    .preview-modal .preview-actions {
        text-align: center;
        margin-top: 1rem;
    }

    .preview-modal .close-preview {
        position: absolute;
        top: 1rem;
        right: 1rem;
        color: white;
        font-size: 2rem;
        cursor: pointer;
    }
</style>

<!-- Gizli input -->


<!-- Preview Modal -->
<div class="preview-modal">
    <span class="close-preview">&times;</span>
    <img src="" alt="Preview" id="preview-image">
    <div class="preview-actions">
        <button class="btn btn-primary" id="select-image-btn">Resmi Seç</button>
    </div>
</div>

<!-- Resim Seç Modalı -->
<div class="modal fade" id="imageSelectModal" tabindex="-1" aria-labelledby="imageSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="imageSelectModalLabel">Resim Yükle veya Galeriden Seç</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body p-4">
                <div id="modal-content-default">
                    <div class="row mb-3">
                        <label for="images" class="form-label">Resim Yükle</label>
                        <input type="file" class="form-control" name="images" id="main_image_input"
                            accept="image/png, image/gif, image/jpeg">
                    </div>
                    <div class="row my-1 text-center">
                        <label class="form-label">Ya da</label>
                    </div>
                    <div class="row">
                        <button type="button" class="btn btn-primary" id="open-gallery-btn">
                            Galeriden Seç
                        </button>
                    </div>
                </div>
                <div class="" id="modal-content-gallery">

                </div>

            </div>
        </div>
    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        let selectedImagePath = '';

        // File input değişikliğini dinle
        $('#main_image_input').on('change', function (e) {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const imageUrl = URL.createObjectURL(file);

                // Ana input'a dosyayı kopyala
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                document.getElementById('images').files = dataTransfer.files;

                // Önizleme resmini güncelle
                $('#selected_image_from_gallery').attr('src', imageUrl);
                $('#selected_image_from_gallery').removeClass('d-none');
                $('#selected_image_from_gallery').css('display', 'block');
                $('#pick-image-btn').text('Başka Resim Seç');
                // Modalı kapat
                $('#imageSelectModal').modal('hide');
            }
        });

        // Butona tıklanırsa modal içeriği yüklenir
        $('#open-gallery-btn').click(function () {
            loadGalleryModal();
            $('#imageSelectModal').addClass('modal-wide');
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

        // Geri dön
        $(document).on('click', '#back-upload-btn', function () {
            $('#modal-content-gallery').hide();
            $('#modal-content-default').fadeIn();
            $('#imageSelectModal').removeClass('modal-wide');
        });

        // Görsel seçilince önizleme göster
        $(document).on('click', '.select-image', function () {
            selectedImagePath = $(this).data('path');
            $('#preview-image').attr('src', selectedImagePath);
            $('.preview-modal').fadeIn();
        });

        // Önizleme modalını kapat
        $('.close-preview').click(function () {
            $('.preview-modal').fadeOut();
        });

        // Resmi seç butonuna tıklandığında
        $('#select-image-btn').click(function () {
            if (selectedImagePath) {
                $('#modal-content-default').show();
                $('#modal-content-gallery').hide();
                $('#imageSelectModal').removeClass('modal-wide');

                if ($('#images').val()) {
                    $('#images').val('');
                }

                $('#selected_image_from_gallery').attr('src', window.location.origin + selectedImagePath);
                $('#selected_image_from_gallery').removeClass('d-none');
                $('#selected_image_from_gallery').css('display', 'block');
                // URL'den dosya adını çıkart
                const fileName = selectedImagePath.split('/').pop();
                $('#pick_from_gallery_image_url').val(fileName);
                $('#pick-image-btn').text('Başka Resim Seç');

                // Modalları kapat
                $('.preview-modal').fadeOut();
                $('#imageSelectModal').modal('hide');
            }
        });

        function loadGalleryModal(params = '') {
            $('#modal-content-default').hide();
            $('#modal-content-gallery').html('<div class="text-center my-5"><div class="spinner-border text-primary"></div></div>').show();

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

                    // 🔙 Geri dön
                    html += `
                <div class="text-center mt-3">
                    <button class="btn btn-secondary btn-sm" id="back-upload-btn">Geri Dön</button>
                </div>`;

                    $('#modal-content-gallery').html(html);
                },
                error: function () {
                    $('#modal-content-gallery').html('<p class="text-danger">Galeriden içerik yüklenemedi.</p>');
                }
            });
        }


    });
</script>