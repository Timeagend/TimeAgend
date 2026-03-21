// Variáveis globais corrigidas
if (typeof horarioSelecionadoSobrancelha === 'undefined') {
    var horarioSelecionadoSobrancelha = '';
    var sobrancelhaSelecionada = ''; // Nome do serviço
    var profissionalSelecionadoSobrancelha = '';
    var valorSobrancelhaSelecionada = '';
}

// Selecionar horário
function selecionarHorarioSobrancelha(horario) {
    document.querySelectorAll('#horariosSobrancelha button').forEach(btn => {
        btn.classList.remove('selecionado');
    });

    document.querySelectorAll('#horariosSobrancelha button').forEach(botao => {
        if (botao.innerText.trim() === horario) {
            botao.classList.add('selecionado');
        }
    });

    horarioSelecionadoSobrancelha = horario;
}

// Abrir modal de resumo
function abrirResumoSobrancelha() {
    const dataInput = document.getElementById('dataSobrancelha').value.trim();

    if (!dataInput || !horarioSelecionadoSobrancelha) {
        alert('Por favor, preencha todos os campos e selecione um horário.');
        return;
    }

    const modalResumo = document.getElementById('modalResumoSobrancelha');
    if (!modalResumo) return console.error("Modal resumo não encontrado!");

    document.getElementById('resumoServicoSobrancelha').innerHTML = `<strong>Serviço:</strong> ${sobrancelhaSelecionada}`;
    document.getElementById('resumoProfissionalSobrancelha').innerHTML = `<strong>Profissional:</strong> ${profissionalSelecionadoSobrancelha}`;
    document.getElementById('resumoDataSobrancelha').innerHTML = `<strong>Data:</strong> ${dataInput}`;
    document.getElementById('resumoHorarioSobrancelha').innerHTML = `<strong>Horário:</strong> ${horarioSelecionadoSobrancelha}`;
    document.getElementById('resumoValorSobrancelha').innerHTML = `<strong>Valor:</strong> R$ ${valorSobrancelhaSelecionada}`;

    modalResumo.style.display = 'block';
}

// Fechar modal de resumo
function fecharResumoSobrancelha() {
    const modalResumo = document.getElementById('modalResumoSobrancelha');
    if (modalResumo) modalResumo.style.display = 'none';
}

// Fechar modal de sucesso
function fecharSucessoSobrancelha() {
    const modalSucesso = document.getElementById('sucessoModalSobrancelha');
    if (modalSucesso) modalSucesso.style.display = 'none';
}

// Confirmar agendamento
function confirmaAgendamentoSobrancelha() {
    const dataInput = document.getElementById('dataSobrancelha').value.trim();

    if (!dataInput || !horarioSelecionadoSobrancelha) {
        alert('Preencha todos os campos antes de confirmar.');
        return;
    }

    confirmaSobrancelhaBackend();
}

// Envia os dados para o backend
function confirmaSobrancelhaBackend() {
    const dataInput = document.getElementById('dataSobrancelha').value.trim();

    const dados = {
        tipoServico: sobrancelhaSelecionada,
        profissional: profissionalSelecionadoSobrancelha,
        data: dataInput,
        horario: horarioSelecionadoSobrancelha,
        valor: valorSobrancelhaSelecionada
    };

    fetch("assets/script/salvar_agendamento.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(dados)
    })
    .then(response => response.json())
    .then(resultado => {
        console.log("Resposta do servidor:", resultado);
        if (resultado.status === "success") {
            fecharResumoSobrancelha();
            const modalSucesso = document.getElementById("sucessoModalSobrancelha");
            if (modalSucesso) modalSucesso.style.display = "block";
        } else {
            alert("Erro ao agendar: " + (resultado.mensagem || "Sem mensagem do servidor"));
        }
    })
    .catch(error => {
        console.error("Erro ao confirmar agendamento:", error);
        alert("Ocorreu um erro ao tentar agendar.");
    });
}
