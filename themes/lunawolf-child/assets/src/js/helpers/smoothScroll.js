export default ($) => {
    (() => {
        $('a[href*="#"]:not(.js-disable)').click(function () {
            $(".nav").removeClass("active");
            $("body").removeClass("overflow");
            let target = $(this.hash);
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - $("header").outerHeight() - 20
                    }, 1000);
                    return false;
                }
            }
        });
    })();
}