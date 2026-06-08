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
    return fetch(`${BASE_URL}models/agenda/agendamento/api/Servicos.php`)
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
    return fetch(`${BASE_URL}models/agenda/agendamento/api/Barbeiros.php`)
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
    return fetch(`${BASE_URL}models/agenda/agendamento/api/PlanoAtivo.php`)
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
      const foto = prof.foto? `${BASE_URL}adm/img/${prof.foto}`: `${BASE_URL}adm/img/barber.png`;
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

    fetch(`${BASE_URL}models/agenda/agendamento/api/Horario.php?idbarbeiro=${profissionalSelecionado.idbarbeiro}&data=${inputData.value}`
        
    )
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

    fetch(`${BASE_URL}models/agenda/agendamento/agend.php`,
         {
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