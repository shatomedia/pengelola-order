$(document).ready(function() {
    const selectListProducts = $('#select-list-products');

    selectListProducts.select2({
        theme: 'bootstrap-5',
        allowClear: true,
        ajax: {
            url: "/list-products",
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
});
