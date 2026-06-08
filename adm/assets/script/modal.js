/* ================================================================
   modal-edit.js
   Modais de edição para Serviços & Preços e Profissionais (Equipe)
   Não altera nenhum código PHP/HTML original — apenas acrescenta
   comportamento via delegação de eventos.
   ================================================================ */

(function () {

    /* ── Fábrica de modal genérico ─────────────────────────── */
    function createOverlay(id) {
        if (document.getElementById(id)) return document.getElementById(id);
        const el = document.createElement('div');
        el.id = id;
        el.className = 'modal-overlay';
        document.body.appendChild(el);
        el.addEventListener('click', function (e) {
            if (e.target === el) closeModal(el);
        });
        return el;
    }

    function openModal(overlay) {
        overlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(overlay) {
        overlay.classList.remove('open');
        document.body.style.overflow = '';
    }

    /* ═══════════════════════════════════════════════════════════
       2. MODAL — EDITAR PROFISSIONAL
       Acionado pelo .edit-icon dentro de .adm-team-card
    ═══════════════════════════════════════════════════════════ */
     const modalTeam = document.getElementById('modal-edit-team');

        document.addEventListener('click', function(e) {
            const editIcon = e.target.closest('.adm-team-card .edit-icon');
            if (!editIcon) return;

            const card = editIcon.closest('.adm-team-card');

            const id = card.getAttribute('data-id');
            const nome = card.getAttribute('data-nome');
            const obs = card.getAttribute('data-obs');

            document.getElementById('edit-barber-id').value = id;
            document.getElementById('edit-barber-nome').value = nome;
            document.getElementById('edit-barber-obs').value = obs;

            modalTeam.style.display = 'flex';
        });

        document.querySelector('.modal-close-btn').addEventListener('click', function() {
            modalTeam.style.display = 'none';
        });

        document.querySelector('.close-modal').addEventListener('click', function() {
            modalTeam.style.display = 'none';
        });

    /* ── Helper: detecta BASE_URL do atributo já presente no DOM ─ */
    function getBaseUrl() {
        const logout = document.querySelector('a.logout[href]');
        if (logout) {
            return logout.href.replace(/\/user\/login\.php.*$/, '');
        }
        return '';
    }

})();