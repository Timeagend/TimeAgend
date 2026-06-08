<?php 
include_once('../config/url.php');
require_once('../models/auth/authFunctions.php');

session_start();
$validAuth = new Auth($con);
if (!$validAuth->isAuthenticated()) {
    header("Location: " . BASE_URL . "user/login.php");
    exit();
}


$successMessage = '';
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['sendEmail'])) {
    $nome = trim($_POST['user_name']);
    $email = trim($_POST['user_email']);
    $mensagem = trim($_POST['mensagem']);

    if (empty($nome) || empty($email) || empty($mensagem)) {
        $errorMessage = "Por favor, preencha todos os campos.";
    } else {
        // Enviar email ou salvar no banco
        // mail() ou outro processamento

        $successMessage = "Mensagem enviada com sucesso!";
    }
}
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimeAgend Barber Shop - Serviços</title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/agendamento1.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/agendamento2.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/agendamentos.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/resumo.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/responsivo.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/contact.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>adm/img">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/logo.css">
        <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/contato.css">

    <style>
      
    </style>
    
</head>
<body>
    <!-- Header -->
    <header>
    <!-- Logo -->
           <a href="<?= BASE_URL ?>/public/index.php" class="logo-link">
    <div class="logo-icon">
        <i class="fa-solid fa-scissors"></i>
    </div>
    <div class="brand-logo">TimeAgend</div>
</a>
           
        <!-- Botão para alternar o menu (visível apenas no mobile) -->
  <button class="menu-toggle" aria-label="Abrir menu" aria-expanded="false" aria-controls="menu-principal">
    &#9776;
  </button>
 

  <!-- Menu principal -->
  <nav class="menu-principal" id="menu-principal">
    <a href="<?= BASE_URL ?>/public/index.php">Início</a>
    <a href="<?= BASE_URL ?>/public/agendamento.php" class="selected">Agenda</a>
    <a href="<?= BASE_URL ?>/public/planos.php">Planos</a>
    <a href="<?= BASE_URL ?>/public/perfil.php">Perfil</a>
    <a onclick="abrirModalContato()" class="nav-link font-medium hover:text-accent transition-colors cursor-pointer">Contato</a>
  </nav>

       
        <style>
            @media (max-width: 768px)
            {.menu-principal {
                z-index: 10;
            }}
          
        </style>
    </header>
   <div id="modalContato">
  <div class="modal-card">

    <button class="btn-fechar" onclick="fecharModalContato()">&times;</button>

    <!-- Cabeçalho -->
    <div id="m_cabecalho">
      <h2>FALE <span>CONOSCO</span></h2>
      <p>Envie sua dúvida ou sugestão. Respondemos em breve.</p>
      <hr>
    </div>

    <!-- Formulário -->
    <form id="contactFormModal">
      <label>Nome</label>
      <input type="text" id="m_nome" placeholder="Seu nome completo">
      <span id="m_erroNome" class="erro-campo"></span>

      <label>E-mail</label>
      <input type="email" id="m_email" placeholder="seuemail@exemplo.com">
      <span id="m_erroEmail" class="erro-campo"></span>

      <label>Mensagem</label>
      <textarea id="m_mensagem" rows="4" placeholder="Digite sua mensagem..."></textarea>
      <span id="m_erroMensagem" class="erro-campo"></span>

      <button type="submit" class="btn-enviar" id="m_btnEnviar">Enviar Mensagem</button>
    </form>

    <!-- Feedback -->
    <div id="m_feedback">
      <div id="m_feedbackIcone"></div>
      <div id="m_feedbackTitulo"></div>
      <p id="m_feedbackMsg"></p>
      <button class="btn-ok" onclick="fecharModalContato()">OK</button>
    </div>

  </div>
</div>

