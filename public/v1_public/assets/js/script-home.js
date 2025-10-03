$(document).ready(function(){
    if($("#topheadline").length>0){
        hscroll('#topheadline');
    }
    if($("#authors").length>0){
        hscroll('#authors');
    }

    $('#lastminuteSlider').slick({
        dots: false,
        infinite: false,
        speed: 300,
        nextArrow: '#lastminuteNext',
		prevArrow: '#lastminutePrev',
		centerMode: false,
		swipeToSlide: true
    });

    var headline = document.querySelector('#headlineCarousel');
    var carousel = new bootstrap.Carousel(headline, {
        interval: 5000,
        touch: true,
        pause: 'hover',
        ride: true
    });

    var featured = document.querySelector('#featuredCarousel');
    var carousel = new bootstrap.Carousel(featured, {
        interval: 5000,
        touch: true,
        pause: 'hover',
        ride: true
    });

    $(".carousel").swipe({

        swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
            console.log("test");
          if (direction == 'left') $(this).carousel('next');
          if (direction == 'right') $(this).carousel('prev');

        },
        allowPageScroll:"vertical"

    });
});
