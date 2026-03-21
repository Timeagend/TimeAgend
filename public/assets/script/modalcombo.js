let horarioSelecionadoCombo = '';


function selecionarHorarioCombo(horario) {
    horarioSelecionadoCombo = horario;
    
    
    // Seleciona todos os botões dentro da div com ID 'horariosBarba'
    const botoes = document.querySelectorAll("#horariosCombo button");
    botoes.forEach(btn => btn.classList.remove("selecionado"));
    
    botoes.forEach(botao => {
        if (botao.innerText.trim() === horario) { // Usar trim() para remover possíveis espaços extras
            botao.classList.add('selecionado'); // Adiciona a classe de selecionado
        }
    });

    // Guarda o horário selecionado
    horarioSelecionadoCombo = horario;
}



// Abrir modal com resumo
function opencombo() {
    
    const data = document.getElementById("dataCombo").value.trim();

    if (!data || !horarioSelecionadoCombo) {
        alert("Preencha todos os campos e selecione um horário.");
        return;
    }

    // Preencher dados no modal
    
    document.getElementById("resumoServicoCombo").innerHTML = `<strong>Serviço:</strong> ${comboSelecionado}`;
    document.getElementById("resumoProfissionalCombo").innerHTML = `<strong>Profissional:</strong> ${profissionalSelecionado}`;
    document.getElementById("resumoDataCombo").innerHTML = `<strong>Data:</strong> ${data}`;
    document.getElementById("resumoHorarioCombo").innerHTML = `<strong>Horário:</strong> ${horarioSelecionadoCombo}`;
    document.getElementById("resumoValorCombo").innerHTML = `<strong>Valor:</strong> R$ ${valorComboSelecionada}`; // Substitua conforme o serviço

    // Mostrar o modal
    document.getElementById("modalResumoCombo").style.display = "block";
}



// Confirmar agendamento
function confirmaAgendamentoCombo() {
    // Fecha o modal de resumo
    fecharModalCombo();

    // Abre o modal de sucesso
    document.getElementById("sucessoModalCombo").style.display = "block";
    confirmaComboBackend(opencombo);

}

// Fechar modal
function fecharModalCombo() {
    document.getElementById("modalResumoCombo").style.display = "none";
}

// // Fechar modal de sucesso
// function fecharModalSucessoCombo() {
//     document.getElementById("sucessoModalCombo").style.display = "none";
// }

function confirmaComboBackend(callback) {
    const data = document.getElementById("dataCombo").value.trim();

    // Dados que você quer enviar
    const dados = {
        tipoServico: comboSelecionado,   // ✅ corrigido
        profissional: profissionalSelecionado,
        data: data,
        horario: horarioSelecionadoCombo,
        valor: valorComboSelecionada
    };

    // Enviar os dados para o backend
    fetch("assets/script/salvar_agendamento.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(dados)
    })
    .then(response => response.json())
    .then(resultado => {
        console.log("Resposta do servidor:", resultado);
        if (resultado.status === "success") {
            fecharModalCombo();
            document.getElementById("sucessoModalCombo").style.display = "block";
        } else {
            alert(resultado.mensagem);
        }
    })
    .catch(error => {
        console.error("Erro ao confirmar agendamento:", error);
        alert("Ocorreu um erro ao salvar o agendamento.");
    });
}