</head>

  <main class="agenda-container">
  <!-- ETAPA 1 -->
  <div id="etapa1" class="fade">
    <div class="steps">
      <div class="step active"><span class="circle">1</span><span>Serviços</span></div>
      <div class="step"><span class="circle">2</span><span>Profissional</span></div>
      <div class="step"><span class="circle">3</span><span>Data & Hora</span></div>
      <div class="step"><span class="circle">4</span><span>Confirmação</span></div>
    </div>

    <h2 class="titulo">SELECIONE UM <span style="color:#f0c000">SERVIÇO</span></h2>

    <div class="categorias">
      <button class="categoria ativa" data-categoria="cortes">Cortes</button>
      <button class="categoria" data-categoria="sobrancelha">Sobrancelha</button>
      <button class="categoria" data-categoria="barba">Barba</button>
      <button class="categoria" data-categoria="combo">Combo</button>
    </div>

    <div id="listaServicos" class="lista-servicos"></div>

    <div id="resumoServicos" class="resumo-servicos">
      <p><strong>SERVIÇOS SELECIONADOS:</strong></p>
      <div id="listaSelecionados">Nenhum serviço selecionado.</div>
      <p><strong>Total:</strong> R$ <span id="totalValor">0,00</span></p>
    </div>

    <div class="btn-row">
      <button id="btnConfirmarServicos" class="btn-confirmar">CONFIRMAR</button>
    </div>
  </div>

  <!-- ETAPA 2 -->
  <div id="etapa2" class="fade" style="display:none;">
    <div class="steps">
      <div class="step"><span class="circle">1</span><span>Serviços</span></div>
      <div class="step active"><span class="circle">2</span><span>Profissional</span></div>
      <div class="step"><span class="circle">3</span><span>Data & Hora</span></div>
      <div class="step"><span class="circle">4</span><span>Confirmação</span></div>
    </div>

    <h2 class="titulo">SELECIONE O <span style="color:#f0c000">PROFISSIONAL</span></h2>

    <!-- <div id="resumoTopo" class="resumo-servicos"></div> -->

    <div id="listaProfissionais" class="lista-profissionais">
      <img src="<?= BASE_URL ?>img/barber.png" alt="Foto do barbeiro">
    </div>
    

    <div class="btn-row" style="margin-top:18px">
      <button id="btnVoltar" class="btn-voltar">← VOLTAR</button>
      <button id="btnConfirmarProf" class="btn-confirmar">CONFIRMAR</button>
    </div>
  </div>
 <!-- ETAPA 3 (inicialmente oculta) -->
<div id="etapa3" style="display:none;">
  <div class="steps">
    <div class="step"><span class="circle">1</span><span>Serviços</span></div>
    <div class="step"><span class="circle">2</span><span>Profissional</span></div>
    <div class="step active"><span class="circle">3</span><span>Data & Hora</span></div>
    <div class="step"><span class="circle">4</span><span>Confirmação</span></div>
  </div>

  <h2 class="titulo">SELECIONE <span style="color:#f0c000">DATA E HORA</span></h2>

  <div class="data-hora-box">
    <div class="data-section">
      <p><i class="fa-regular fa-calendar"></i> SELECIONE A DATA</p>
      <input type="date" id="dataSelecionada">
      <small>Dica: Escolha um dia útil para mais horários</small>
    </div>

    <div class="horarios-section">
      <p><i class="fa-regular fa-clock"></i> HORÁRIOS DISPONÍVEIS:</p>
      <div class="horarios-grid">
        <button class="hora">09:00</button>
        <button class="hora">10:00</button>
        <button class="hora">11:00</button>
        <button class="hora">13:00</button>
        <button class="hora">14:00</button>
        <button class="hora">15:00</button>
      </div>
    </div>
  </div>

  <div class="btn-row" style="margin-top:18px">
    <button id="btnVoltar2" class="btn-voltar">← VOLTAR</button>
    <button id="btnConfirmarData" class="btn-confirmar">CONFIRMAR</button>
  </div>
</div>
  <!-- ETAPA 4 (inicialmente oculta) -->
 <div class="etapa-confirmacao" style="display:none;">
            <div class="steps">
                <div class="step"><span class="circle">1</span><span>Serviços</span></div>
                <div class="step"><span class="circle">2</span><span>Profissional</span></div>
                <div class="step"><span class="circle">3</span><span>Data & Hora</span></div>
                <div class="step active"><span class="circle">4</span><span>Confirmação</span></div>
            </div>

<h2 class="titulo">CONFIRMAÇÃO DO <span style="color:#f0c000">AGENDAMENTO</span></h2>

            <div class="resumo-completo">
                <div class="resumo-item">
                    <h3>📋 SERVIÇOS SELECIONADOS</h3>
                    <div id="resumoServicosConfirmacao"></div>
                </div>

                <div class="resumo-item">
                    <h3>👨‍💼 PROFISSIONAL</h3>
                    <div id="resumoProfissionalConfirmacao"></div>
                </div>

                <div class="resumo-item">
                    <h3>📅 DATA E HORA</h3>
                    <div id="resumoDataHoraConfirmacao"></div>
                </div>

                <div class="resumo-total">
                    <h3>TOTAL: R$ <span id="totalConfirmacao"><?= $_SESSION['valorFinal']?></span></h3>
                </div>
            </div>

            <div class="btn-row">
                <button id="btnVoltarConfirmacao" class="btn-voltar">← VOLTAR</button>
                <button id="btnFinalizarAgendamento" class="btn-confirmar">FINALIZAR AGENDAMENTO</button>
            </div>
        </div>

        <div id="popupSucesso" style="display:none;">
        <div class="popup-overlay"></div>
        <div class="popup-box">
           <div class="popup-icone">✂️</div>
            <h2>Agendamento Realizado!</h2>
            <p>Seu horário foi confirmado com sucesso.</p>
        <button id="popupBtnOk">OK</button>
  </div>
