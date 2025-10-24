// Abertura do modal de filtro
document.getElementById('filter-icon').addEventListener('click', function () {
    document.getElementById('filter-modal').style.display = 'flex';
});

// Fechar o modal ao clicar no 'x'
document.getElementById('close-modal').addEventListener('click', function () {
    document.getElementById('filter-modal').style.display = 'none';
});

// Fechar o modal quando clicar fora da área do conteúdo
window.addEventListener('click', function (e) {
    if (e.target == document.getElementById('filter-modal')) {
        document.getElementById('filter-modal').style.display = 'none';
    }
});

// Aplicar o filtro na tabela
document.getElementById('apply-filter').addEventListener('click', function () {
    // Pega os valores do filtro
    let filterDate = document.getElementById('filter-date').value;
    let filterService = document.getElementById('filter-service').value.toLowerCase();

    let rows = document.querySelectorAll('#client-table-body tr');

    rows.forEach(row => {
        let date = row.cells[1].textContent.trim();
        let service = row.cells[2].textContent.toLowerCase().trim();

        let dateMatch = filterDate ? date === filterDate : true;
        let serviceMatch = filterService ? service.includes(filterService) : true;

        // Mostra ou esconde a linha com base nos filtros
        if (dateMatch && serviceMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    // Fecha o modal após aplicar o filtro
    document.getElementById('filter-modal').style.display = 'none';
});

