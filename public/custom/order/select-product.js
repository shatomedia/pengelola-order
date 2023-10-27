$(document).ready(function (){
    const selectProduct = $('#select-product');

    selectProduct.select2({
        theme: 'bootstrap-5',
        allowClear: true,
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
});
