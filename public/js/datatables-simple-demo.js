window.addEventListener('DOMContentLoaded', () => {
    const datatablesSimple = document.getElementById('datatablesSimple');

    if (!datatablesSimple) {
        return;
    }

    new simpleDatatables.DataTable(datatablesSimple, {
        perPage: 10,
        perPageSelect: [5, 10, 25, 50],
        labels: {
            placeholder: 'Buscar...',
            perPage: '{select} registros por página',
            noRows: 'No hay registros',
            noResults: 'No se encontraron resultados',
            info: 'Mostrando {start} a {end} de {rows} registros'
        }
    });
});
