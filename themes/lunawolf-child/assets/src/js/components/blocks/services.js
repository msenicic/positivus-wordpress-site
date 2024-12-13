export default ($) => {
    function updateColor() {
        $('.services__service__inner').each(function() {
            const service = $(this);
            const isBig = service.outerWidth() > 500;
            service.toggleClass('big', isBig);
            service.toggleClass('low', !isBig);
        });    
    }

    updateColor();
    $(window).resize(updateColor);
}