jQuery(document).ready(function ($) {
    $('body').on('click', '.sol-borrar', function (event) {
        event.preventDefault();
        var $enlace = $(this);
        var $filaEnlace = $enlace.parents('tr');
        $.post(ajax_object.ajax_url,
            {
                action: 'mix_solicitud_borrar',
                nonce: ajax_object.ajax_nonce,
                solicitud_id: $enlace.data('solicitud_id')
            },
            function (response) {
                $filaEnlace.remove();
            });
        return false;
    });
});