<style>

</style>

@php
    // index.blade.php'den gönderilen data özelliklerini JavaScript ile alacağız
    // URL'yi JavaScript'te oluşturacağız
    $text = '';
    // Başlangıçta boş URL, JavaScript ile doldurulacak
    $url = config('app.url');

    $facebookShare = clone Share::page($url, $text);
    $twitterShare = clone Share::page($url, $text);
    $redditShare = clone Share::page($url, $text);
@endphp

<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable ">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-primary">
                <h4 id="modal-title" class="text-center m-2">Paylaş</h4>
                <button type="button" id="close-model" class="btn-close text-white" data-bs-dismiss="modal"
                    aria-label="Kapat"></button>
            </div>
            <hr class="m-0">
            <div class="modal-body my-3">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-3">
                        <a href="#" id="facebook-share-link" target="_blank"
                            class="btn btn-info btn-lg d-flex flex-column align-items-center justify-content-center gap-2">
                            <i data-feather="facebook"></i>
                            <h5 class="card-title">Facebook</h5>
                        </a>
                    </div>
                    <div class="col-12 col-md-3">
                        <a href="#" id="twitter-share-link" target="_blank"
                            class="btn btn-dark btn-lg d-flex flex-column align-items-center justify-content-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24"
                                viewBox="0 0 50 50" style="fill:#FFFFFF;">
                                <path
                                    d="M 5.9199219 6 L 20.582031 27.375 L 6.2304688 44 L 9.4101562 44 L 21.986328 29.421875 L 31.986328 44 L 44 44 L 28.681641 21.669922 L 42.199219 6 L 39.029297 6 L 27.275391 19.617188 L 17.933594 6 L 5.9199219 6 z M 9.7167969 8 L 16.880859 8 L 40.203125 42 L 33.039062 42 L 9.7167969 8 z">
                                </path>
                            </svg>
                            <h5 class="card-title">X</h5>
                        </a>
                    </div>
                    <div class="col-12 col-md-3">
                        <a href="#" id="reddit-share-link" target="_blank"
                            class="btn btn-danger btn-lg d-flex flex-column align-items-center justify-content-center gap-2">
                            <i class="fa fa-reddit" style="font-size: 24px;"></i>
                            <h5 class="card-title">Reddit</h5>
                        </a>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>




<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Modal açıldığında çalışacak
        $('#shareModal').on('show.bs.modal', function (event) {
            // Butonu alalım
            var button = $(event.relatedTarget);

            // Data özelliklerini alalım
            var id = button.data('id');
            var categorySlug = button.data('categoryslug');
            var slug = button.data('slug');

            // Paylaşım URL'sini oluşturalım
            var baseUrl = '{{ config('app.url') }}';
            var fullUrl = baseUrl + '/' + categorySlug + '/' + slug + '/' + id;

            // Paylaşım linklerini güncelleyelim
            // Facebook
            var facebookUrl = '{{ $facebookShare->facebook()->getRawLinks() }}'.replace('{{ $url }}', fullUrl);
            $('#facebook-share-link').attr('href', facebookUrl);

            // Twitter (X)
            var twitterUrl = '{{ $twitterShare->twitter()->getRawLinks() }}'.replace('{{ $url }}', fullUrl);
            $('#twitter-share-link').attr('href', twitterUrl);

            // Reddit
            var redditUrl = '{{ $redditShare->reddit()->getRawLinks() }}'.replace('{{ $url }}', fullUrl);
            $('#reddit-share-link').attr('href', redditUrl);
        });
    });
</script>