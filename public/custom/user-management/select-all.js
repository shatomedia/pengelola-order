document.addEventListener("DOMContentLoaded", function () {
    const roleModals = document.querySelectorAll('[id^="modalEdit"]');

    roleModals.forEach(function (modal) {
        const roleId = modal.id.replace("modalEdit", "");
        const selectAllCheckbox = modal.querySelector("#selectAll-" + roleId);
        const permissionCheckboxes = modal.querySelectorAll(
            'input[name="permission[]"]'
        );
        const selectAllLabel = modal.querySelector(
            'label[for="selectAll-' + roleId + '"]'
        );

        selectAllLabel.addEventListener("click", function () {
            selectAllCheckbox.checked = !selectAllCheckbox.checked;
            const isChecked = selectAllCheckbox.checked;
            permissionCheckboxes.forEach(function (checkbox) {
                checkbox.checked = isChecked;
            });
        });

        selectAllCheckbox.addEventListener("change", function () {
            const isChecked = this.checked;
            permissionCheckboxes.forEach(function (checkbox) {
                checkbox.checked = isChecked;
            });
        });

        permissionCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener("change", function () {
                const allPermissionsChecked = Array.from(
                    permissionCheckboxes
                ).every(function (checkbox) {
                    return checkbox.checked;
                });

                selectAllCheckbox.checked = allPermissionsChecked;
            });
        });
    });
});
