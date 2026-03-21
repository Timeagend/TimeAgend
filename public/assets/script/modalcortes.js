

// Função para selecionar horário
function selecionarHorarioCortes(horario) {
    horarioSelecionadoCortes = horario;

    // Seleciona todos os botões dentro da div com ID 'horariosCortes'
    const botoes = document.querySelectorAll("#horariosCortes button");
    botoes.forEach(btn => btn.classList.remove("selecionado"));

    // Marca o botão selecionado
    botoes.forEach(botao => {
        if (botao.innerText.trim() === horario) {
            botao.classList.add('selecionado');
        }
    });
}



// Abrir modal com resumo
function openCortes() {
   
    const data = document.getElementById("dataCortes").value.trim();

    if (!data || !horarioSelecionadoCortes) {
        alert("Preencha todos os campos e selecione um horário.");
        return;
    }

    // Preencher dados no modal
   
    document.getElementById("resumoServicoCortes").innerHTML = `<strong>Serviço:</strong> ${corteSelecionadoCortes}`;
    document.getElementById("resumoProfissionalCortes").innerHTML = `<strong>Profissional:</strong> ${profissionalSelecionadoCortes}`;
    document.getElementById("resumoDataCortes").innerHTML = `<strong>Data:</strong> ${data}`;
    document.getElementById("resumoHorarioCortes").innerHTML = `<strong>Horário:</strong> ${horarioSelecionadoCortes}`;
    document.getElementById("resumoValorCortes").innerHTML = `<strong>Valor:</strong> R$ ${valorCortesSelecionada}`; // Substitua conforme o serviço

    // Mostrar o modal
    document.getElementById("modalResumoCortes").style.display = "block";
}



// Confirmar agendamento
function confirmaAgendamentoCortes() {

    // Fecha o modal de resumo
    fecharModalCortes();

    // Abre o modal de sucesso
    document.getElementById("sucessoModalCortes").style.display = "block";
    
    confirmaCortesBackend(openCortes);
}

// Fechar modal
function fecharModalCortes() {
    document.getElementById("modalResumoCortes").style.display = "none";
}

function confirmaCortesBackend() {
    const data = document.getElementById("dataCortes").value.trim();

    const dados = {
        data: data,
        horario: horarioSelecionadoCortes,
        profissional: profissionalSelecionadoCortes,
        tipoServico: corteSelecionadoCortes,
        valor: valorCortesSelecionada
    };

    fetch("assets/script/salvar_agendamento.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(dados)
    })
    .then(response => response.json())
    .then(resultado => {
        if (resultado.status === "success") {
            fecharModalCortes();
            document.getElementById("sucessoModalCortes").style.display = "block";
        } else {
            alert(resultado.mensagem);
        }
    })
    .catch(error => console.error("Erro:", error));
}
