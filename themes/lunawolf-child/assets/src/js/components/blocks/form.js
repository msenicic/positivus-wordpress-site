export default ($) => {
    $('input[name="form-toggle"]').on('change', function() {
        $('.contact__form').removeClass('-active');
        $('#' + $(this).val()).addClass('-active');
    });

    $('header .btn a').on('click', function() {
        console.log("radi")
        $('#radio-2').prop('checked', true);
        $('.contact__form').removeClass('-active');
        $('#form2').addClass('-active');
    });
}