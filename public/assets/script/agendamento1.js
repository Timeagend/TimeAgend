// Variáveis Globais
let servicoSelecionado = '';
let corteSelecionado = '';
let profissionalSelecionado = null;
let comboSelecionado = ''; // Variável global para armazenar a seleção do combo
let dataSelecionada = ''; // Variável para armazenar a data selecionada
let horaSelecionada = ''; // Variável para armazenar a hora selecionada
let valorComboSelecionada = 0; // Valor do serviço de barba
let valorCortesSelecionada = 0; // Valor do serviço de cortes

// ------------------------------------------------------------------------------------
// CATEGORIA: CORTE

let corteSelecionadoCortes = '';
let profissionalSelecionadoCortes = '';
let horarioSelecionadoCortes = '';

// Seleciona o tipo de corte
function selecionarTipoCorte(tipoCorte, preco) {
    // Atualiza variáveis globais se necessário
    corteSelecionadoCortes = tipoCorte;
    valorCortesSelecionada = preco;

    // Obter todos os tr da tabela
    const cortes = document.querySelectorAll('table tr');
    cortes.forEach(corte => {
        // Remover a classe 'selected' de todos os cortes
        corte.classList.remove('selected-servicos');

        // Desmarcar todos os checkboxes
        const checkbox = corte.querySelector('input[type="checkbox"]');
        if (checkbox) checkbox.checked = false;
    });

    // Encontrar o tr correspondente ao tipo de corte e marcar
    const corteItem = document.getElementById(`corte${['Corte Clássico', 'Corte Infantil', 'Corte Degradê', 'Corte Americano', 'Corte Low Fade'].indexOf(tipoCorte) + 1}`);
    if (corteItem) {
        const checkbox = corteItem.querySelector('input[type="checkbox"]');
        if (checkbox) checkbox.checked = true;
        corteItem.classList.add('selected-servicos');
    }
}


// Confirma o corte e mostra os profissionais
function confirmarCorte() {
    if (!corteSelecionadoCortes) {
        alert('Por favor, selecione um corte!');
        return;
    }

    document.getElementById('Cortes-menu').style.display = 'none';
    document.getElementById('profissional-menu').style.display = 'block';
}

// Seleciona o profissional
function selecionarProfissionalCortes(nomeProfissional) {
    profissionalSelecionadoCortes = nomeProfissional;

    const profissionais = document.querySelectorAll('.profissional-card');
    profissionais.forEach(card => card.classList.remove('selected1'));

    const profissionalSelecionadoCard = Array.from(profissionais).find(card =>
        card.querySelector('h3')?.innerText === nomeProfissional
    );

    if (profissionalSelecionadoCard) {
        profissionalSelecionadoCard.classList.add('selected1');
    }
}

// Vai da tela de profissional para a de data/hora
function irParaDataHoraCortes() {
    if (!profissionalSelecionadoCortes) {
        alert('Por favor, selecione um profissional!');
        return;
    }

    document.getElementById('profissional-menu').style.display = 'none';
    document.getElementById('selecionaData-cortes').style.display = 'block';
}

// Voltar da tela de data/hora para profissionais
function voltarParaProfissionaisCortes() {
    document.getElementById('selecionaData-cortes').style.display = 'none';
    document.getElementById('profissional-menu').style.display = 'block';

    horarioSelecionadoCortes = '';
    document.getElementById('data').value = '';
    document.getElementById('listaHorarios').innerHTML = '';
    document.getElementById('confirmarHorario').style.display = 'none';
}

// Voltar para a seleção de cortes
function voltarParaSelecaoCortes() {
    document.getElementById('profissional-menu').style.display = 'none';
    document.getElementById('Cortes-menu').style.display = 'block';
    document.getElementById('selecionaData-cortes').style.display = 'none';

    corteSelecionadoCortes = '';
    profissionalSelecionadoCortes = '';
    horarioSelecionadoCortes = '';

    document.querySelectorAll('table tr').forEach(tr => tr.classList.remove('selected'));
    document.querySelectorAll('.profissional-card').forEach(card => card.classList.remove('selected'));
}
 
 servicoSelecionado = '';
 corteSelecionado = '';
 profissionalSelecionado = null;
 comboSelecionado = ''; // Variável global para armazenar a seleção do combo
 dataSelecionada = ''; // Variável para armazenar a data selecionada
 horaSelecionada = ''; // Variável para armazenar a hora selecionada


