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

    <div id="resumoTopo" class="resumo-servicos"></div>

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
  const btnFinalizarAgendamento = document.getElementById("btnFinalizarAgendamento");

  // --- DADOS ---
  let categoriaAtiva = document.querySelector(".categoria.ativa")?.dataset.categoria || "cortes";
  const selecionados = {};
  let profissionalSelecionado = null;
  let horarioSelecionado = null;
  let servicosPorCategoria = {};
  let profissionais = [];
  let descontoPlano = 0; // desconto em %

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
        servicosPorCategoria = { cortes: [{nome:"Corte Clássico", preco:40}] };
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
        profissionais = [{ nome_barbeiro:"Rafael Costa", descricao:"2 anos", foto:"<?= BASE_URL ?>adm/img/barber.png" }];
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
    const chave = categoria;
    if (!servicosPorCategoria[chave]?.length) {
      listaServicos.innerHTML = "<p>Nenhum serviço disponível.</p>";
      return;
    }
    servicosPorCategoria[chave].forEach(servico => {
      const btn = document.createElement("button");
      btn.classList.add("servico");
      btn.dataset.nome = servico.nome;
      btn.dataset.preco = servico.preco;
      btn.dataset.categoria = chave;
      // aplicar desconto no display
      const precoFinal = servico.preco - ((servico.preco * descontoPlano) / 100);
      btn.innerHTML = `${servico.nome} <span class="preco">R$ ${precoFinal.toFixed(2).replace('.',',')}</span>`;
      if (selecionados[chave]?.nome === servico.nome) btn.classList.add("selecionado");
      btn.addEventListener("click", () => selecionarServico(btn));
      listaServicos.appendChild(btn);
    });
  }

  function selecionarServico(btn) {
    const categoria = btn.dataset.categoria;
    const nome = btn.dataset.nome;
    const preco = parseFloat(btn.dataset.preco);
    const precoFinal = preco - ((preco * descontoPlano)/100);

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
      listaSelecionados.innerHTML = valores.map(i=>{
        return `<p>${i.nome} — R$ ${i.precoFinal.toFixed(2).replace('.',',')}</p>`;
      }).join("");
      const total = valores.reduce((acc,i)=>acc + i.precoFinal,0);
      totalValor.textContent = total.toFixed(2).replace('.',',');
    } else {
      resumoServicos.style.display="none";
      listaSelecionados.textContent="Nenhum serviço selecionado.";
      totalValor.textContent="0,00";
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
    etapa1.style.display = "none"; etapa2.style.display = "block";
  });

  // --- ETAPA 2 ---
  function renderizarProfissionais() {
    listaContainer.innerHTML = "";
    profissionais.forEach((prof,index) => {
      const nome = prof.nome_barbeiro || "Não definido";
      const descricao = prof.descricao || "Descrição não informada";
      const foto = prof.foto ? `<?= BASE_URL ?>adm/img/${prof.foto}` : `<?= BASE_URL ?>adm/img/barber.png`;
      const card = document.createElement("div");
      card.classList.add("prof-card");
      card.innerHTML = `<img src="${foto}" alt="${nome}"><h3>${nome}</h3><p>${descricao}</p>`;
      card.addEventListener("click", () => selecionarProfissional(card,index));
      listaContainer.appendChild(card);
    });
  }

  function selecionarProfissional(card,index){
    document.querySelectorAll(".prof-card").forEach(c=>c.classList.remove("selecionado"));
    card.classList.add("selecionado");
    profissionalSelecionado = profissionais[index];
  }

  btnConfirmarProf.addEventListener("click", () => {
    if (!profissionalSelecionado) { alert("Selecione um profissional!"); return; }
    localStorage.setItem("profissionalSelecionado", JSON.stringify(profissionalSelecionado));
    etapa2.style.display="none";
    etapa3.style.display="block";
    renderizarHorarios();
  });

  btnVoltar.addEventListener("click", () => {
    etapa2.style.display="none"; etapa1.style.display="block";
  });

  // --- ETAPA 3 ---
  function renderizarHorarios() {
    containerHorarios.innerHTML = "";
    horarioSelecionado = null;
    if (!profissionalSelecionado) { containerHorarios.innerHTML="<p>Selecione um profissional primeiro.</p>"; return; }
    if (!inputData.value) { containerHorarios.innerHTML="<p>Selecione uma data.</p>"; return; }

    fetch(`../../models/agenda/agendamento/api/Horario.php?idbarbeiro=${profissionalSelecionado.idbarbeiro}&data=${inputData.value}`)
      .then(res => res.json())
      .then(resp => {
        const disponiveis = resp.disponiveis || [];
        if (!disponiveis.length) { containerHorarios.innerHTML="<p>Nenhum horário disponível.</p>"; return; }

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
        containerHorarios.innerHTML="<p>Erro ao carregar horários.</p>";
      });
  }

  inputData.addEventListener("change", renderizarHorarios);

  btnConfirmarData.addEventListener("click", () => {
    if (!inputData.value || !horarioSelecionado) { alert("Selecione data e horário!"); return; }
    localStorage.setItem("dataSelecionada", inputData.value);
    localStorage.setItem("horarioSelecionado", horarioSelecionado);
    etapa3.style.display="none";
    etapaConfirmacao.style.display="block";
    renderizarConfirmacao();
  });

  btnVoltar2.addEventListener("click", () => {
    etapa3.style.display="none";
    etapa2.style.display="block";
  });

  // --- ETAPA 4 ---
  function renderizarConfirmacao() {
    const servicos = JSON.parse(localStorage.getItem("servicosSelecionados"))||[];
    const prof = JSON.parse(localStorage.getItem("profissionalSelecionado"))||{};
    const data = localStorage.getItem("dataSelecionada");
    const hora = localStorage.getItem("horarioSelecionado");
    const total = servicos.reduce((acc,i)=>acc + i.precoFinal,0).toFixed(2).replace('.',',');

    resumoServicosConfirmacao.innerHTML = servicos.map(i=>`<p>${i.nome} — R$ ${i.precoFinal.toFixed(2).replace('.',',')}</p>`).join("");
    resumoProfissionalConfirmacao.textContent = prof.nome_barbeiro || "Não definido";
    resumoDataHoraConfirmacao.textContent = `${data} — ${hora}`;
    totalConfirmacao.textContent = total;
  }

  btnFinalizarAgendamento.addEventListener("click", () => {
    const servicos = JSON.parse(localStorage.getItem("servicosSelecionados"))||[];
    const prof = JSON.parse(localStorage.getItem("profissionalSelecionado"))||{};
    const data = localStorage.getItem("dataSelecionada");
    const hora = localStorage.getItem("horarioSelecionado");

    if (!servicos.length || !prof.nome_barbeiro || !data || !hora) {
      alert("Informações incompletas.");
      return;
    }

    // Enviar nomes para o backend
    const dados = {
      servicos: servicos.map(s=>s.nome),
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
        alert("Agendamento realizado!");
        localStorage.clear();
        window.location.href = "index.php";
      } else {
        alert("Erro: " + (resp.mensagem || "Tente novamente."));
      }
    })
    .catch(err => { console.error(err); alert("Erro ao agendar."); });
  });

  // --- Inicialização ---
  etapa1.style.display="block";
  etapa2.style.display="none";
  etapa3.style.display="none";
  etapaConfirmacao.style.display="none";

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
}

