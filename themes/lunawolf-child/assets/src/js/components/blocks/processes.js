export default ($) => {
    $('.js-process-toggle').on('click', function() {
        var $process = $(this).closest('.processes__process');
        $process.toggleClass('active');
    });
}