// ------------------------------------------------------------------------------------
// CATEGORIA: COMBO

function selecionarCombo(combo, preco) {
    comboSelecionado = combo;
    valorComboSelecionada = preco;

    // Mapeamento entre nome do combo e ID
    const comboIds = {
        'Corte + Barba': 'comboCorteBarba',
        'Corte + Sobrancelha': 'comboCorteSobrancelha',
        'Completo (C/B/S)': 'comboCompleto'
    };

    // Remove seleção anterior
    const combos = document.querySelectorAll('.combo-item');
    combos.forEach(item => {
        item.classList.remove('selected-servicos');

        const checkbox = item.querySelector('input[type="checkbox"]');
        if (checkbox) checkbox.checked = false;
    });

    // Marcar o novo combo
    const comboId = comboIds[combo];
    const comboItem = document.getElementById(comboId);

    if (comboItem) {
        const checkbox = comboItem.querySelector('input[type="checkbox"]');
        if (checkbox) checkbox.checked = true;

        comboItem.classList.add('selected-servicos');
    } else {
        console.error("Erro: Elemento não encontrado para", combo);
    }
}




// Função para confirmar a seleção de combo e ir para a tela de profissionais
function confirmarCombo() {
    if (!comboSelecionado) { // Verifique se a variável comboSelecionado está definida
        alert('Por favor, selecione um combo!');
        return;
    }

    // Esconde a seleção de combo e mostra a de profissionais
    document.getElementById('Combo-menu').style.display = 'none'; // Esconde a seção de combo
    document.getElementById('profissional-menu-combo').style.display = 'block'; // Mostra a seção de profissionais
}

// Função para selecionar o profissional
function selecionarProfissional(nome) {
    profissionalSelecionado = nome; // Armazena o nome do profissional selecionado
    document.querySelectorAll("#profissional-menu-combo .profissional-card").forEach(card => {
        card.classList.remove("selected1");
    });

    const cards = document.querySelectorAll("#profissional-menu-combo .profissional-card");
    cards.forEach(card => {
        if (card.querySelector("h3").innerText === nome) {
            card.classList.add("selected1");
        }
    });
}

// Função para ir para a seleção de data
function irParaDataHora() {
    // Verifica se um profissional foi selecionado
    if (!profissionalSelecionado) {
        alert('Por favor, selecione um profissional antes de continuar!');
        return; // Impede a navegação se nenhum profissional foi selecionado
    }

    // Esconde a seção de profissionais
    document.getElementById('profissional-menu-combo').style.display = 'none';
    // Mostra a seção de seleção de data
    document.getElementById('selecionaData').style.display = 'block';
}

// Função para confirmar a data e ir para a seleção de hora
function irParaHora() {
    const data = document.getElementById('data').value;
    if (!data) {
        alert('Por favor, selecione uma data!');
        return;
    }
    dataSelecionada = data; // Armazena a data selecionada
    // Esconde a seção de seleção de data
    document.getElementById('selecionaData').style.display = 'none';
    // Mostra a seção de seleção de hora
    document.getElementById('selecionaHora').style.display = 'block';
}

// Função para finalizar o agendamento
function finalizarAgendamento() {
    const hora = document.getElementById('hora').value;
    if (!hora) {
        alert('Por favor, selecione um horário!');
        return;
    }
    horaSelecionada = hora; // Armazena a hora selecionada

    // Aqui você pode adicionar a lógica para finalizar o agendamento
    alert(`Agendamento confirmado para ${dataSelecionada} às ${horaSelecionada} com o profissional ${profissionalSelecionado}.`);
}

