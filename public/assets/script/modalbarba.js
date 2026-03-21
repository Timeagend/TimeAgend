

let horarioSelecionadoBarba = ''; // Declare the variable at the appropriate scope

function selecionarHorarioBarba(horario) {
    horarioSelecionadoBarba = horario;
    
    
    // Seleciona todos os botões dentro da div com ID 'horariosBarba'
    const botoes = document.querySelectorAll("#horariosBarba button");
    botoes.forEach(btn => btn.classList.remove("selecionado"));

    botoes.forEach(botao => {
        if (botao.innerText.trim() === horario) { // Usar trim() para remover possíveis espaços extras
            botao.classList.add('selecionado'); // Adiciona a classe de selecionado
        }
    });

    // Guarda o horário selecionado
    horarioSelecionadoBarba = horario;
}



// Abrir modal com resumo
function openContactModal3() {
    
    const data = document.getElementById("dataBarba").value.trim();

    if ( !data || !horarioSelecionadoBarba) {
        alert("Preencha todos os campos e selecione um horário.");
        return;
    }
    


    // Preencher dados no modal
    
    document.getElementById("resumoServicoBarba").innerHTML = `<strong>Serviço:</strong> ${tipoBarbaSelecionado}`;
    document.getElementById("resumoProfissionalBarba").innerHTML = `<strong>Profissional:</strong> ${profissionalSelecionadoBarba}`;
    document.getElementById("resumoDataBarba").innerHTML = `<strong>Data:</strong> ${data}`;
    document.getElementById("resumoHorarioBarba").innerHTML = `<strong>Horário:</strong> ${horarioSelecionadoBarba}`;
    document.getElementById("resumoValorBarba").innerHTML = `<strong>Valor:</strong> R$ ${valorBarbaSelecionada}`; // Substitua conforme o serviço

    // Mostrar o modal
    document.getElementById("modalResumoBarba").style.display = "block";
}


// Confirmar agendamento
function confirmaAgendamentoBarba() {
    fecharModalBarba();
    document.getElementById("sucessoModalBarba").style.display = "block";
    confirmaBarbaBackend(openContactModal3);
}
// Fechar modal
function fecharModalBarba() {
    document.getElementById("modalResumoBarba").style.display = "none";
}

// Fechar modal de sucesso
function fecharModalSucessoBarba() {
    document.getElementById("sucessoModalBarba").style.display = "none";
}

function confirmaBarbaBackend(callback) {
    const data = document.getElementById("dataBarba").value.trim();

    const dados = {
        tipoServico: tipoBarbaSelecionado,
        profissional: profissionalSelecionadoBarba,
        data: data,
        horario: horarioSelecionadoBarba,
        valor: valorBarbaSelecionada
    };

    fetch("assets/script/salvar_agendamento.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(dados)
    })
    .then(response => response.json()) // agora espera JSON do PHP
    .then(resultado => {
        console.log("Resposta do servidor:", resultado);
        if(resultado.status === "success") {
            fecharModalBarba();
            document.getElementById("sucessoModalBarba").style.display = "block";
        } else {
            alert(resultado.mensagem);
        }
    })
    .catch(error => {
        console.error("Erro ao salvar agendamento:", error);
        alert("Ocorreu um erro ao salvar o agendamento.");
    });
}