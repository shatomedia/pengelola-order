$('.btn-delete-confirm').click(function(event) {
    const form = $(this).closest("form");
    event.preventDefault();
    Swal.fire({
        title: `Peringatan`,
        text: "Apakah Anda yakin akan menghapus data ini?",
        icon: "warning",
        reverseButtons: true,
        showCancelButton: true,
        cancelButtonColor: '#d33',
        cancelButtonText: 'TIDAK',
        confirmButtonText: 'YA, HAPUS!',
        dangerMode: true,
    }).then(function(e) {
        if (e.value === true) {
            form.submit();
        }else {
            e.dismiss;
        }
    },function (dismiss) {
        return false;
    });
});
