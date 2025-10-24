// Selecionar todos os links do menu
const menuLinks = document.querySelectorAll('#sidebar a[data-target]');
const contentSections = document.querySelectorAll('.content-section');

// Adicionar evento de clique a cada link do menu
menuLinks.forEach(link => {
    link.addEventListener('click', function(event) {
        event.preventDefault(); // Evitar comportamento padrão do link

        // Obter o ID do conteúdo associado
        const targetId = this.getAttribute('data-target');

        // Esconder todas as seções de conteúdo
        contentSections.forEach(section => {
            section.style.display = 'none';
        });

        // Exibir apenas a seção de conteúdo correspondente
        const targetSection = document.getElementById(targetId);
        if (targetSection) {
            targetSection.style.display = 'block';
        }

        // Atualizar estado ativo no menu
        menuLinks.forEach(link => link.parentElement.classList.remove('active'));
        this.parentElement.classList.add('active');
    });
});