</div>
</main>

  <script>
document.addEventListener("DOMContentLoaded", () => {
  // --- ELEMENTOS ETAPAS ---
  const etapa1 = document.getElementById("etapa1");
  const etapa2 = document.getElementById("etapa2");
  const etapa3 = document.getElementById("etapa3");
  const etapaConfirmacao = document.querySelector(".etapa-confirmacao");

  // Etapa 1 - Serviços
  const categorias = document.querySelectorAll(".categoria");
  const listaServicos = document.getElementById("listaServicos");
  const resumoServicos = document.getElementById("resumoServicos");
  const listaSelecionados = document.getElementById("listaSelecionados");
  const totalValor = document.getElementById("totalValor");
  const btnConfirmarServicos = document.getElementById("btnConfirmarServicos");

  // Etapa 2 - Profissionais
  const listaContainer = document.getElementById("listaProfissionais");
  const btnConfirmarProf = document.getElementById("btnConfirmarProf");
  const btnVoltar = document.getElementById("btnVoltar");

  // Etapa 3 - Data e Hora
  const inputData = document.getElementById("dataSelecionada");
  const containerHorarios = document.querySelector(".horarios-grid");
  const btnConfirmarData = document.getElementById("btnConfirmarData");
  const btnVoltar2 = document.getElementById("btnVoltar2");

  // Etapa 4 - Confirmação
  const resumoServicosConfirmacao = document.getElementById("resumoServicosConfirmacao");
  const resumoProfissionalConfirmacao = document.getElementById("resumoProfissionalConfirmacao");
  const resumoDataHoraConfirmacao = document.getElementById("resumoDataHoraConfirmacao");
  const totalConfirmacao = document.getElementById("totalConfirmacao");
  const btnVoltarConfirmacao = document.getElementById("btnVoltarConfirmacao");
  const btnFinalizarAgendamento = document.getElementById("btnFinalizarAgendamento");

  // --- DADOS ---
  let categoriaAtiva = document.querySelector(".categoria.ativa")?.dataset.categoria || "cortes";
  const selecionados = {};
  let profissionalSelecionado = null;
  let horarioSelecionado = null;
  let servicosPorCategoria = {};
  let profissionais = [];
  let descontoPlano = 0;

  // --- CARREGAR SERVIÇOS ---
  function carregarServicos() {
    return fetch("../../models/agenda/agendamento/api/Servicos.php")
      .then(res => res.json())
      .then(data => {
        servicosPorCategoria = {
          cortes: data.corte || [],
          sobrancelha: data.sobrancelha || [],
          barba: data.barba || [],
          combo: data.combo || []
        };
        renderizarServicos(categoriaAtiva);
      })
      .catch(err => {
        console.error(err);
        servicosPorCategoria = { cortes: [{ nome: "Corte Clássico", preco: 40 }] };
        renderizarServicos(categoriaAtiva);
      });
  }

  // --- CARREGAR PROFISSIONAIS ---
  function carregarProfissionais() {
    return fetch("../../models/agenda/agendamento/api/Barbeiros.php")
      .then(res => res.json())
      .then(data => {
        profissionais = data;
        renderizarProfissionais();
      })
      .catch(err => {
        console.error(err);
        profissionais = [{ nome_barbeiro: "Rafael Costa", descricao: "2 anos", foto: "<?= BASE_URL ?>adm/img/barber.png" }];
        renderizarProfissionais();
      });
  }

  // --- CARREGAR DESCONTO DO PLANO ---
  function carregarDescontoPlano() {
    return fetch("../../models/agenda/agendamento/api/PlanoAtivo.php")
      .then(res => res.json())
      .then(data => {
        descontoPlano = parseInt(data.desconto) || 0;
        localStorage.setItem("descontoPlano", descontoPlano);
      })
      .catch(err => {
        console.error("Erro ao carregar plano ativo:", err);
        descontoPlano = 0;
        localStorage.setItem("descontoPlano", descontoPlano);
      });
  }

  // --- ETAPA 1 ---
  function renderizarServicos(categoria) {
    listaServicos.innerHTML = "";
    if (!servicosPorCategoria[categoria]?.length) {
      listaServicos.innerHTML = "<p>Nenhum serviço disponível.</p>";
      return;
    }
    servicosPorCategoria[categoria].forEach(servico => {
      const btn = document.createElement("button");
      btn.classList.add("servico");
      btn.dataset.nome = servico.nome;
      btn.dataset.preco = servico.preco;
      btn.dataset.categoria = categoria;
      const precoFinal = servico.preco - ((servico.preco * descontoPlano) / 100);
      btn.innerHTML = `${servico.nome} <span class="preco">R$ ${precoFinal.toFixed(2).replace('.', ',')}</span>`;
      if (selecionados[categoria]?.nome === servico.nome) btn.classList.add("selecionado");
      btn.addEventListener("click", () => selecionarServico(btn));
      listaServicos.appendChild(btn);
    });
  }

  function selecionarServico(btn) {
    const categoria = btn.dataset.categoria;
    const nome = btn.dataset.nome;
    const preco = parseFloat(btn.dataset.preco);
    const precoFinal = preco - ((preco * descontoPlano) / 100);
    const jaSelecionado = selecionados[categoria]?.nome === nome;

    if (jaSelecionado) {
      btn.classList.remove("selecionado");
      delete selecionados[categoria];
    } else {
      document.querySelectorAll(`.servico[data-categoria="${categoria}"]`).forEach(s => s.classList.remove("selecionado"));
      btn.classList.add("selecionado");
      selecionados[categoria] = { nome, preco, precoFinal };
    }
    atualizarResumo();
  }

  function atualizarResumo() {
    const valores = Object.values(selecionados);
    if (valores.length > 0) {
      resumoServicos.style.display = "block";
      listaSelecionados.innerHTML = valores.map(i => `<p>${i.nome} — R$ ${i.precoFinal.toFixed(2).replace('.', ',')}</p>`).join("");
      const total = valores.reduce((acc, i) => acc + i.precoFinal, 0);
      totalValor.textContent = total.toFixed(2).replace('.', ',');
    } else {
      resumoServicos.style.display = "none";
      listaSelecionados.textContent = "Nenhum serviço selecionado.";
      totalValor.textContent = "0,00";
    }
  }

  categorias.forEach(cat => cat.addEventListener("click", () => {
    categorias.forEach(c => c.classList.remove("ativa"));
    cat.classList.add("ativa");
    categoriaAtiva = cat.dataset.categoria;
    renderizarServicos(categoriaAtiva);
  }));

  btnConfirmarServicos.addEventListener("click", () => {
    if (!Object.values(selecionados).length) { alert("Selecione pelo menos um serviço!"); return; }
    localStorage.setItem("servicosSelecionados", JSON.stringify(Object.values(selecionados)));
    etapa1.style.display = "none";
    etapa2.style.display = "block";
  });

  // --- ETAPA 2 ---
  function renderizarProfissionais() {
    listaContainer.innerHTML = "";
    profissionais.forEach((prof, index) => {
      const nome = prof.nome_barbeiro || "Não definido";
      const descricao = prof.descricao || "Descrição não informada";
      const foto = prof.foto ? `<?= BASE_URL ?>adm/img/${prof.foto}` : `<?= BASE_URL ?>adm/img/barber.png`;
      const card = document.createElement("div");
      card.classList.add("prof-card");
      card.innerHTML = `<img src="${foto}" alt="${nome}"><h3>${nome}</h3><p>${descricao}</p>`;
      card.addEventListener("click", () => selecionarProfissional(card, index));
      listaContainer.appendChild(card);
    });
  }

  function selecionarProfissional(card, index) {
    document.querySelectorAll(".prof-card").forEach(c => c.classList.remove("selecionado"));
    card.classList.add("selecionado");
    profissionalSelecionado = profissionais[index];
  }

  btnConfirmarProf.addEventListener("click", () => {
    if (!profissionalSelecionado) { alert("Selecione um profissional!"); return; }
    localStorage.setItem("profissionalSelecionado", JSON.stringify(profissionalSelecionado));
    etapa2.style.display = "none";
    etapa3.style.display = "block";
    document.querySelector(".horarios-section").style.display = "none";
    renderizarHorarios();
  });

  btnVoltar.addEventListener("click", () => {
    etapa2.style.display = "none";
    etapa1.style.display = "block";
  });

  // --- ETAPA 3 ---
  function renderizarHorarios() {
    containerHorarios.innerHTML = "";
    horarioSelecionado = null;

    if (!profissionalSelecionado) { containerHorarios.innerHTML = "<p>Selecione um profissional primeiro.</p>"; return; }
    if (!inputData.value) {
      document.querySelector(".horarios-section").style.display = "none";
      return;
    }

    document.querySelector(".horarios-section").style.display = "block";

    fetch(`../../models/agenda/agendamento/api/Horario.php?idbarbeiro=${profissionalSelecionado.idbarbeiro}&data=${inputData.value}`)
      .then(res => res.json())
      .then(resp => {
        const disponiveis = resp.disponiveis || [];
        if (!disponiveis.length) { containerHorarios.innerHTML = "<p>Nenhum horário disponível.</p>"; return; }
        disponiveis.forEach(hora => {
          const btn = document.createElement("button");
          btn.classList.add("hora");
          btn.dataset.hora = hora;
          btn.textContent = hora;
          btn.addEventListener("click", () => {
            containerHorarios.querySelectorAll(".hora").forEach(h => h.classList.remove("selecionado"));
            btn.classList.add("selecionado");
            horarioSelecionado = hora;
          });
          containerHorarios.appendChild(btn);
        });
      })
      .catch(err => {
        console.error("Erro ao carregar horários:", err);
        containerHorarios.innerHTML = "<p>Erro ao carregar horários.</p>";
      });
  }

  inputData.addEventListener("change", renderizarHorarios);

  btnConfirmarData.addEventListener("click", () => {
    if (!inputData.value || !horarioSelecionado) { alert("Selecione data e horário!"); return; }
    localStorage.setItem("dataSelecionada", inputData.value);
    localStorage.setItem("horarioSelecionado", horarioSelecionado);
    etapa3.style.display = "none";
    etapaConfirmacao.style.display = "block";
    renderizarConfirmacao();
  });

  btnVoltar2.addEventListener("click", () => {
    etapa3.style.display = "none";
    etapa2.style.display = "block";
  });

  // --- ETAPA 4 ---
  function renderizarConfirmacao() {
    const servicos = JSON.parse(localStorage.getItem("servicosSelecionados")) || [];
    const prof = JSON.parse(localStorage.getItem("profissionalSelecionado")) || {};
    const data = localStorage.getItem("dataSelecionada");
    const hora = localStorage.getItem("horarioSelecionado");
    const total = servicos.reduce((acc, i) => acc + i.precoFinal, 0).toFixed(2).replace('.', ',');

    resumoServicosConfirmacao.innerHTML = servicos.map(i => `<p>${i.nome} — R$ ${i.precoFinal.toFixed(2).replace('.', ',')}</p>`).join("");
    resumoProfissionalConfirmacao.textContent = prof.nome_barbeiro || "Não definido";
    resumoDataHoraConfirmacao.textContent = `${data} — ${hora}`;
    totalConfirmacao.textContent = total;
  }

  btnVoltarConfirmacao.addEventListener("click", () => {
    etapaConfirmacao.style.display = "none";
    etapa3.style.display = "block";
  });

  btnFinalizarAgendamento.addEventListener("click", () => {
    const servicos = JSON.parse(localStorage.getItem("servicosSelecionados")) || [];
    const prof = JSON.parse(localStorage.getItem("profissionalSelecionado")) || {};
    const data = localStorage.getItem("dataSelecionada");
    const hora = localStorage.getItem("horarioSelecionado");

    if (!servicos.length || !prof.nome_barbeiro || !data || !hora) {
      alert("Informações incompletas.");
      return;
    }

    const dados = {
      servicos: servicos.map(s => s.nome),
      barbeiro: prof.nome_barbeiro,
      data: data,
      horario: hora,
      plano_ativo: descontoPlano || null
    };

    fetch("../../models/agenda/agendamento/agend.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(dados)
    })
    .then(res => res.json())
    .then(resp => {
      if (resp.sucesso) {
        mostrarPopupSucesso();
      } else {
        alert("Erro: " + (resp.mensagem || "Tente novamente."));
      }
    })
    .catch(err => { console.error(err); alert("Erro ao agendar."); });
  });

  // --- POPUP SUCESSO ---
  function mostrarPopupSucesso() {
    const popup = document.getElementById("popupSucesso");
    popup.style.display = "flex";

    document.getElementById("popupBtnOk").addEventListener("click", () => {
      popup.style.display = "none";
      localStorage.clear();
      window.location.href = "index.php";
    });
  }

  // --- Inicialização ---
  etapa1.style.display = "block";
  etapa2.style.display = "none";
  etapa3.style.display = "none";
  etapaConfirmacao.style.display = "none";

  Promise.all([carregarDescontoPlano(), carregarServicos(), carregarProfissionais()]);
});
</script>


<script src="<?= BASE_URL?>public/assets/script/contato.js"></script>
<script src="<?= BASE_URL?>public/assets/script/menu.js"></script>



</body>
</html>