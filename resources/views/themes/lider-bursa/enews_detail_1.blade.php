<!DOCTYPE html>
<html>

<head>

    <link rel="stylesheet" type="text/css" href="/vendor/egazete/css/flipbook.style.css">
    <link rel="stylesheet" type="text/css" href="/vendor/egazete/css/flipbook.skin.black.css">

    <script src="/vendor/egazete/ajax/libs/jquery/1.8.3/jquery.js"></script>
    <script src="/vendor/egazete/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>

    <script src="/vendor/egazete/js/flipbook.min.js"></script>


    <script async="" src="/vendor/egazete/gtag/js?id=UA-174183794-1"></script>

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-174183794-1');
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var book1 = $("#container").flipBook({
                pages: [{
                    src: "https://www.lidergazete.com/uploads/gazete/20250425sayfa1.jpg",
                    thumb: "https://www.lidergazete.com/uploads/gazete/20250425sayfa1.jpg",
                    title: "Anasayfa"
                }, {
                    src: "https://www.lidergazete.com/uploads/gazete/20250425sayfa2.jpg",
                    thumb: "https://www.lidergazete.com/uploads/gazete/20250425sayfa2.jpg",
                    title: "Sayfa 2"
                }, {
                    src: "https://www.lidergazete.com/uploads/gazete/20250425sayfa3.jpg",
                    thumb: "https://www.lidergazete.com/uploads/gazete/20250425sayfa3.jpg",
                    title: "Sayfa 3"
                }, {
                    src: "https://www.lidergazete.com/uploads/gazete/20250425sayfa4.jpg",
                    thumb: "https://www.lidergazete.com/uploads/gazete/20250425sayfa4.jpg",
                    title: "Sayfa 4"
                }, {
                    src: "https://www.lidergazete.com/uploads/gazete/20250425sayfa5.jpg",
                    thumb: "https://www.lidergazete.com/uploads/gazete/20250425sayfa5.jpg",
                    title: "Sayfa 5"
                }, {
                    src: "https://www.lidergazete.com/uploads/gazete/20250425sayfa6.jpg",
                    thumb: "https://www.lidergazete.com/uploads/gazete/20250425sayfa6.jpg",
                    title: "Sayfa 6"
                }, {
                    src: "https://www.lidergazete.com/uploads/gazete/20250425sayfa7.jpg",
                    thumb: "https://www.lidergazete.com/uploads/gazete/20250425sayfa7.jpg",
                    title: "Sayfa 7"
                }, {
                    src: "https://www.lidergazete.com/uploads/gazete/20250425sayfa8.jpg",
                    thumb: "https://www.lidergazete.com/uploads/gazete/20250425sayfa8.jpg",
                    title: "Sayfa 8"
                }],

                //menu
                btnNext: true,
                btnPrev: true,
                btnZoomIn: true,
                btnZoomOut: true,
                btnToc: false,
                btnThumbs: false,
                btnShare: false,
                btnExpand: false,

                startPage: 0,

                pageWidth: 1000,
                pageHeight: 1694,
                thumbnailWidth: 100,
                thumbnailHeight: 169,

                flipType: '3d',

                time1: 500,
                time2: 600

            });

        })
    </script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/turn.js/3/turn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script></head>

<body>




    <div id="container"></div>

    </body>
    </html>
