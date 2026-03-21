/**
 * @jest-environment jsdom
 */

describe('openContactModal', () => {
    let originalAlert;
    let horarioSelecionadoSobrancelha, sobrancelhaSelecionada, profissionalSelecionadoSobrancelha, valorSobrancelhaSelecionada;

    beforeEach(() => {
        // Mock global variables
        horarioSelecionadoSobrancelha = '10:00';
        sobrancelhaSelecionada = 'Design de Sobrancelhas';
        profissionalSelecionadoSobrancelha = 'Maria';
        valorSobrancelhaSelecionada = '50';

        // Set up DOM elements
        document.body.innerHTML = `
            <input class="nome-form2" value="João">
            <input class="data-form2" value="2023-10-10">
            <div id="resumoNome"></div>
            <div id="resumoServico"></div>
            <div id="resumoProfissional"></div>
            <div id="resumoData"></div>
            <div id="resumoHorario"></div>
            <div id="resumoValor"></div>
            <div id="resumoModal" style="display: none;"></div>
        `;

        // Mock alert
        originalAlert = window.alert;
        window.alert = jest.fn();
    });

    afterEach(() => {
        // Restore original alert
        window.alert = originalAlert;
    });

    it('should display an alert if any required field is empty', () => {
        document.querySelector('.nome-form2').value = ''; // Simulate empty name input
        openContactModal();
        expect(window.alert).toHaveBeenCalledWith('Por favor, preencha todos os campos e selecione um horário antes de confirmar.');
    });

    it('should update modal content and display the modal when all fields are filled', () => {
        openContactModal();

        expect(document.getElementById('resumoNome').innerHTML).toBe('<strong>Nome:</strong> João');
        expect(document.getElementById('resumoServico').innerHTML).toBe('<strong>Serviço:</strong> Design de Sobrancelhas');
        expect(document.getElementById('resumoProfissional').innerHTML).toBe('<strong>Profissional:</strong> Maria');
        expect(document.getElementById('resumoData').innerHTML).toBe('<strong>Data:</strong> 2023-10-10');
        expect(document.getElementById('resumoHorario').innerHTML).toBe('<strong>Horário:</strong> 10:00');
        expect(document.getElementById('resumoValor').innerHTML).toBe('<strong>Valor:</strong> 0,00');
        expect(document.getElementById('resumoModal').style.display).toBe('block');
    });

    /**
     * @jest-environment jsdom
     */

    describe('selecionarHorario', () => {
        let horarioSelecionadoSobrancelha;

        beforeEach(() => {
            // Set up DOM elements
            document.body.innerHTML = `
                <div class="horarios-grid">
                    <button>08:00</button>
                    <button>10:00</button>
                    <button>12:00</button>
                </div>
            `;
        });

        it('should remove the "selecionado" class from all buttons', () => {
            document.querySelectorAll('.horarios-grid button').forEach(btn => btn.classList.add('selecionado'));
            selecionarHorario('10:00');
            document.querySelectorAll('.horarios-grid button').forEach(btn => {
                expect(btn.classList.contains('selecionado')).toBe(false);
            });
        });

        it('should add the "selecionado" class to the button with the matching horario', () => {
            selecionarHorario('10:00');
            const selectedButton = Array.from(document.querySelectorAll('.horarios-grid button')).find(btn => btn.innerText.trim() === '10:00');
            expect(selectedButton.classList.contains('selecionado')).toBe(true);
        });

        it('should update the global variable "horarioSelecionadoSobrancelha"', () => {
            selecionarHorario('10:00');
            expect(horarioSelecionadoSobrancelha).toBe('10:00');
        });
    });

    describe('openContactModal', () => {
        let originalAlert;
        let horarioSelecionadoSobrancelha, sobrancelhaSelecionada, profissionalSelecionadoSobrancelha, valorSobrancelhaSelecionada;

        beforeEach(() => {
            // Mock global variables
            horarioSelecionadoSobrancelha = '10:00';
            sobrancelhaSelecionada = 'Design de Sobrancelhas';
            profissionalSelecionadoSobrancelha = 'Maria';
            valorSobrancelhaSelecionada = '50';

            // Set up DOM elements
            document.body.innerHTML = `
                <input class="nome-form2" value="João">
                <input class="data-form2" value="2023-10-10">
                <div id="resumoNome"></div>
                <div id="resumoServico"></div>
                <div id="resumoProfissional"></div>
                <div id="resumoData"></div>
                <div id="resumoHorario"></div>
                <div id="resumoValor"></div>
                <div id="resumoModal" style="display: none;"></div>
            `;

            // Mock alert
            originalAlert = window.alert;
            window.alert = jest.fn();
        });

        afterEach(() => {
            // Restore original alert
            window.alert = originalAlert;
        });

        it('should display an alert if any required field is empty', () => {
            document.querySelector('.nome-form2').value = ''; // Simulate empty name input
            openContactModal();
            expect(window.alert).toHaveBeenCalledWith('Por favor, preencha todos os campos e selecione um horário antes de confirmar.');
        });

        it('should update modal content and display the modal when all fields are filled', () => {
            openContactModal();

            expect(document.getElementById('resumoNome').innerHTML).toBe('<strong>Nome:</strong> João');
            expect(document.getElementById('resumoServico').innerHTML).toBe('<strong>Serviço:</strong> Design de Sobrancelhas');
            expect(document.getElementById('resumoProfissional').innerHTML).toBe('<strong>Profissional:</strong> Maria');
            expect(document.getElementById('resumoData').innerHTML).toBe('<strong>Data:</strong> 2023-10-10');
            expect(document.getElementById('resumoHorario').innerHTML).toBe('<strong>Horário:</strong> 10:00');
            expect(document.getElementById('resumoValor').innerHTML).toBe('<strong>Valor:</strong> R$ 50');
            expect(document.getElementById('resumoModal').style.display).toBe('block');
        });
    });

    describe('fecharModal', () => {
        beforeEach(() => {
            document.body.innerHTML = `
                <div id="resumoModal" style="display: block;"></div>
            `;
        });

        it('should hide the modal', () => {
            fecharModal();
            expect(document.getElementById('resumoModal').style.display).toBe('none');
        });
    });

    describe('pagarPix', () => {
        let originalAlert;

        beforeEach(() => {
            document.body.innerHTML = `
                <div id="resumoModal" style="display: block;"></div>
            `;

            // Mock alert
            originalAlert = window.alert;
            window.alert = jest.fn();
        });

        afterEach(() => {
            // Restore original alert
            window.alert = originalAlert;
        });

        it('should display a success alert and close the modal', () => {
            pagarPix();
            expect(window.alert).toHaveBeenCalledWith('Agendamento realizado com sucesso!');
            expect(document.getElementById('resumoModal').style.display).toBe('none');
        });
    });
});