.agenda-container {
  width: 80%;
  max-width: 900px;
  margin: 60px auto;
  text-align: center;
}

/* ====== Etapas ====== */
 .steps {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 50px;
  margin-bottom: 40px;
  transform: scale(1.05); /* 🔽 diminui 5% o tamanho total */
  transform-origin: center; /* mantém centralizado */
}



.step {
  display: flex;
  align-items: center;   /* Alinha círculo e texto no centro */
  flex-direction: row;   /* Fica lado a lado */
  color: #999;
  font-size: 0.9rem;
  position: relative;
  gap: 8px;              /* Espaço entre o círculo e o texto */
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
}

.categoria {
  background-color: #222;
  border: none;
  color: #fff;
  padding: 8px 18px;
  border-radius: 20px;
  cursor: pointer;
  transition: 0.3s;
  font-weight: 500;
}

.categoria.ativa {
  background-color: #f0c000;
  color: #000;
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
  transition: 0.3s;
  margin-bottom: 15px;
  width: 100%;
}

.servico:hover {
  background-color: #333;
}

.servico.selecionado {
  background-color: #f0c000;
  color: #000;
}

/* ====== Resumo ====== */
.resumo-servicos {
  background-color: #1c1c1c;
  padding: 20px;
  border-radius: 12px;
  text-align: left;
  font-size: 0.95rem;
  margin-bottom: 30px;
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
  transition: 0.3s;
}

