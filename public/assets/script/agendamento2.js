let tipoBarbaSelecionado = null;
let profissionalSelecionadoBarba = null;
let valorBarbaSelecionada = 0; // Valor do serviço de barba

// Selecionar tipo de barba
function selecionarTipoBarba(tipo, preco) {
    tipoBarbaSelecionado = tipo;
    valorBarbaSelecionada = preco; // Atualiza o valor do serviço de barba

    // Desmarcar todos os checkboxes e remover a classe 'selected' de todas as linhas
    const linhas = document.querySelectorAll('#Barba-menu table tr');
    linhas.forEach(linha => {
        linha.classList.remove('selected-servicos');
        const checkbox = linha.querySelector('input[type="checkbox"]');
        if (checkbox) checkbox.checked = false;
    });

    // Encontrar o tr correspondente ao tipo de barba
    const linhaSelecionada = Array.from(linhas).find(tr => tr.textContent.includes(tipo.toUpperCase()));
    if (linhaSelecionada) {
        const checkbox = linhaSelecionada.querySelector('input[type="checkbox"]');
        if (checkbox) checkbox.checked = true; // Marca o checkbox correspondente
        linhaSelecionada.classList.add('selected-servicos'); // Adiciona destaque visual
    }
}


// Confirmar tipo e ir para profissionais
function confirmaBarba() {
    if (!tipoBarbaSelecionado) {
        alert("Selecione um tipo de barba antes de continuar.");
        return;
    }

    document.getElementById("Barba-menu").style.display = "none";
    document.getElementById("profissional-menu-barba").style.display = "block";
}

// Selecionar profissional
function selecionarProfissionalBarba(nome) {
    profissionalSelecionadoBarba = nome;

    // Destaque visual
    const cards = document.querySelectorAll("#profissional-menu-barba .profissional-card");
    cards.forEach(card => card.classList.remove("selected1"));

    const cardSelecionado = Array.from(cards).find(c => c.textContent.includes(nome));
    if (cardSelecionado) cardSelecionado.classList.add("selected1");
}

// Confirmar profissional e ir para data/hora
function irParaDataHoraBarba() {
    if (!profissionalSelecionadoBarba) {
        alert("Selecione um profissional antes de continuar.");
        return;
    }

    document.getElementById("profissional-menu-barba").style.display = "none";
    document.getElementById("selecionadata-barba").style.display = "block";
}

// Voltar para tipo de barba
function voltarParaSelecaoBarba() {
    document.getElementById("profissional-menu-barba").style.display = "none";
    document.getElementById("Barba-menu").style.display = "block";
}

// Voltar para profissional
function voltarParaBarba() {
    document.getElementById("selecionadata-barba").style.display = "none";
    document.getElementById("profissional-menu-barba").style.display = "block";
}

// // Mostrar horários disponíveis (simulação)
// function mostrarHorariosDisponiveis() {
//     const horarios = ["09:00", "10:00", "14:00", "16:00"];
//     const lista = document.getElementById("listaHorarios");

//     lista.innerHTML = ""; // limpar

//     horarios.forEach(hora => {
//         const h = document.createElement("h3");
//         h.classList.add("horas");
//         h.innerText = hora;
//         h.onclick = () => selecionarHorario(hora, h);
//         lista.appendChild(h);
//     });

//     document.getElementById("horariosDisponiveis").style.display = "block";
// }


