$(document).ready(function(){

    hscroll('#headerCategories');
    hscroll('#headerCategories2');

    $("img.lazy").lazyload();

    $('#currencySlider').slick({
        dots: false,
        infinite: true,
        speed: 300,
        variableWidth: true,
        nextArrow: '#currencyNext',
		prevArrow: '#currencyPrev',
		centerMode: false,
		swipeToSlide: true
    });

});

function copyToClipboard(selector){
    var textToCopy = $(selector).text();
    var tempTextarea = $('<textarea>');
    $('body').append(tempTextarea);
    tempTextarea.val(textToCopy).select();
    document.execCommand('copy');
    tempTextarea.remove();
  }

$("#search-close").click(function(){
    $("body").removeClass("searching");
});

$("#searching-btn").click(function(){
    $("body").addClass("searching");
    $("#search").focus();
});

$("#burger-menu-contain .before-overlay, #menu-close").click(function(){
    $("body").removeClass("burger-menu");
});

$("#menu-btn").click(function(){
    $("body").addClass("burger-menu");
});

$(".comment-reply-show").click(function(){
    $(this).parent().toggleClass("active");
    return false;
});

$(".comment-reply a").click(function(){
    var u = $(this).data("user");
    $("#commentMessage").val(u+' ');
    $("#commentReply").html('<i class="icon-close2 d-inline-block"></i> <div class="reply-comment-user">'+u+'</div> cevap veriliyor.');
    $("#commentMessage").focus();

    $("#commentReply .icon-close2").click(function(){
        $("#commentMessage").val("");
        $("#commentReply").html("");
        return false;
    });
    return false;
});

$("#copyDetail").click(function(){
    copyToClipboard("#detailContent");
    return false;
});

$(".playout-button").click(function(){
    $(this).parent().toggleClass("active");
    return false;
});

$("#signIn").click(function(){
    $("body").removeClass("sign-in").removeClass("sign-up");
    $("body").addClass("sign-in");
});

$("#signInBtn").click(function(){
    $("body").removeClass("sign-in").removeClass("sign-up");
    $("body").addClass("sign-in");
});

$("#signUpBtn").click(function(){
    $("body").removeClass("sign-in").removeClass("sign-up");
    $("body").addClass("sign-up");
});

$(".sign-close-button").click(function(){
    $("body").removeClass("sign-in").removeClass("sign-up");
})

function hscroll(cls){
    let sldr = document.querySelector(cls);
    let mouseDown = false;
    let startX, scrollLeft;

    let startDragging = function (e) {
    mouseDown = true;
    startX = e.pageX - sldr.offsetLeft;
    scrollLeft = sldr.scrollLeft;
    };
    let stopDragging = function (event) {
    mouseDown = false;
    };

    sldr.addEventListener('mousemove', (e) => {
        e.preventDefault();
        if(!mouseDown) { return; }
        const x = e.pageX - sldr.offsetLeft;
        const scroll = x - startX;
        sldr.scrollLeft = scrollLeft - scroll;
    });

    // Add the event listeners
    sldr.addEventListener('mousedown', startDragging, false);
    sldr.addEventListener('mouseup', stopDragging, false);
    sldr.addEventListener('mouseleave', stopDragging, false);

}


$(".carousel-indicators button").hover(function(){
    var goto = Number( $(this).attr('data-bs-slide-to') );
    $(this).parent().parent().carousel(goto);
});
