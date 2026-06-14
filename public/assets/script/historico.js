/* historico.js — confirmação de cancelamento + filtro */

document.addEventListener('DOMContentLoaded', function () {

    /* ── Modal de confirmação ─────────────────────────────────── */
    const overlay    = document.getElementById('confirmOverlay');
    const btnSim     = document.getElementById('btnSim');
    const btnNao     = document.getElementById('btnNao');
    let   formPendente = null;

    document.querySelectorAll('.cancel-button').forEach(function (btn) {
        btn.addEventListener('click', function () {
            formPendente = btn.closest('.cancel-form');
            overlay.classList.add('active');
        });
    });

    btnSim.addEventListener('click', function () {
        overlay.classList.remove('active');
        if (formPendente) {
            formPendente.submit();
            formPendente = null;
        }
    });

    btnNao.addEventListener('click', function () {
        overlay.classList.remove('active');
        formPendente = null;
    });

    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) {
            overlay.classList.remove('active');
            formPendente = null;
        }
    });

    /* ── Filtro por período ───────────────────────────────────── */
    const filterBtns = document.querySelectorAll('.filter-btn');
    const cards      = document.querySelectorAll('.appointment-card');

    filterBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {

            filterBtns.forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');

            const filtro = btn.dataset.filter;

            cards.forEach(function (card) {
                const periodo = card.dataset.periodo;

                if (
                    filtro === 'todos' ||
                    (filtro === 'proximos' && periodo === 'proximo') ||
                    (filtro === 'passados' && periodo === 'passado')
                ) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });

            const wrapper  = document.getElementById('cardsWrapper');
            const visiveis = wrapper ? wrapper.querySelectorAll('.appointment-card:not(.hidden)') : [];
            let   aviso    = document.getElementById('filtroVazio');

            if (visiveis.length === 0) {
                if (!aviso) {
                    aviso = document.createElement('p');
                    aviso.id = 'filtroVazio';
                    aviso.style.cssText = 'color:#aaa; margin-top:2rem; width:100%; text-align:center;';
                    wrapper.appendChild(aviso);
                }
                aviso.textContent =
                    filtro === 'proximos'
                        ? 'Nenhum agendamento futuro.'
                        : 'Nenhum agendamento passado.';
            } else if (aviso) {
                aviso.remove();
            }
        });
    });

});