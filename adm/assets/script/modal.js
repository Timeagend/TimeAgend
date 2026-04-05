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
       1. MODAL — EDITAR SERVIÇO
       Acionado pelo .edit-icon dentro dos cards de serviço
       (cards que NÃO são .adm-team-card)
    ═══════════════════════════════════════════════════════════ */
    const serviceOverlay = createOverlay('modal-edit-service');
    serviceOverlay.innerHTML = `
        <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modal-service-title">
            <div class="modal-header">
                <h3 id="modal-service-title">
                    <i class='bx bx-edit'></i> Editar Serviço
                </h3>
                <button class="modal-close-btn" aria-label="Fechar">&times;</button>
            </div>
            <form id="form-edit-service"
                  action="${getBaseUrl()}/adm/services/editService.php"
                  method="POST">
                <input type="hidden" name="service-id" id="edit-service-id">
                <div class="modal-form-grid">
                    <div class="full">
                        <label>Nome do serviço</label>
                        <input type="text" name="service-name" id="edit-service-name"
                               placeholder="Ex: Hidratação" required>
                    </div>
                    <div>
                        <label>Tipo</label>
                        <input type="text" name="service-tipo" id="edit-service-tipo"
                               placeholder="Ex: Cabelo" required>
                    </div>
                    <div>
                        <label>Duração</label>
                        <input type="text" name="service-duracao" id="edit-service-duracao"
                               placeholder="Ex: 30 min" required>
                    </div>
                    <div>
                        <label>Valor (R$)</label>
                        <input type="number" name="service-valor" id="edit-service-valor"
                               placeholder="0,00" step="0.01" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-btn-cancel">Cancelar</button>
                    <button type="submit" class="modal-btn-save">
                        <i class='bx bx-save'></i> Salvar
                    </button>
                </div>
            </form>
        </div>`;

    /* Fechar pelo × e pelo Cancelar */
    serviceOverlay.querySelector('.modal-close-btn').addEventListener('click', () => closeModal(serviceOverlay));
    serviceOverlay.querySelector('.modal-btn-cancel').addEventListener('click', () => closeModal(serviceOverlay));

    /* Delegação: clique em .edit-icon dentro de um card de serviço */
    document.addEventListener('click', function (e) {
        const editIcon = e.target.closest('#meu-site-content .category .edit-icon');
        if (!editIcon) return;

        const card = editIcon.closest('.barber-card');
        if (!card) return;

        /* Lê os dados do card renderizado pelo PHP */
        const nome     = card.querySelector('strong')?.textContent.trim() ?? '';
        const tipo     = card.querySelector('.adm-chip-badge')?.textContent.trim() ?? '';
        const duracao  = card.querySelector('.adm-chip-meta')?.textContent.replace(/\s+/g, ' ').trim().replace(/^[^\s]+\s/, '') ?? '';
        const precoRaw = card.querySelector('.adm-chip-price')?.textContent.trim() ?? '';
        /* "R$ 1.200,50" → "1200.50" para o input number */
        const preco    = precoRaw.replace('R$', '').trim().replace(/\./g, '').replace(',', '.');

        /* Preenche o formulário */
        document.getElementById('edit-service-name').value    = nome;
        document.getElementById('edit-service-tipo').value    = tipo;
        document.getElementById('edit-service-duracao').value = duracao;
        document.getElementById('edit-service-valor').value   = preco;
        /* Se o seu backend usa ID, adicione data-id="<?= $s['id'] ?>" no .barber-card */
        const id = card.dataset.id ?? '';
        document.getElementById('edit-service-id').value = id;

        openModal(serviceOverlay);
    });

    /* ═══════════════════════════════════════════════════════════
       2. MODAL — EDITAR PROFISSIONAL
       Acionado pelo .edit-icon dentro de .adm-team-card
    ═══════════════════════════════════════════════════════════ */
    const teamOverlay = createOverlay('modal-edit-team');
    teamOverlay.innerHTML = `
        <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modal-team-title">
            <div class="modal-header">
                <h3 id="modal-team-title">
                    <i class='bx bx-user-circle'></i> Editar Profissional
                </h3>
                <button class="modal-close-btn" aria-label="Fechar">&times;</button>
            </div>
            <form id="form-edit-team"
                  action="${getBaseUrl()}/adm/services/editBarber.php"
                  method="POST"
                  enctype="multipart/form-data">
                <input type="hidden" name="barber-id" id="edit-barber-id">
                <div class="modal-form-grid">
                    <div class="full">
                        <label>Nome do profissional</label>
                        <input type="text" name="nome" id="edit-barber-nome"
                               placeholder="Ex: João Pereira" required>
                    </div>
                    <div class="full">
                        <label>Foto de perfil</label>
                        <div class="modal-file-drop">
                            <input type="file" name="foto" id="edit-barber-foto"
                                   accept="image/*">
                            <i class='bx bx-image-add'></i>
                            <p>Clique ou arraste uma nova foto aqui</p>
                            <img class="preview-thumb" id="edit-barber-thumb" alt="Pré-visualização">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-btn-cancel">Cancelar</button>
                    <button type="submit" class="modal-btn-save">
                        <i class='bx bx-save'></i> Salvar
                    </button>
                </div>
            </form>
        </div>`;

    /* Fechar pelo × e pelo Cancelar */
    teamOverlay.querySelector('.modal-close-btn').addEventListener('click', () => closeModal(teamOverlay));
    teamOverlay.querySelector('.modal-btn-cancel').addEventListener('click', () => closeModal(teamOverlay));

    /* Preview de imagem antes de enviar */
    document.getElementById('edit-barber-foto').addEventListener('change', function () {
        const thumb = document.getElementById('edit-barber-thumb');
        if (this.files && this.files[0]) {
            thumb.src = URL.createObjectURL(this.files[0]);
            thumb.style.display = 'block';
        }
    });

    /* Delegação: clique em .edit-icon dentro de .adm-team-card */
    document.addEventListener('click', function (e) {
        const editIcon = e.target.closest('.adm-team-card .edit-icon');
        if (!editIcon) return;

        const card = editIcon.closest('.adm-team-card');
        if (!card) return;

        /* Lê nome do input original do card */
        const nomeInput = card.querySelector('.name input');
        const nome = nomeInput?.value.trim() ?? '';

        /* Se o seu backend usa ID, adicione data-id="<?= $barbeiro['id'] ?>" no .adm-team-card */
        const id = card.dataset.id ?? '';

        document.getElementById('edit-barber-nome').value = nome;
        document.getElementById('edit-barber-id').value   = id;
        /* Limpa preview anterior */
        const thumb = document.getElementById('edit-barber-thumb');
        thumb.src = '';
        thumb.style.display = 'none';
        document.getElementById('edit-barber-foto').value = '';

        openModal(teamOverlay);
    });

    /* ── Fechar com ESC ──────────────────────────────────── */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal(serviceOverlay);
            closeModal(teamOverlay);
        }
    });

    /* ── Helper: detecta BASE_URL do atributo já presente no DOM ─ */
    function getBaseUrl() {
        /* Pega o href do link de logout que já usa BASE_URL */
        const logout = document.querySelector('a.logout[href]');
        if (logout) {
            return logout.href.replace(/\/user\/login\.php.*$/, '');
        }
        return '';
    }

})();