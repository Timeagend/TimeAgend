let sobrancelhaSelecionada = '';
let profissionalSelecionadoSobrancelha = '';
let horarioSelecionadoSobrancelha = '';
let valorSobrancelhaSelecionada = 0;

// Seleciona o tipo de sobrancelha
function selecionarTipoSobrancelha(tipo, preco) {
    sobrancelhaSelecionada = tipo;
    valorSobrancelhaSelecionada = preco;

    // Desmarcar todos os checkboxes e remover a classe 'selected' de todas as linhas
    const opcoes = document.querySelectorAll('#Sobrancelha-menu table tr');
    opcoes.forEach(op => {
        op.classList.remove('selected-servicos');
        const checkbox = op.querySelector('input[type="checkbox"]');
        if (checkbox) checkbox.checked = false;
    });

    // Mapeamento entre tipo de sobrancelha e ID
    const ids = ['Sobrancelha Navalha', 'Sobrancelha Pinça'];  // Exemplo de tipos
    const index = ids.indexOf(tipo);
    const itemSelecionado = document.getElementById(`sobrancelha${index + 1}`);

    if (itemSelecionado) {
        const checkbox = itemSelecionado.querySelector('input[type="checkbox"]');
        if (checkbox) checkbox.checked = true; // Marca o checkbox correspondente
        itemSelecionado.classList.add('selected-servicos'); // Adiciona destaque visual
    }
}


// function selecionarTipoSobrancelha(nome, preco) {
//     sobrancelhaSelecionada = nome;
//     valorSobrancelhaSelecionada = preco;
// }

// Confirma o tipo e avança para seleção de profissional
function confirmarSobrancelha() {
    if (!sobrancelhaSelecionada) {
        alert("Por favor, selecione um tipo de sobrancelha.");
        return;
    }

    document.getElementById("Sobrancelha-menu").style.display = "none";
    document.getElementById("profissional-menu-sobrancelha").style.display = "block";
}

// Seleciona profissional
function selecionarProfissionalSobrancelha(nome) {
    profissionalSelecionadoSobrancelha = nome;

    document.querySelectorAll("#profissional-menu-sobrancelha .profissional-card").forEach(card => {
        card.classList.remove("selected1");
    });

    const cards = document.querySelectorAll("#profissional-menu-sobrancelha .profissional-card");
    cards.forEach(card => {
        if (card.querySelector("h3").innerText === nome) {
            card.classList.add("selected1");
        }
    });
}

// Confirma profissional e vai pra data
function irParaDataHoraSobrancelha() {
    if (!profissionalSelecionadoSobrancelha) {
        alert("Por favor, selecione um profissional.");
        return;
    }

    document.getElementById("profissional-menu-sobrancelha").style.display = "none";
    document.getElementById("selecionadata-sobrancelha").style.display = "block";
}

// Voltar para tipo de sobrancelha
function voltarParaSelecaoSobrancelha() {
    document.getElementById("profissional-menu-sobrancelha").style.display = "none";
    document.getElementById("Sobrancelha-menu").style.display = "block";
}

// Voltar para profissionais
function voltarParaProfissionaisSobrancelha() {
    document.getElementById("selecionadata-sobrancelha").style.display = "none";
    document.getElementById("profissional-menu-sobrancelha").style.display = "block";
}

// // Exibe os horários disponíveis
// function mostrarHorariosDisponiveis() {
//     const dataInput = document.getElementById("dataSobrancelha");
//     const horariosDiv = document.getElementById("horariosDisponiveis");
//     const listaHorarios = document.getElementById("listaHorarios");
//     const btnConfirmar = document.getElementById("confirmarHorarioSobrancelha");

//     listaHorarios.innerHTML = "";
//     btnConfirmar.style.display = "none";

//     if (dataInput.value) {
//         horariosDiv.style.display = "block";

//         // Lista de horários mockados
//         const horarios = ["09:00", "10:00", "11:00", "14:00", "15:00"];

//         horarios.forEach(horario => {
//             const btn = document.createElement("button");
//             btn.innerText = horario;
//             btn.className = "horario-button";
//             btn.onclick = function () {
//                 document.querySelectorAll(".horario-button").forEach(b => b.classList.remove("selected"));
//                 btn.classList.add("selected");
//                 horarioSelecionadoSobrancelha = horario;
//                 btnConfirmar.style.display = "inline-block";
//             };
//             listaHorarios.appendChild(btn);
//         });
//     }
// }

// // Finaliza o agendamento
// function finalizarAgendamento() {
//     if (!horarioSelecionadoSobrancelha) {
//         alert("Selecione um horário.");
//         return;
//     }

//     alert(`Agendamento realizado com sucesso!\n\nTipo: ${sobrancelhaSelecionada}\nProfissional: ${profissionalSelecionadoSobrancelha}\nHorário: ${horarioSelecionadoSobrancelha}`);
    
//     // Resetar (opcional)
//     sobrancelhaSelecionada = '';
//     profissionalSelecionadoSobrancelha = '';
//     horarioSelecionadoSobrancelha = '';

//     // Oculta tudo e volta pro início (ou faz o que preferir)
//     document.getElementById("selecionadata-sobrancelha").style.display = "none";
//     document.getElementById("Sobrancelha-menu").style.display = "block";
// }
