function initSelectProduct(el) {
    el.select2({
        theme: 'bootstrap-5',
        allowClear: true,
        placeholder: $(el).data('placeholder'),
        ajax: {
            url: "/select-product",
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.nama,
                            id: item.id
                        }
                    })
                };
            }
        }
    });
}

$(document).ready(function() {
    initSelectProduct($('.select-product'));

    $('#add-product-row').on('click', function() {
        const $row = $('.product-row').first().clone();

        $row.find('select').val(null).removeClass('select2-hidden-accessible').removeAttr('data-select2-id');
        $row.find('.select2-container').remove();
        $row.find('input').val('');
        $row.find('.remove-product-row').show();

        $('#product-rows').append($row);
        initSelectProduct($row.find('.select-product'));
    });

    $(document).on('click', '.remove-product-row', function() {
        $(this).closest('.product-row').remove();
    });
});
