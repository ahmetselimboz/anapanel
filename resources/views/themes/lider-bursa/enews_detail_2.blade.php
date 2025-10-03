<!doctype html>
<html>

<head>
    <link rel="basic.css"></link>
    <link type="text/css" rel="stylesheet" href="default.css">
    <link type="text/css" rel="stylesheet" href="basic.css">
    <link type="text/javascript" src="basic.js">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/turn.js/3/turn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>

    <style type="text/css">
        body {
            background: #ccc;
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: url('/vendor/egazete/images/woods_image.png') ;
        }

        #book {
            width: 960px;
            height: 750px;
            margin: 0 auto;
        }

        #book .turn-page {
            background-color: white;
        }

        #book .page {
            width: 100%;
            height: 100%;
            overflow: hidden;
            position: relative;
        }

        #book .page img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            margin: 0;
            padding: 0;
        }

        #controls {
            /* width: 800px; */
            text-align: center;
            margin: 20px auto;
            font: 18px arial;
        }

        #controls input,
        #controls label , #number-pages {
            font: 18px arial;
            margin: 0 5px;

        }
        #controls label , #number-pages {
            font: 18px arial;
            margin: 0 5px;
            color: white;

        }

        #book .odd {
            background-image: -webkit-linear-gradient(left, #FFF 95%, #ddd 100%);
            background-image: -moz-linear-gradient(left, #FFF 95%, #ddd 100%);
            background-image: -o-linear-gradient(left, #FFF 95%, #ddd 100%);
            background-image: -ms-linear-gradient(left, #FFF 95%, #ddd 100%);
            width: 470px;
        }

        #book .even {
            background-image: -webkit-linear-gradient(right, #FFF 95%, #ddd 100%);
            background-image: -moz-linear-gradient(right, #FFF 95%, #ddd 100%);
            background-image: -o-linear-gradient(right, #FFF 95%, #ddd 100%);
            background-image: -ms-linear-gradient(right, #FFF 95%, #ddd 100%);
            width: 470px;
        }

        /* Modal styles for zoom view */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 50px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
        }

        .modal-content {
            margin: auto;
            display: block;
            width: auto;
            height: auto;
            max-width: 90%;
            max-height: 90%;
            transition: transform 0.3s ease;
        }

        .modal-content {
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @keyframes zoom {
            from {
                transform: scale(0.1)
            }
            to {
                transform: scale(1)
            }
        }

        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        .zoom-controls {
            position: fixed;
            bottom: 20px;
            left: 0;
            width: 100%;
            text-align: center;
            z-index: 1001;
        }

        .zoom-button {
            background-color: rgba(223 30 36);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 24px;
            margin: 0 10px;
            cursor: pointer;
            transition: background-color 0.3s;
            background: rgba(223 30 36);

        }

        .zoom-button:hover {
            background-color: rgb(253 253 253);
            color: rgba(223 30 36);
        }

        .book-controls {
            position: relative;
            margin-top: 20px;
            text-align: center;
            width: 100%;
            background: rgba(223 30 36);
        }

        .book-controls button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 12px 24px;
            margin: 0 8px 10px 8px;
            cursor: pointer;
            border-radius: 30px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
        }

        .book-controls button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 5px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>

    <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="expandedImg">
        <div class="zoom-controls">
            <button class="zoom-button" id="zoom-in">+</button>
            <button class="zoom-button" id="zoom-out">-</button>
        </div>
    </div>

    <div id="book"></div>

    <div class="book-controls" id="controls">
        <button id="prev-btn">◀ Önceki</button>
        <label for="page-number">Sayfa:</label> <input class="form-control" type="text" size="3" id="page-number"> / <span id="number-pages"></span>
        <button id="next-btn">Sonraki ▶</button>
    </div>

    <script type="text/javascript">
        // İlk olarak tüm resim URL'lerini bir diziye alıyoruz
        var imageUrls = [];
        const newsImages = @json($news->getImages);
        newsImages.forEach(function(image) {
            imageUrls.push("/uploads/"+image.images);
            // debugger;
        });

        var numberOfPages = imageUrls.length;
        var zoomEnabled = true;
        var currentZoom = 1;

        function addPage(page, book) {
            if (!book.turn('hasPage', page)) {
                var element = $('<div />', {
                    'class': 'page ' + ((page % 2 == 0) ? 'even' : 'odd'),
                    'id': 'page-' + page
                }).html('<div class="page ">Yükleniyor...</div>');

                book.turn('addPage', element, page);

                setTimeout(function() {
                    if (page > 0 && page <= numberOfPages) {
                        var imageUrl = imageUrls[page - 1];
                        var imageHtml = '<div class="page"><img src="' + imageUrl + '" alt="Sayfa ' + page + '"></div>';
                        element.html(imageHtml);

                        element.find('img').on('click', function() {
                            if (zoomEnabled) {
                                var imgSrc = $(this).attr('src');
                                var modal = document.getElementById("imageModal");
                                var modalImg = document.getElementById("expandedImg");
                                modal.style.display = "block";
                                modalImg.src = imgSrc;
                                currentZoom = 1;
                                modalImg.style.transform = 'scale(' + currentZoom + ')';
                            }
                        });
                    } else if (page > numberOfPages) {
                        element.html('<div class="page"><p>Sayfa Sonu</p></div>');
                    }
                }, 1000);
            }
        }

        $(window).ready(function() {
            const isMobile = window.innerWidth <= 666;

            $('#book').turn({
                width: isMobile ? 470 : 940,
                height: 750,
                autoCenter: true,
                acceleration: true,
                pages: numberOfPages,
                elevation: 50,
                display: isMobile ? 'single' : 'double',
                duration: 1000,
                gradients: !$.isTouch,
                when: {
                    turning: function(e, page, view) {
                        var range = $(this).turn('range', page);

                        for (page = range[0]; page <= range[1]; page++)
                            addPage(page, $(this));

                        zoomEnabled = false;
                    },

                    turned: function(e, page) {
                        $('#page-number').val(page);

                        setTimeout(function() {
                            zoomEnabled = true;
                        }, 100);
                    }
                }
            });

            $('#number-pages').html(numberOfPages);

            $('#page-number').keydown(function(e) {
                if (e.keyCode == 13)
                    $('#book').turn('page', $('#page-number').val());
            });

            $('#zoom-in').click(function() {
                if (currentZoom < 3) {
                    currentZoom += 0.5;
                    $('#expandedImg').css('transform', 'scale(' + currentZoom + ')');
                }
            });

            $('#zoom-out').click(function() {
                if (currentZoom > 0.5) {
                    currentZoom -= 0.5;
                    $('#expandedImg').css('transform', 'scale(' + currentZoom + ')');
                }
            });

            $('#prev-btn').click(function() {
                $('#book').turn('previous');
            });

            $('#next-btn').click(function() {
                $('#book').turn('next');
            });

            $('.close').on('click', function() {
                document.getElementById('imageModal').style.display = "none";
            });

            $('#expandedImg').on('click', function(e) {
                var rect = e.target.getBoundingClientRect();
                var x = e.clientX - rect.left;
                var y = e.clientY - rect.top;
                $(this).css('transform-origin', x + 'px ' + y + 'px');
            });
        });

        $(window).bind('keydown', function(e) {
            if (e.target && e.target.tagName.toLowerCase() != 'input') {
                if (e.keyCode == 37 && zoomEnabled) {
                    zoomEnabled = false;
                    $('#book').turn('previous');
                    setTimeout(function() {
                        zoomEnabled = true;
                    }, 100);
                } else if (e.keyCode == 39 && zoomEnabled) {
                    zoomEnabled = false;
                    $('#book').turn('next');
                    setTimeout(function() {
                        zoomEnabled = true;
                    }, 100);
                } else if (e.keyCode == 27 && document.getElementById('imageModal').style.display == "block") {
                    document.getElementById('imageModal').style.display = "none";
                }
            }
        });

        $(window).resize(function() {
            const isMobile = window.innerWidth <= 666;
            setTimeout(function() {
                $('#book').turn('size', isMobile ? 470 : 940, 750);
                $('#book').turn('display', isMobile ? 'single' : 'double');
                $('#book').turn('resize');
            }, 100);
        });
    </script>

</body>

</html>
