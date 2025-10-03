$(document).ready(function(){
    if($("#topheadline").length>0){
        hscroll('#topheadline');
    }
    if($("#authorss").length>0){
        hscroll('#authorss');
    }

	$('#yenilistele').slick({
			slidesPerRow: 1,
			slidesToShow: 1,
			slidesToScroll: 1,
			autoplay: true,
			autoplaySpeed: 4000,
			dots: false,
			infinite: true,
			speed: 300,
			nextArrow: '#yenilisteleNext',
			prevArrow: '#yenilistelePrev',
			centerMode: false,
			swipeToSlide: true
	});
	
	$('#dortlukayan').slick({
			slidesPerRow: 1,
			slidesToShow: 1,
			slidesToScroll: 1,
			autoplay: true,
			autoplaySpeed: 4000,
			dots: false,
			infinite: true,
			speed: 300,
			nextArrow: '#dortlukayanNext',
			prevArrow: '#dortlukayanPrev',
			centerMode: false,
			swipeToSlide: true
	});

	if (window.matchMedia("(max-width: 768px)").matches) {
		
		$('#authors').slick({
			slidesPerRow: 2,
			slidesToShow: 2,
			slidesToScroll: 1,
			autoplay: true,
			autoplaySpeed: 4000,
			dots: false,
			infinite: true,
			speed: 300,
			nextArrow: '#authorNext',
			prevArrow: '#authorPrev',
			centerMode: false,
			swipeToSlide: true
		});
		
	} else {
		
		$('#authors').slick({
				slidesPerRow: 3,
				slidesToShow: 3,
				slidesToScroll: 1,
				autoplay: true,
				autoplaySpeed: 4000,
				dots: false,
				infinite: true,
				speed: 300,
				nextArrow: '#authorNext',
				prevArrow: '#authorPrev',
				centerMode: false,
				swipeToSlide: true
		});
		
	}



    $('#lastminuteSlider').slick({
		autoplay: true,
		autoplaySpeed: 4000,
        dots: false,
        infinite: false,
        speed: 300,
        nextArrow: '#lastminuteNext',
		prevArrow: '#lastminutePrev',
		centerMode: false,
		swipeToSlide: true
    });

    $("#homenewslider").slick({
        dots: true,
        arrows: false,
        infinite: false,
        swipeToSlide: true,
        speed: 560,
        autoplay: false,
    });

    $("#homenewslider2").slick({
        dots: true,
        arrows: false,
        infinite: false,
        swipeToSlide: true,
        speed: 560,
        autoplay: false,
    });

    var headline = document.querySelector('#headlineCarousel');
    var carousel = new bootstrap.Carousel(headline, {
        interval: 0,
        touch: true,
        pause: 'hover',
        ride: false
    });

	var headline = document.querySelector('#headlineMobileCarousel');
    var carousel = new bootstrap.Carousel(headline, {
        interval: 0,
        touch: true,
        pause: 'hover',
        ride: false
    });

    var headline2 = document.querySelector('#headlineCarousel2');
    var carousel2= new bootstrap.Carousel(headline2, {
        interval: 0,
        touch: true,
        pause: 'hover',
        ride: false
    });

    var featured = document.querySelector('#featuredCarousel');
    var carousel = new bootstrap.Carousel(featured, {
        interval: 0,
        touch: true,
        pause: 'hover',
        ride: false
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
