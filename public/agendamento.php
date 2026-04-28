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
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/profissionais.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/resumo.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/responsivo.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/contact.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>adm/img">
    <style>
        
    </style>
    
</head>
<body>
    <!-- Header -->
    <header>
    <img src="<?= BASE_URL?>/img/SAVE_20241028_185834.jpg" alt="Logo TimeAgend">
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
    <a href="#" onclick="openContact()">Contato</a>
  </nav>

       
        <style>
            @media (max-width: 768px)
            {.menu-principal {
                z-index: 10;
            }}
        </style>
    </header>
    <div class="modal" id="contactModal">
    <div class="modal-content-1">

    <?php if (!empty($successMessage) || !empty($errorMessage)): ?>
    <div id="mensagemModal" class="custom-modal" style="display: block;">
        <div class="custom-modal-content">
            <p>
                <?php 
                    echo !empty($successMessage) 
                        ? htmlspecialchars($successMessage) 
                        : htmlspecialchars($errorMessage); 
                ?>
            </p>
            <button onclick="fecharModal()">OK</button>
        </div>
    </div>
    <?php endif; ?>


        <span class="close" onclick="closeContactModal()">&times;</span>
    
        <div id="contato" class="contato-container">
      
            <form class="form-email" method="POST">
            <h3 class="fale-conosco">Fale <span class="conosco">Conosco</span></h3>
                <label for="user_name">Nome:</label>
                <input type="text" name="user_name" id="user_name" required>
                <label for="user_email">E-mail:</label>
                <input type="email" name="user_email" id="user_email" required>
                <label for="mensagem">Mensagem:</label>
                <textarea name="mensagem" id="mensagem" required></textarea>
                <button type="submit" name="sendEmail" data-button>Enviar</button>
            </form>
        </div>
    </div>
</div>


