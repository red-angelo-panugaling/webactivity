window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        const options = datatablesSimple.dataset.perPage
            ? { perPage: Number(datatablesSimple.dataset.perPage), perPageSelect: [5, 10, 15, 20, 25] }
            : {};

        new simpleDatatables.DataTable(datatablesSimple, options);
    }
});
