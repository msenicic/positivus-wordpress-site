export default ($) => {
    function setVh() {
        let vh = window.innerHeight * 0.01;
        //let vh = $(window).innerHeight() * 0.01;
        $("html").css('--vh', `${vh}px`);
    }

    function toggleHeaderClass() {
        if ($(window).scrollTop() > 0) {
            $('header').addClass('active');
        } else {
            $('header').removeClass('active');
        }
    }    

    toggleHeaderClass();
    setVh();

    $(".js-menu-toggle").click(function () {
        $(".nav").toggleClass("active");
        $("body").toggleClass("overflow");
    });

    $(".nav__overlay").click(function () {
        $(".nav").removeClass("active");
        $("body").removeClass("overflow");
    });

    $(window).resize(function () {
        setVh();
        if ($(window).outerWidth() > 1024) {
            $(".nav").removeClass("active");
            $("body").removeClass("overflow");
        }
    });

    //let lastScroll = 0;

    $(window).scroll(function () {
        toggleHeaderClass();
        //$(".nav").removeClass("active");
        // let height = $("header").outerHeight();
        // let i = $(window).scrollTop();

        // if (lastScroll > height) {
        //     if (i > lastScroll) {
        //         $("header").addClass("hide");
        //     } else {
        //         $("header").removeClass("hide");
        //     }
        // }
        // lastScroll = i;
    });
};