<style>
  body{background:#000;color:#fff;font-family:Arial,Helvetica,sans-serif;margin:0;padding:20px}
  .agenda-container{max-width:900px;margin:20px auto}
  
  .titulo{font-size:1.4rem;text-align:center;margin:10px 0}
  .categorias{display:flex;justify-content:center;gap:10px;margin:15px 0}
  .categoria{background:#222;color:#fff;padding:8px 14px;border-radius:16px;border:none;cursor:pointer}
  .categoria.ativa{background:#f0c000;color:#000}
  .lista-servicos{display:flex;flex-direction:column;gap:10px;margin:10px 0}
  .servico{background:#222;color:#fff;border:none;border-radius:12px;padding:12px 16px;text-align:left;display:flex;justify-content:space-between;cursor:pointer}
  .servico.selecionado{background:#f0c000;color:#000}
  .resumo-servicos{background:#1c1c1c;padding:12px;border-radius:10px;margin-top:12px}
  .btn-row{display:flex;gap:12px;justify-content:center;margin-top:16px}
  .btn-confirmar,.btn-voltar{padding:10px 26px;border-radius:26px;border:none;cursor:pointer}
  .btn-confirmar{background:#7c7c2a;color:#fff}
  .btn-voltar{background:#666;color:#fff}
  /* etapa 2 */
  .lista-profissionais{display:flex;flex-wrap:wrap;gap:18px;justify-content:center;margin-top:18px}
  .prof-card{width:200px;background:#1a1a1a;padding:16px;border-radius:16px;text-align:center;cursor:pointer}
  .prof-card img{width:80px;height:80px;border-radius:50%;object-fit:cover;margin-bottom:10px}
  .prof-card.selecionado{background:#f0c000;color:#000}
  /* simples transição */
  .fade{transition:opacity .22s ease;opacity:1}
  .hidden{display:none;opacity:0}
</style>
</head>
<body>
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


<style>
  
    .preco{
        margin-right: 30px;
    }
/* ====== Estilo base ====== */
body {
  background-color: #000;
  color: #fff;
  /* font-family: 'Poppins', sans-serif; */
  margin: 0;
  padding: 0;
  /* ✅ MELHORIA: Evita scroll horizontal em mobile */
  overflow-x: hidden;
}

.agenda-container {
  width: 80%;
  max-width: 900px;
  margin: 60px auto;
  text-align: center;
  /* ✅ MELHORIA: Caixa não ultrapassa a tela */
  box-sizing: border-box;
}

/* ====== Etapas ====== */
.steps {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 50px;
  margin-bottom: 40px;
  transform: scale(1.05);
  transform-origin: center;
  /* ✅ MELHORIA: Evita quebra em telas pequenas */
  flex-wrap: nowrap;
  overflow-x: auto;
  padding-bottom: 4px;
}

.step {
  display: flex;
  align-items: center;
  flex-direction: row;
  color: #999;
  font-size: 0.9rem;
  position: relative;
  gap: 8px;
  /* ✅ MELHORIA: Não encolhe em telas pequenas */
  flex-shrink: 0;
}

.circle {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background-color: #333;
  color: #fff;
  display: flex;
  justify-content: center;
  align-items: center;
  font-weight: bold;
  transition: 0.3s;
}

/* Círculo ativo (dourado) */
.step.active .circle {
  background-color: #f0c000;
  color: #000;
}

/* Linha entre os passos */
.step::after {
  content: "";
  position: absolute;
  top: 50%;
  right: -40px;
  width: 35px;
  height: 2px;
  background: #555;
  transform: translateY(-50%);
}

.step:last-child::after {
  display: none;
}

.step.active {
  color: #f0c000;
  font-weight: bold;
}

/* ====== Título ====== */
.titulo {
  font-size: 1.8rem;
  letter-spacing: 1px;
  margin-bottom: 40px;
  color: white;
  margin-top: 60px;
  /* ✅ MELHORIA: Quebra linha em telas muito pequenas */
  word-break: break-word;
}

.destaque {
  color: #f0c000;
}

/* ====== Categorias ====== */
.categorias {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-bottom: 60px;
  /* ✅ MELHORIA: Quebra linha quando não cabe */
  /* flex-wrap: wrap; */
}

.categoria {
  background-color: #222;
  border: none;
  color: #fff;
  /* padding: 8px 18px; */
  border-radius: 20px;
  cursor: pointer;
  transition: background-color 0.3s, transform 0.15s;
  font-weight: 500;
  
  white-space: nowrap;
}

.categoria.ativa {
  background-color: #f0c000;
  color: #000;
}

/* ✅ MELHORIA: Leve feedback visual ao clicar */
.categoria:active {
  transform: scale(0.96);
}

/* ====== Lista de serviços ====== */
.lista-servicos {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 25px;
}

.servico {
  background-color: #222;
  color: #fff;
  border: none;
  border-radius: 20px;
  padding: 14px 34px;
  text-align: left;
  font-size: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  cursor: pointer;
  transition: background-color 0.3s, transform 0.15s;
  margin-bottom: 15px;
  width: 98%;
  /* ✅ MELHORIA: Garante que padding não estoure a largura */
  box-sizing: border-box;
}

.servico:hover {
  background-color: #333;
}

/* ✅ MELHORIA: Leve feedback visual ao clicar */
.servico:active {
  transform: scale(0.98);
}

.servico.selecionado {
  background-color: #f0c000;
  color: #000;
}

/* ====== Resumo ====== 
   ✅ CORREÇÃO PRINCIPAL: Oculto por padrão, aparece só após selecionar serviço
   O JS já controla o display, mas adicionamos display:none inicial aqui
   para garantir que não aparece antes de qualquer seleção.
*/
.resumo-servicos {
  background-color: #1c1c1c;
  padding: 20px;
  border-radius: 12px;
  text-align: left;
  font-size: 0.95rem;
  margin-bottom: 30px;
  /* ✅ OCULTO POR PADRÃO */
  display: none;
  /* ✅ MELHORIA: Transição suave ao aparecer */
  animation: fadeInUp 0.3s ease;
}

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(8px); }
  to   { opacity: 1; transform: translateY(0); }
}

#listaSelecionados {
  margin: 10px 0;
}

/* ====== Botão Confirmar ====== */
.btn-confirmar {
  background-color: #7c7c2a;
  color: #fff;
  border: none;
  border-radius: 30px;
  padding: 12px 40px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s, transform 0.15s;
  /* ✅ MELHORIA: Mínimo de largura legível */
  min-width: 160px;
}

.btn-confirmar:hover {
  background-color: #9a9a3d;
}

/* ✅ MELHORIA: Responsividade ====== */
@media (max-width: 768px) {
  header img{
    margin-left: 0;
    position: relative;
    right: 40px;
  }
  .agenda-container {
    width: 95%;
    margin: 30px auto;
    padding: 0 4px;
  }

  /* Steps menores: esconde texto, mostra só número */
  .steps {
    gap: 12px;
    transform: scale(1);
    margin-bottom: 28px;
  }

  .step span:last-child {
    /* ✅ Esconde o label de texto em mobile, mantém só o círculo */
    display: none;
  }

  .step::after {
    right: -16px;
    width: 14px;
  }

  .titulo {
    font-size: 1.3rem;
    margin-top: 30px;
    margin-bottom: 24px;
  }

  .categorias {
    gap: 0;
    margin-top: 40px;
    /* margin-bottom: 30px; */
  }

  .categoria {
    /* padding: 7px 13px; */
    font-size: 0.88rem;
    margin-bottom: -10px;
  }

  .servico {
    padding: 12px 18px;
    font-size: 0.93rem;
    border-radius: 14px;
    /* margin-bottom: 8px; */
  }

  .preco {
    margin-right: 0;
    white-space: nowrap;
    font-size: 0.9rem;
  }

  .btn-confirmar,
  .btn-voltar {
    padding: 11px 24px;
    font-size: 0.9rem;
    min-width: 120px;
  }
}

@media (max-width: 480px) {
  .btn-row {
    flex-direction: column;
    align-items: center;
    gap: 10px;
  }

  .btn-confirmar,
  .btn-voltar {
    width: 100%;
    max-width: 320px;
  }
}

/* ---------------------------------- */
/* etapa 2 - Profissionais */
/* ---------------------------------- */
.lista-profissionais {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 30px;
  margin: 40px 0;
}

.prof-card {
  background-color: #1a1a1a;
  border-radius: 20px;
  padding: 25px;
  text-align: center;
  width: 220px;
  transition: background-color 0.3s, transform 0.2s;
  cursor: pointer;
  margin-bottom: 20px;
  /* ✅ MELHORIA: Não ultrapassa a tela em mobile */
  box-sizing: border-box;
}

.prof-card img {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
  margin-bottom: 15px;
}

.prof-card h3 {
  margin: 5px 0;
  color: #fff;
}

.prof-card p {
  color: #aaa;
  font-size: 0.9rem;
}

.prof-card .estrelas {
  margin-top: 10px;
  color: gold;
}

.prof-card:hover {
  background-color: #333;
  transform: scale(1.05);
}

.prof-card.selecionado {
  background-color: #f0c000;
  color: #000;
}

.prof-card.selecionado h3,
.prof-card.selecionado p {
  color: #000;
}

/* ✅ MELHORIA: Cards de profissional full-width em mobile */
@media (max-width: 520px) {
  .prof-card {
    width: 100%;
    max-width: 300px;
  }
}

.botoes {
  display: flex;
  justify-content: center;
  gap: 20px;
}

.btn-voltar {
  background-color: #666;
  color: #fff;
  border: none;
  border-radius: 30px;
  padding: 12px 40px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s, transform 0.15s;
  min-width: 160px;
}

.btn-voltar:hover {
  background-color: #777;
}

/* ----------------------------------
Etapa 3 - Data e Hora
---------------------------------- */
.data-hora-box {
  background: #1a1a1a;
  border-radius: 15px;
  padding: 30px;
  margin: 25px 0;
  border: 2px solid #2a2a2a;
  margin-bottom: 50px;
  /* ✅ MELHORIA: Padding não estoura em mobile */
  box-sizing: border-box;
}

.data-section {
  margin-bottom: 35px;
  padding-bottom: 25px;
  border-bottom: 1px solid #333;
}

.data-section p {
  color: #f0c000;
  font-weight: bold;
  font-size: 16px;
  margin-bottom: 15px;
  display: flex;
  align-items: center;
}

.data-section p i {
  font-size: 18px;
}

#dataSelecionada {
  background: #2a2a2a;
  border: 2px solid #444;
  border-radius: 8px;
  color: white;
  padding: 12px 15px;
  font-size: 16px;
  /* ✅ MELHORIA: 100% em vez de 96% para alinhar melhor */
  width: 100%;
  margin-bottom: 10px;
  font-family: inherit;
  box-sizing: border-box;
}

#dataSelecionada:focus {
  border-color: #f0c000;
  outline: none;
}

.data-section small {
  color: #888;
  font-size: 13px;
  font-style: italic;
}

.horarios-section p {
  color: #f0c000;
  font-weight: bold;
  font-size: 16px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.horarios-section p i {
  font-size: 18px;
}

.horarios-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
}

.hora {
  background: #2a2a2a;
  border: 2px solid #444;
  border-radius: 8px;
  color: white;
  padding: 15px 10px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: border-color 0.25s, background-color 0.25s, transform 0.15s;
  text-align: center;
  margin-top: 0px;
  width: 100%;
  box-sizing: border-box;
}

.hora:hover {
  border-color: #f0c000;
  background: #333;
  transform: translateY(-2px);
}

.hora.selecionado {
  background: #f0c000;
  border-color: #f0c000;
  color: #000;
  transform: scale(1.05);
}

/* Estilização do input date */
#dataSelecionada::-webkit-calendar-picker-indicator {
  filter: invert(1);
  cursor: pointer;
  padding: 5px;
}

#dataSelecionada::-webkit-datetime-edit-fields-wrapper { color: white; }
#dataSelecionada::-webkit-datetime-edit-text { color: white; }
#dataSelecionada::-webkit-datetime-edit-month-field,
#dataSelecionada::-webkit-datetime-edit-day-field,
#dataSelecionada::-webkit-datetime-edit-year-field { color: white; }

/* #dataSelecionada { color-scheme: dark; } */

/* Responsividade etapa 3 */
@media (max-width: 768px) {
  .data-hora-box {
    padding: 20px;
    
  }
  
  .horarios-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
  }
  
  .hora {
    padding: 12px 8px;
    font-size: 14px;
  }
}

@media (max-width: 480px) {
  .horarios-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .data-section p,
  .horarios-section p {
    font-size: 14px;
  }
}

/* ===== ETAPA 4 - CONFIRMAÇÃO ===== */
.etapa-confirmacao {
  text-align: center;
  color: white;
  margin-top: 40px;
}

.etapa-confirmacao .titulo {
  font-size: 28px;
  font-weight: bold;
  margin-bottom: 40px;
  text-transform: uppercase;
}

.resumo-completo {
  background: #1a1a1a;
  border-radius: 20px;
  padding: 30px;
  max-width: 600px;
  margin: 0 auto 40px auto;
  text-align: left;
  border: 2px solid #2a2a2a;
  box-shadow: 0 0 10px rgba(0,0,0,0.3);
  /* ✅ MELHORIA */
  box-sizing: border-box;
}

.resumo-item {
  margin-bottom: 25px;
  border-bottom: 1px solid #333;
}

.resumo-item:last-child {
  border-bottom: none;
}

.resumo-item h3 {
  color: #f0c000;
  font-size: 18px;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.resumo-item div {
  color: #ddd;
  font-size: 15px;
  line-height: 1.6;
}

.resumo-total {
  text-align: center;
  background: #2a2a2a;
  padding: 1px;
  border-radius: 12px;
  font-weight: bold;
}

/* ===== BOTÕES GERAIS ===== */
.btn-row {
  display: flex;
  justify-content: center;
  gap: 20px;
  /* ✅ MELHORIA: Quebra em telas muito pequenas */
  flex-wrap: wrap;
}

.btn-voltar, .btn-confirmar {
  font-weight: bold;
  border: none;
  border-radius: 50px;
  padding: 12px 35px;
  cursor: pointer;
  transition: background-color 0.3s, transform 0.15s, color 0.3s;
  text-transform: uppercase;
  font-size: 15px;
  /* ✅ MELHORIA: Evita texto cortado em mobile */
  white-space: nowrap;
}

.btn-voltar {
  background: #444;
  color: #fff;
  
}

.btn-voltar:hover {
  background: #555;
  transform: translateY(-2px);
}

.btn-confirmar {
  background: #99a532;
  color: #fff;
}

.btn-confirmar:hover {
  background: #b8c94a;
  color: #000;
  transform: translateY(-2px);
}

/* ===== RESPONSIVIDADE ETAPA 4 ===== */
@media (max-width: 768px) {
  .resumo-completo {
    padding: 20px;
    width: 100%;
  }

  .btn-row {
    flex-direction: column;
    gap: 15px;
    align-items: center;
  }
  .btn-voltar{
    margin-bottom: 12px;
  }

  .btn-voltar, .btn-confirmar {
    width: 100%;
    max-width: 320px;
    text-align: center;
  }
}

.resumo-item h3{
    color: white;
}
/* // ✅ MELHORIA: Estilização do popup de sucesso */
#popupSucesso {
  position: fixed;
  inset: 0;
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
}

.popup-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.75);
  backdrop-filter: blur(3px);
}

.popup-box {
  position: relative;
  z-index: 1;
  background: #1a1a1a;
  /* border: 2px solid #f0c000; */
  border-radius: 20px;
  padding: 40px 20px;
  text-align: center;
  animation: popupEntrar 0.35s ease;
  max-width: 340px;
  width: 80%;
}

@keyframes popupEntrar {
  from { opacity: 0; transform: scale(0.85); }
  to   { opacity: 1; transform: scale(1); }
}

.popup-icone {
  font-size: 3rem;
  margin-bottom: 16px;
}

.popup-box h2 {
  color: #f0c000;
  font-size: 1.4rem;
  margin: 0 0 10px;
}

.popup-box p {
  color: #ccc;
  font-size: 0.8rem;
  margin: 0 0 50px;
}

#popupBtnOk {
  background: #99a532;
  color: #fff;
  border: none;
  border-radius: 30px;
  padding: 11px 40px;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.3s, transform 0.15s;
  display: inline-block;
}

#popupBtnOk:hover {
  background: #b8c94a;
  color: #000;
  transform: translateY(-2px);
}
</style>


<script src="<?= BASE_URL?>/public/assets/script/menu.js"></script>

</body>
</html>