document.addEventListener('DOMContentLoaded', function() {
    function initDropdowns() {
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
        dropdownElementList.forEach(function (dropdownToggleEl) {
            new bootstrap.Dropdown(dropdownToggleEl)
        })
    }

    // Initialiser les dropdowns au chargement de la page
    initDropdowns();

    // Si vous utilisez Turbo ou un autre système de navigation côté client,
    // vous pouvez également écouter l'événement de navigation
    if (typeof Turbo !== 'undefined') {
        document.addEventListener("turbo:load", initDropdowns);
    }
});