.btn-confirmar:hover {
  background-color: #9a9a3d;
}

/* ====== Responsividade ====== */
@media (max-width: 768px) {
  .agenda-container {
    width: 95%;
  }
  .steps {
    gap: 20px;
    font-size: 0.8rem;
  }
  .categoria {
    padding: 6px 12px;
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
  transition: 0.3s;
  cursor: pointer;
  margin-bottom: 20px;
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
  transition: 0.3s;
}

.btn-voltar:hover {
  background-color: #777;
}
/* ----------------------------------
Etapa 3 - Data e Hora
---------------------------------- */
/* ETAPA 3 - DATA E HORA */
.data-hora-box {
  background: #1a1a1a;
  border-radius: 15px;
  padding: 30px;
  margin: 25px 0;
  border: 2px solid #2a2a2a;
  margin-bottom: 50px;
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
  gap: 10px;
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
  width: 96%;
  margin-bottom: 10px;
  font-family: inherit;
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
  transition: all 0.3s ease;
  text-align: center;
  margin-top: 0px;
  width: 100%;
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

/* Estilização do input date para navegadores modernos */
#dataSelecionada::-webkit-calendar-picker-indicator {
  filter: invert(1);
  cursor: pointer;
  padding: 5px;
}

#dataSelecionada::-webkit-datetime-edit-fields-wrapper {
  color: white;
}

#dataSelecionada::-webkit-datetime-edit-text {
  color: white;
}

#dataSelecionada::-webkit-datetime-edit-month-field,
#dataSelecionada::-webkit-datetime-edit-day-field,
#dataSelecionada::-webkit-datetime-edit-year-field {
  color: white;
}

/* Para Firefox */
#dataSelecionada {
  color-scheme: dark;
}

/* Responsividade */
@media (max-width: 768px) {
  .data-hora-box {
    padding: 20px;
    margin: 20px 0;
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
    grid-template-columns: 1fr;
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

/* Caixa geral do resumo */
.resumo-completo {
  background: #1a1a1a;
  border-radius: 20px;
  padding: 30px;
  max-width: 600px;
  margin: 0 auto 40px auto;
  text-align: left;
  border: 2px solid #2a2a2a;
  box-shadow: 0 0 10px rgba(0,0,0,0.3);
}

.resumo-item {
  margin-bottom: 25px;
  border-bottom: 1px solid #333;
  /* padding-bottom: 15px; */
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
  /* font-size: 20px; */
  font-weight: bold;
  
}

/* ===== BOTÕES ===== */
.btn-row {
  display: flex;
  justify-content: center;
  gap: 20px;
}

.btn-voltar, .btn-confirmar {
  font-weight: bold;
  border: none;
  border-radius: 50px;
  padding: 12px 35px;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  font-size: 15px;
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

/* ===== RESPONSIVIDADE ===== */
@media (max-width: 768px) {
  .resumo-completo {
    padding: 20px;
    width: 90%;
  }

  .btn-row {
    flex-direction: column;
    gap: 15px;
  }

  .btn-voltar, .btn-confirmar {
    width: 100%;
  }
}
.resumo-item h3{
    color: white;
}

</style>