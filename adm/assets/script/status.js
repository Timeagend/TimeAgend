(function () {

    const LABELS = {
        pendente:   'pendente',
        confirmado: 'confirmado',
        cancelado:  'cancelado',
    };

    document.addEventListener('click', function (e) {

        // Fecha dropdowns ao clicar fora
        document.querySelectorAll('.status-dropdown.open').forEach(function (d) {
            if (!d.closest('.status-wrapper').contains(e.target)) {
                d.classList.remove('open');
            }
        });

        // Abre/fecha ao clicar no badge
        const badge = e.target.closest('.status-wrapper .status');
        if (badge) {
            badge.nextElementSibling.classList.toggle('open');
            return;
        }

        // Seleciona opção
        const option = e.target.closest('.status-option');
        if (!option) return;

        const wrapper   = option.closest('.status-wrapper');
        const badge2    = wrapper.querySelector('.status');
        const newStatus = option.dataset.value;
        const oldStatus = badge2.dataset.status;
        const id        = badge2.dataset.id;

        if (newStatus === oldStatus) {
            option.closest('.status-dropdown').classList.remove('open');
            return;
        }

        // ── Update otimista ──
        badge2.classList.remove(oldStatus);
        badge2.classList.add(newStatus);
        badge2.dataset.status = newStatus;
        badge2.textContent    = LABELS[newStatus] || newStatus;
        option.closest('.status-dropdown').classList.remove('open');

        // ── Salva no banco ──
        wrapper.classList.add('status-saving');

        fetch('services/changeStatus.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body:    new URLSearchParams({ id: id, status: newStatus }).toString()
        })
        .then(function (res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(function (data) {
            if (!data.success) throw new Error(data.message);
        })
        .catch(function (err) {
            badge2.classList.remove(newStatus);
            badge2.classList.add(oldStatus);
            badge2.dataset.status = oldStatus;
            badge2.textContent    = LABELS[oldStatus] || oldStatus;
            console.error('Erro ao atualizar status:', err.message);
        })
        .finally(function () {
            wrapper.classList.remove('status-saving');
        });
    });

})();