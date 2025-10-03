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
<div class="modal fade" id="youtubeModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-primary">
                <h4 class="text-center m-2">Youtube Embed Ekle</h4>
                <button type="button" id="close-model" class="btn-close text-white" data-bs-dismiss="modal"
                    aria-label="Kapat"></button>
            </div>
            <hr class="mt-0">
            <div class="modal-body" id="modalBody">
                <div class="row">
                    <div class="col-12 col-lg-12 col-xl-12">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a href="#bytitle" class="nav-link active" id="tab-bytitle" data-bs-toggle="tab">Başlığa
                                    Göre
                                    Videolar</a>
                            </li>
                            <li class="nav-item">
                                <a href="#myvideo" class="nav-link " id="tab-myvideo" data-bs-toggle="tab">Kendi
                                    Videolarım</a>
                            </li>

                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="bytitle">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <h2 class="mb-4">Başlığa Göre</h2>
                                            <div class="row my-2">
                                                <div class="col-9">
                                                    <input type="text" class="form-control"
                                                        placeholder="Video Ara..." id="search-title">
                                                </div>
                                                <div class="col-3 d-flex align-items-center">
                                                    <button class="btn btn-success btn-sm px-4"
                                                        id="search-btn">Ara</button>
                                                </div>
                                            </div>
                                            <div id="videobytitle"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane" id="myvideo">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <h2 class="mb-4">Kendi Videolarım</h2>
                                            <div id="videomyvideo"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>



                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-secondary" id="close-model" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let searchTitle;

        function loadData(url, containerId) {
            $.ajax({
                url: url,
                method: "GET",
                beforeSend: function() {
                    $("#" + containerId).html(`
                    <div class="row mb-3 p-3 border rounded">
                        <div class="col-md-4">
                            <div class="bg-secondary rounded"  style="height: 120px; width: 100%;"></div>
                        
                        </div>
                        <div class="col-md-6">
                            <h5>Yükleniyor...</h5>
                            <p><small class="text-muted">Yükleniyor...</small></p>
                            <p>Yükleniyor...</p>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <button class="btn btn-primary w-100  text-dark" disabled>Ekle</button>
                        </div>
                    </div>
                    <div class="row mb-3 p-3 border rounded">
                        <div class="col-md-4">
                            <div class="bg-secondary rounded"  style="height: 120px; width: 100%;"></div>
                        
                        </div>
                        <div class="col-md-6">
                            <h5>Yükleniyor...</h5>
                            <p><small class="text-muted">Yükleniyor...</small></p>
                            <p>Yükleniyor...</p>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <button class="btn btn-primary w-100  text-dark" disabled>Ekle</button>
                        </div>
                    </div>
                    <div class="row mb-3 p-3 border rounded">
                        <div class="col-md-4">
                            <div class="bg-secondary rounded"  style="height: 120px; width: 100%;"></div>
                        
                        </div>
                        <div class="col-md-6">
                            <h5>Yükleniyor...</h5>
                            <p><small class="text-muted">Yükleniyor...</small></p>
                            <p>Yükleniyor...</p>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <button class="btn btn-primary w-100  text-dark" disabled>Ekle</button>
                        </div>
                    </div>
                    `);
                },
                success: function(data) {
                    console.log("data: ", data);
                    renderVideos(data, containerId);
                },
                error: function(xhr, status, error) {
                    let errorMessage = xhr.responseJSON?.error || xhr.statusText;
                 
                    if (errorMessage === "Undefined property: InvalidArgumentException::$search") {
                        var googleConnectUrl = "{{ route('google.connect') }}";
                        $("#modalBody").html(`<p class="text-danger text-center d-flex align-items-center justify-content-center" style="height: 400px">   <a href="${googleConnectUrl}" type="button"
                                       class="btn btn-primary  mx-xl-3  d-flex align-items-center" >
                                        <i data-feather="log-in" class="me-1"></i>
                                        Google Hesabını Bağla
                                    </a></p>`);

                    } else {
                        let errorJSON = JSON.parse(errorMessage)?.error
                        if (errorJSON?.status === "UNAUTHENTICATED") {
                            var googleConnectUrl = "{{ route('google.connect') }}";
                            $("#modalBody").html(`<p class="text-danger text-center d-flex align-items-center justify-content-center" style="height: 400px">   <a href="${googleConnectUrl}" type="button"
                                       class="btn btn-primary  mx-xl-3  d-flex align-items-center" >
                                        <i data-feather="log-in" class="me-1"></i>
                                        Google Hesabını Bağla
                                    </a></p>`);

                        }
                    }

                }
            });
        }

        function renderVideos(videos, id) {

            if (videos.length > 0) {
                const videoList = document.querySelector(`#${id}`);
                videoList.innerHTML = "";

                videos.forEach(video => {
                    const videoItem = document.createElement("div");
                    videoItem.classList.add("row", "mb-3", "p-3", "border", "rounded");
                    videoItem.innerHTML = `
                        <div class="col-md-4">
                            <img src="${video.image}" class="img-fluid rounded" alt="${video.title}" style="height: 120px; object-fit: cover; width: 100%;">
                        </div>
                        <div class="col-md-6">
                            <h5>${video.title}</h5>
                            <p><small class="text-muted">${new Date(video.publishedAt).toLocaleDateString()}</small></p>
                            <p>${video.description.substring(0, 100)}...</p>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <button class="btn btn-primary w-100" data-url="${video.embedUrl}" data-title="${video.title}" id="video-btn">Ekle</button>
                        </div>
                    `;
                    videoList.appendChild(videoItem);
                });
            } else {
                const videoList = document.querySelector(`#${id}`);
                videoList.innerHTML = "";
                const videoItem = document.createElement("div");
                videoItem.classList.add("row", "mb-3", "p-3", "border", "rounded");
                videoItem.innerHTML = `
                        <div class="col-md-12 text-center">
                           Video Ara!
                        </div>
                        
                    `;
                videoList.appendChild(videoItem);
            }

        }

        // Modal açıldığında ilk sekmeyi yükle
        $('#youtubeModal').on('shown.bs.modal', function() {
            let title = $("#title").val()
            $("#search-title").val(title)
            loadData("/secure/youtube/get-video-by-title?title=" + encodeURIComponent(title),
                "videobytitle");
        });

        $("#search-btn").click(function() {
            searchTitle = $("#search-title").val();
            console.log("/secure/youtube/get-video-by-title?title=" + encodeURIComponent(searchTitle));
            loadData("/secure/youtube/get-video-by-title?title=" + encodeURIComponent(searchTitle),
                "videobytitle");
        })

        // Sekmeler arasında geçiş
        $("#tab-bytitle").click(function() {
            let title = $("#title").val()
            $("#search-title").val(title)
            $("#tab-bytitle").addClass("active");
            $("#tab-myvideo").removeClass("active");
            $("#bytitle").addClass("show active");
            $("#myvideo").removeClass("show active");
            loadData("/secure/youtube/get-video-by-title?title=" + encodeURIComponent(title),
                "videobytitle");
        });

        $("#tab-myvideo").click(function() {

            $("#tab-myvideo").addClass("active");
            $("#tab-bytitle").removeClass("active");
            $("#myvideo").addClass("show active");
            $("#bytitle").removeClass("show active");
            loadData("/secure/youtube/get-user-video", "videomyvideo");
        });

        document.addEventListener("click", function(event) {
            if (event.target && event.target.id === "video-btn") {
                const embedUrl = event.target.getAttribute("data-url");
                const title = event.target.getAttribute("data-title");
                const videoEmbedTextarea = document.querySelector("#video_embed");

                if (videoEmbedTextarea) {
                    videoEmbedTextarea.value = `<iframe width="866" height="487" src="${embedUrl}" title="${title}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>`;
                }

                $("#youtubeModal").modal("hide");
            }
        });
    });
</script>