function voltarParaMenuInicial() {
    // Restaura a tela principal e a seleção de serviços
    document.querySelector('.main-container').style.display = 'block';
    document.querySelector('.seleciona-servicos').style.display = 'flex';

    // Esconde o menu atual
    const menuSelecionado = document.getElementById(`${servicoSelecionado}-menu`);
    if (menuSelecionado) menuSelecionado.style.display = 'none';

    // Esconde outras possíveis seções abertas
    document.getElementById('profissional-menu-combo').style.display = 'none';
    document.getElementById('selecionaData').style.display = 'none';
    document.getElementById('selecionaHora').style.display = 'none';

    // Resetando seleções globais
    servicoSelecionado = '';
    comboSelecionado = '';
    profissionalSelecionado = '';
    dataSelecionada = '';
    horaSelecionada = '';

    // Remove seleção visual de serviços
    const servicos = document.querySelectorAll('.seleciona-servicos h3');
    servicos.forEach(servico => servico.classList.remove('selected'));

    // Remove seleção visual de combos
    document.querySelectorAll('.combo-item').forEach(item => {
        item.classList.remove('selected');
    });

    // Remove seleção visual de profissionais
    document.querySelectorAll('.profissional-card').forEach(card => {
        card.classList.remove('selected1');
    });

    // Esconde botão de voltar
    document.getElementById('voltar').style.display = 'none';

    // Restaura o título
    document.getElementById('tituloServico').innerText = 'SELECIONE UM SERVIÇO';
}


// Função para voltar para a seleção de combo
function voltarParaSelecaoCombo() {
    // Restaura a tela de seleção de combo e esconde a de profissionais
    document.getElementById('Combo-menu').style.display = 'block'; // Mostra a seção de combo
    document.getElementById('profissional-menu-combo').style.display = 'none'; // Esconde a seção de profissionais
    // Resetando a seleção de profissional
    profissionalSelecionado = ''; 

}

// Função para voltar da seleção de data para a seleção de profissionais
function voltarParaProfissionais() {
    // Esconde a seção de seleção de data
    document.getElementById('selecionaData').style.display = 'none';
    // Mostra a seção de seleção de profissionais
    document.getElementById('profissional-menu-combo').style.display = 'block';
}

// Função para voltar da seleção de hora para a seleção de data
function voltarParaData() {
    // Esconde a seção de seleção de hora
    document.getElementById('selecionaHora').style.display = 'none';
    // Mostra a seção de seleção de data
    document.getElementById('selecionaData').style.display = 'block';
}

// ------------------------------------------------------------------------------------
// CATEGORIA: SERVIÇOS

// Função para selecionar o serviço
function selecionarServico(servico) {
    servicoSelecionado = servico;

    // Marca o serviço selecionado visualmente
    const servicos = document.querySelectorAll('.seleciona-servicos h3');
    servicos.forEach(s => s.classList.remove('selected-servicos'));

    const servicoSelecionadoElemento = document.getElementById(`servico${['Cortes', 'Combo', 'Barba', 'Sobrancelha'].indexOf(servico) + 1}`);
    if (servicoSelecionadoElemento) servicoSelecionadoElemento.classList.add('selected-servicos');
}

// Função para confirmar o serviço e mostrar detalhes
function confirmarServico() {
    if (!servicoSelecionado) {
        alert('Por favor, selecione um serviço primeiro!');
        return;
    }

    // Esconde a tela principal e mostra o menu do serviço selecionado
    document.querySelector('.main-container').style.display = 'none';
    document.querySelector('.seleciona-servicos').style.display = 'none';

    const menuSelecionado = document.getElementById(`${servicoSelecionado}-menu`);
    if (menuSelecionado) menuSelecionado.style.display = 'block';

    // Exibe o botão de voltar
    document.getElementById('voltar').style.display = 'block';

    // Atualiza o título para o nome do serviço
    document.getElementById('tituloServico').innerText = servicoSelecionado.toUpperCase();
}

// Função para voltar à seleção de serviços
function voltarParaSelecao() {
    // Restaura a tela principal e a seleção de serviços
    document.querySelector('.main-container').style.display = 'block';
    document.querySelector('.seleciona-servicos').style.display = 'flex';

    // Esconde o menu atual
    const menuSelecionado = document.getElementById(`${servicoSelecionado}-menu`);
    if (menuSelecionado) menuSelecionado.style.display = 'none';

    // Reseta a seleção visual
    const servicos = document.querySelectorAll('.seleciona-servicos h3');
    servicos.forEach(servico => servico.classList.remove('selected'));

    servicoSelecionado = '';

    // Esconde o botão de voltar
    document.getElementById('voltar').style.display = 'none';

    // Restaura o título original
    document.getElementById('tituloServico'). innerText = 'SELECIONE UM SERVIÇO';
}