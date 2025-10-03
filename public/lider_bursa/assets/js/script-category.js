
$(document).ready(function(){

    var headline = document.querySelector('#categoryHeadlineCarousel');
    var carousel = new bootstrap.Carousel(headline);

    $(".carousel").swipe({
        
        swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
            console.log("test");
          if (direction == 'left') $(this).carousel('next');
          if (direction == 'right') $(this).carousel('prev');
      
        },
        allowPageScroll:"vertical"
      
    });

});
