<?php
/* ═══════════════════════════════════════════════════════════════
   CONFIGURAÇÃO DE DISPONIBILIDADE DOS BARBEIROS
   Sistema de Agendamento — AdminHub
   ═══════════════════════════════════════════════════════════════ */

$barbeiros = [
    ['id' => 1, 'nome' => 'Carlos Silva',  'iniciais' => 'CS', 'cor' => '#3b82f6'],
    ['id' => 2, 'nome' => 'Rafael Mendes', 'iniciais' => 'RM', 'cor' => '#f59e0b'],
    ['id' => 3, 'nome' => 'Diego Costa',   'iniciais' => 'DC', 'cor' => '#10b981'],
];

$mensagem = '';
$erro     = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /*
     * TODO: processar $dados e salvar no banco de dados.
     * Estrutura esperada por barbeiro:
     *   entrada, saida, intervalo
     *   pausa_ativa, pausa_ini, pausa_fim
     *   weekly_json   → JSON com dias semanais: [0,1,2,3,4,5]
     *   excecoes_json → JSON com exceções: { "2026-05-20": { mode, entrada, saida, intervalo } }
     */
    $dados = [];
    if (!empty($_POST['b'])) {
        foreach ($_POST['b'] as $bid => $cfg) {
            $dados[$bid] = [
                'entrada'      => $cfg['entrada']      ?? '09:00',
                'saida'        => $cfg['saida']        ?? '18:00',
                'intervalo'    => $cfg['intervalo']    ?? '30',
                'pausa_ativa'  => isset($cfg['pausa_ativa']) ? 1 : 0,
                'pausa_ini'    => $cfg['pausa_ini']    ?? '12:00',
                'pausa_fim'    => $cfg['pausa_fim']    ?? '13:00',
                'weekly_json'  => $cfg['weekly_json']  ?? '[]',
                'excecoes_json'=> $cfg['excecoes_json']?? '{}',
            ];
        }
    }
    $mensagem = 'Configurações salvas com sucesso!';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Disponibilidade — BarberAdmin</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
<style>
/* ── Reset & Root ─────────────────────────────── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

:root{
  --blue:#3b82f6;
  --blue-h:#2563eb;
  --blue-s:#dbeafe;
  --blue-m:#93c5fd;
  --amber:#f59e0b;
  --amber-s:#fef3c7;
  --green:#10b981;
  --green-s:#d1fae5;
  --red:#ef4444;
  --red-s:#fee2e2;
  --slate:#0f172a;
  --slate2:#1e293b;
  --text:#1e293b;
  --sub:#64748b;
  --muted:#94a3b8;
  --border:#e2e8f0;
  --bg:#f1f5f9;
  --white:#ffffff;
  --radius-sm:8px;
  --radius:14px;
  --radius-lg:20px;
  --shadow-sm:0 1px 2px rgba(0,0,0,.05);
  --shadow:0 1px 3px rgba(0,0,0,.06),0 4px 20px rgba(0,0,0,.06);
  --shadow-md:0 4px 6px rgba(0,0,0,.07),0 10px 30px rgba(0,0,0,.08);
}

body{
  font-family:'DM Sans',sans-serif;
  background:var(--bg);
  color:var(--text);
  min-height:100vh;
  padding:32px 20px 80px;
}

/* ── Page ─────────────────────────────────────── */
.page{max-width:1140px;margin:0 auto;}

.breadcrumb{
  display:flex;align-items:center;gap:6px;
  font-size:12px;font-weight:600;color:var(--muted);
  letter-spacing:.3px;margin-bottom:12px;
}
.breadcrumb a{color:var(--blue);text-decoration:none;}
.breadcrumb span{font-size:10px;}

.page-header{
  display:flex;align-items:flex-start;justify-content:space-between;
  flex-wrap:wrap;gap:12px;margin-bottom:28px;
}
.page-header h1{
  
  font-size:26px;font-weight:800;
  letter-spacing:-.5px;color:var(--slate);
}
.page-header p{font-size:13px;color:var(--sub);margin-top:4px;line-height:1.6;}

/* ── Toast ────────────────────────────────────── */
.toast{
  display:flex;align-items:center;gap:10px;
  background:#f0fdf4;border:1.5px solid #86efac;
  color:#15803d;padding:13px 18px;border-radius:10px;
  font-size:13.5px;font-weight:600;margin-bottom:22px;
  animation:slideDown .3s ease;
}
@keyframes slideDown{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:none}}
.toast svg{flex-shrink:0;}

/* ── Barber Tabs ──────────────────────────────── */
.barber-tabs{
  display:flex;gap:8px;flex-wrap:wrap;margin-bottom:24px;
}
.btab{
  display:flex;align-items:center;gap:9px;
  padding:9px 18px;border-radius:50px;
  border:2px solid var(--border);
  background:var(--white);cursor:pointer;
  font-family:inherit;font-size:13.5px;font-weight:600;
  color:var(--sub);transition:all .18s;
  box-shadow:var(--shadow-sm);
}
.btab:hover{border-color:var(--blue);color:var(--blue);}
.btab.active{
  border-color:transparent;
  background:var(--slate2);color:#fff;
  box-shadow:0 4px 16px rgba(15,23,42,.25);
}
.btab.active .bav{background:rgba(255,255,255,.15);color:#fff;}
.bav{
  width:26px;height:26px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:9.5px;font-weight:800;
  flex-shrink:0;
}

/* ── Grid Layout ──────────────────────────────── */
.grid{
  display:grid;
  grid-template-columns:1fr 400px;
  gap:16px;align-items:start;
}
@media(max-width:900px){.grid{grid-template-columns:1fr;}}

/* ── Card ─────────────────────────────────────── */
.card{
  background:var(--white);border-radius:var(--radius);
  padding:22px;box-shadow:var(--shadow);
  border:1px solid var(--border);
}
.card+.card{margin-top:14px;}

.card-head{display:flex;align-items:center;gap:12px;margin-bottom:20px;}
.c-icon{
  width:38px;height:38px;border-radius:10px;
  display:flex;align-items:center;justify-content:center;
  font-size:18px;flex-shrink:0;
  background:var(--bg);
}
.c-title{font-size:14px;font-weight:700;letter-spacing:-.2px;}
.c-sub{font-size:11.5px;color:var(--sub);margin-top:2px;}

/* ── Form Fields ──────────────────────────────── */
.s-label{
  font-size:10px;font-weight:700;letter-spacing:1.5px;
  text-transform:uppercase;color:var(--muted);margin-bottom:10px;
}
.row2{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;}
.field{display:flex;flex-direction:column;gap:5px;}
.field label{font-size:11.5px;font-weight:700;color:#475569;}
.field input,.field select{
  border:1.5px solid var(--border);border-radius:var(--radius-sm);
  padding:9px 12px;font-family:inherit;font-size:13.5px;
  font-weight:500;color:var(--text);background:#f8fafc;
  outline:none;transition:all .15s;width:100%;
}
.field input:focus,.field select:focus{
  border-color:var(--blue);background:var(--white);
  box-shadow:0 0 0 3px rgba(59,130,246,.12);
}
.divider{border:none;border-top:1px solid var(--border);margin:16px 0;}

/* ── Switch ───────────────────────────────────── */
.switch-row{
  display:flex;align-items:center;
  justify-content:space-between;margin-bottom:14px;
}
.sw-info .sw-label{font-size:13.5px;font-weight:700;}
.sw-info .sw-sub{font-size:11.5px;color:var(--sub);margin-top:2px;}
.switch{position:relative;display:inline-block;width:44px;height:24px;}
.switch input{display:none;}
.switch .sl{
  position:absolute;inset:0;background:#cbd5e1;
  border-radius:50px;cursor:pointer;transition:background .2s;
}
.switch .sl::after{
  content:'';position:absolute;
  width:18px;height:18px;background:#fff;
  border-radius:50%;top:3px;left:3px;
  transition:transform .2s;
  box-shadow:0 1px 4px rgba(0,0,0,.2);
}
.switch input:checked+.sl{background:var(--blue);}
.switch input:checked+.sl::after{transform:translateX(20px);}

/* ── Expandable ───────────────────────────────── */
.expand-body{
  overflow:hidden;max-height:0;opacity:0;
  transition:max-height .3s,opacity .3s;
}
.expand-body.open{opacity:1;}

/* ── Weekly Days ──────────────────────────────── */
.week-grid{
  display:grid;grid-template-columns:repeat(7,1fr);
  gap:6px;margin-bottom:4px;
}
.wday-btn{
  display:flex;flex-direction:column;align-items:center;
  gap:4px;padding:10px 4px;border-radius:10px;
  border:1.5px solid var(--border);background:var(--bg);
  cursor:pointer;font-family:inherit;transition:all .18s;
}
.wday-btn:hover{border-color:var(--blue);background:var(--blue-s);}
.wday-btn.active{
  border-color:var(--blue);background:var(--blue);
  box-shadow:0 3px 10px rgba(59,130,246,.3);
}
.wday-name{font-size:10px;font-weight:700;color:var(--sub);letter-spacing:.5px;}
.wday-letter{font-size:13px;font-weight:800;color:var(--text);}
.wday-btn.active .wday-name,
.wday-btn.active .wday-letter{color:#fff;}

.week-hint{font-size:11.5px;color:var(--muted);margin-top:10px;line-height:1.6;}

/* ── Preview ──────────────────────────────────── */
.preview-box{
  background:#f8fafc;border:1.5px solid var(--border);
  border-radius:10px;padding:14px;min-height:56px;
}
.pv-label{
  font-size:10px;font-weight:700;letter-spacing:1px;
  text-transform:uppercase;color:var(--muted);margin-bottom:10px;
}
.chips{display:flex;flex-wrap:wrap;gap:5px;}
.chip{
  padding:4px 10px;border-radius:20px;
  font-size:11.5px;font-weight:700;
  background:var(--blue-s);border:1.5px solid var(--blue-m);
  color:var(--blue);
}
.chip.blocked{
  background:#fef2f2;border-color:#fca5a5;
  color:#dc2626;text-decoration:line-through;opacity:.6;
}
.chip-empty{font-size:12.5px;color:var(--muted);}

/* ── Calendar ─────────────────────────────────── */
.cal-nav{
  display:flex;align-items:center;justify-content:space-between;
  margin-bottom:14px;
}
.cal-month{font-size:15px;font-weight:800;letter-spacing:-.3px;}
.cal-arrows{display:flex;gap:4px;}
.cal-btn{
  width:30px;height:30px;border-radius:7px;border:none;
  background:var(--bg);cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  font-size:14px;color:var(--sub);transition:all .15s;
}
.cal-btn:hover{background:var(--blue-s);color:var(--blue);}
.cal-wds{
  display:grid;grid-template-columns:repeat(7,1fr);
  gap:2px;margin-bottom:4px;
}
.cal-wd{
  text-align:center;font-size:10px;font-weight:700;
  color:var(--muted);padding:3px 0;letter-spacing:.5px;
}
.cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:2px;}
.cal-day{
  aspect-ratio:1;border-radius:7px;border:none;
  background:transparent;cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  flex-direction:column;gap:2px;
  font-size:12.5px;font-weight:600;color:var(--text);
  transition:all .15s;font-family:inherit;position:relative;
}
.cal-day:hover:not(.empty):not(.past){background:var(--blue-s);color:var(--blue);}
.cal-day.empty,.cal-day.past{cursor:default;pointer-events:none;}
.cal-day.past{color:var(--muted);opacity:.35;}
.cal-day.today{border:2px solid var(--blue);color:var(--blue);}
.cal-day.work-day{background:#f0fdf4;}
.cal-day.work-day .day-dot{background:var(--green);}
.cal-day.exc-blocked{background:#fff1f2;}
.cal-day.exc-blocked .day-dot{background:var(--red);}
.cal-day.exc-extra{background:#fefce8;}
.cal-day.exc-extra .day-dot{background:var(--amber);}
.cal-day.exc-custom{background:#eff6ff;}
.cal-day.exc-custom .day-dot{background:var(--blue);}
.day-dot{
  width:5px;height:5px;border-radius:50%;
  position:absolute;bottom:4px;left:50%;
  transform:translateX(-50%);
}

/* ── Legend ───────────────────────────────────── */
.cal-legend{
  display:flex;flex-wrap:wrap;gap:8px;margin-top:12px;
}
.leg-item{display:flex;align-items:center;gap:5px;font-size:10.5px;font-weight:600;color:var(--sub);}
.leg-dot{width:8px;height:8px;border-radius:50%;}

/* ── Exceptions List ──────────────────────────── */
.exc-section{margin-top:18px;}
.exc-hdr{
  font-size:10px;font-weight:700;letter-spacing:1px;
  text-transform:uppercase;color:var(--muted);margin-bottom:10px;
}
.no-exc{font-size:12.5px;color:var(--muted);}

/* Exception Item */
.exc-item{
  border:1.5px solid var(--border);border-radius:10px;
  overflow:hidden;margin-bottom:8px;transition:border-color .15s;
}
.exc-item:hover{border-color:#c8d3e0;}
.exc-item-head{
  display:flex;align-items:center;justify-content:space-between;
  padding:10px 14px;cursor:pointer;background:var(--white);
  user-select:none;
}
.exc-item-left{display:flex;align-items:center;gap:10px;}
.exc-date-badge{
  padding:3px 10px;border-radius:6px;
  font-size:11.5px;font-weight:700;
  background:var(--blue-s);color:var(--blue);
}
.exc-name{font-size:13px;font-weight:700;}
.exc-desc{font-size:11px;color:var(--sub);margin-top:2px;}
.exc-item-right{display:flex;align-items:center;gap:7px;}

.badge-mode{
  padding:3px 9px;border-radius:20px;
  font-size:10.5px;font-weight:700;
}
.badge-mode.blocked{background:var(--red-s);color:#b91c1c;}
.badge-mode.extra{background:var(--amber-s);color:#92400e;}
.badge-mode.custom{background:var(--blue-s);color:#1d4ed8;}

.btn-del{
  width:24px;height:24px;border-radius:6px;border:none;
  background:#fff0f0;color:#dc2626;cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  font-size:13px;transition:all .15s;
}
.btn-del:hover{background:#fecaca;}
.chevron{font-size:10px;color:var(--muted);transition:transform .2s;}
.exc-item.open .chevron{transform:rotate(180deg);}

/* Exception Panel */
.exc-panel{
  max-height:0;overflow:hidden;
  transition:max-height .3s,opacity .3s;opacity:0;
}
.exc-item.open .exc-panel{max-height:320px;opacity:1;}
.exc-panel-inner{
  padding:14px;border-top:1.5px solid var(--border);
  background:#f8fafc;
}

.mode-tabs{display:flex;gap:6px;margin-bottom:14px;}
.mode-tab{
  flex:1;padding:8px 6px;border-radius:8px;border:1.5px solid var(--border);
  background:var(--white);cursor:pointer;font-family:inherit;
  font-size:11.5px;font-weight:700;color:var(--sub);
  transition:all .15s;text-align:center;
}
.mode-tab:hover{border-color:var(--muted);}
.mode-tab.active.blocked{border-color:var(--red);background:var(--red-s);color:#b91c1c;}
.mode-tab.active.extra{border-color:var(--amber);background:var(--amber-s);color:#92400e;}
.mode-tab.active.custom{border-color:var(--blue);background:var(--blue-s);color:#1d4ed8;}

.mode-desc{
  font-size:11.5px;color:var(--sub);
  padding:8px 10px;background:var(--bg);
  border-radius:7px;margin-bottom:12px;line-height:1.6;
}
.custom-time-fields{
  display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;
  overflow:hidden;max-height:0;opacity:0;
  transition:max-height .25s,opacity .25s;
}
.custom-time-fields.open{max-height:100px;opacity:1;}

/* ── Actions ──────────────────────────────────── */
.form-actions{
  display:flex;justify-content:flex-end;gap:10px;margin-top:22px;
}
.btn{
  padding:11px 26px;border-radius:10px;font-family:inherit;
  font-size:13.5px;font-weight:700;cursor:pointer;
  border:none;transition:all .18s;
}
.btn-ghost{
  background:var(--white);border:1.5px solid var(--border);
  color:var(--sub);
}
.btn-ghost:hover{border-color:#94a3b8;color:var(--text);}
.btn-save{
  background:var(--slate2);color:#fff;
  box-shadow:0 3px 12px rgba(15,23,42,.25);
}
.btn-save:hover{background:var(--slate);transform:translateY(-1px);box-shadow:0 6px 18px rgba(15,23,42,.3);}
.btn-save:active{transform:none;}

/* ── Panel visibility ─────────────────────────── */
.b-panel{display:none;}
.b-panel.active{display:block;}
</style>
</head>
<body>
<div class="page">

  <div class="breadcrumb">
    <a href="#">Dashboard</a>
    <span>›</span>
    <span>Configurações</span>
    <span>›</span>
    <!-- <span>Disponibilidade</span> -->
  </div>

  <div class="page-header">
    <div>
      <h1>Configurações</h1>
      <p>Configure horários, dias de trabalho e exceções de cada profissional.</p>
    </div>
  </div>

  <?php if($mensagem): ?>
  <div class="toast">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
    <?=htmlspecialchars($mensagem)?>
  </div>
  <?php endif; ?>

  <form method="POST" id="mainForm" onsubmit="serializeAll()">

    <!-- Barber Tabs -->
    <div class="barber-tabs">
      <?php foreach($barbeiros as $i=>$b): ?>
      <button type="button" class="btab <?=$i===0?'active':''?>"
              data-id="<?=$b['id']?>"
              onclick="selectBarber(<?=$b['id']?>,this)">
        <div class="bav" style="background:<?=$b['cor']?>22;color:<?=$b['cor']?>"><?=$b['iniciais']?></div>
        <?=htmlspecialchars($b['nome'])?>
      </button>
      <?php endforeach; ?>
    </div>

    <?php foreach($barbeiros as $i=>$b): ?>
    <div class="b-panel <?=$i===0?'active':''?>" id="panel-<?=$b['id']?>">
      <div class="grid">

        <!-- ══ LEFT COLUMN ══════════════════════ -->
        <div>

          <!-- Horário Padrão -->
          <div class="card">
            <div class="card-head">
              <div class="c-icon">🕐</div>
              <div>
                <div class="c-title">Horário Padrão</div>
                <div class="c-sub">Aplicado em todos os dias sem exceção configurada</div>
              </div>
            </div>
            <p class="s-label">Expediente</p>
            <div class="row2" style="margin-bottom:16px">
              <div class="field">
                <label>Entrada</label>
                <input type="time" id="entrada-<?=$b['id']?>" name="b[<?=$b['id']?>][entrada]" value="09:00" oninput="updatePreview(<?=$b['id']?>)">
              </div>
              <div class="field">
                <label>Saída</label>
                <input type="time" id="saida-<?=$b['id']?>" name="b[<?=$b['id']?>][saida]" value="18:00" oninput="updatePreview(<?=$b['id']?>)">
              </div>
            </div>
            <!-- <div class="field">
              <label>Intervalo entre atendimentos</label>
              <select id="intervalo-<?=$b['id']?>" name="b[<?=$b['id']?>][intervalo]" onchange="updatePreview(<?=$b['id']?>)">
                <option value="15">⏱ 15 minutos</option>
                <option value="20">⏱ 20 minutos</option>
                <option value="30" selected>⏱ 30 minutos</option>
                <option value="45">⏱ 45 minutos</option>
                <option value="60">⏱ 1 hora</option>
              </select>
            </div> -->
          </div>

          <!-- Pausa -->
          <div class="card">
            <div class="card-head">
              <div class="c-icon">☕</div>
              <div>
                <div class="c-title">Pausa / Almoço</div>
                <div class="c-sub">Bloqueia horários automaticamente durante o período</div>
              </div>
            </div>
            <div class="switch-row">
              <div class="sw-info">
                <div class="sw-label">Ativar pausa</div>
                <div class="sw-sub">Horários da pausa ficam indisponíveis</div>
              </div>
              <label class="switch">
                <input type="checkbox" id="pausa-cb-<?=$b['id']?>"
                       name="b[<?=$b['id']?>][pausa_ativa]"
                       onchange="togglePausa(<?=$b['id']?>)">
                <span class="sl"></span>
              </label>
            </div>
            <div class="expand-body" id="pausa-fields-<?=$b['id']?>">
              <div class="row2" style="margin-top:4px">
                <div class="field">
                  <label>Início</label>
                  <input type="time" id="pausa-ini-<?=$b['id']?>" name="b[<?=$b['id']?>][pausa_ini]" value="12:00" oninput="updatePreview(<?=$b['id']?>)">
                </div>
                <div class="field">
                  <label>Fim</label>
                  <input type="time" id="pausa-fim-<?=$b['id']?>" name="b[<?=$b['id']?>][pausa_fim]" value="13:00" oninput="updatePreview(<?=$b['id']?>)">
                </div>
              </div>
            </div>
          </div>

          <!-- Dias Semanais -->
          <div class="card">
            <div class="card-head">
              <div class="c-icon">🔁</div>
              <div>
                <div class="c-title">Dias Semanais de Trabalho</div>
                <div class="c-sub">Define a recorrência automática de atendimento</div>
              </div>
            </div>
            <div class="week-grid" id="week-grid-<?=$b['id']?>">
              <!-- rendered by JS -->
            </div>
            <p class="week-hint">Os dias marcados repetem automaticamente em todo o calendário. Use o calendário ao lado para adicionar exceções.</p>
            <input type="hidden" name="b[<?=$b['id']?>][weekly_json]" id="weekly-json-<?=$b['id']?>">
          </div>

          <!-- Preview -->
          <div class="card">
            <div class="card-head">
              <div class="c-icon">👁</div>
              <div>
                <div class="c-title">Pré-visualização dos Horários</div>
                <div class="c-sub">Slots gerados com base no horário padrão</div>
              </div>
            </div>
            <div class="preview-box">
              <div class="pv-label">Horários disponíveis</div>
              <div class="chips" id="slots-<?=$b['id']?>"></div>
            </div>
          </div>

        </div><!-- left -->

        <!-- ══ RIGHT COLUMN ─ Calendar ══════════ -->
        <div>
          <div class="card">
            <div class="card-head">
              <div class="c-icon">📅</div>
              <div>
                <div class="c-title">Calendário de Exceções</div>
                <div class="c-sub">Clique em qualquer data futura para configurar</div>
              </div>
            </div>

            <div class="cal-nav">
              <span class="cal-month" id="cal-label-<?=$b['id']?>"></span>
              <div class="cal-arrows">
                <button type="button" class="cal-btn" onclick="calNav(<?=$b['id']?>,-1)">‹</button>
                <button type="button" class="cal-btn" onclick="calNav(<?=$b['id']?>,1)">›</button>
              </div>
            </div>

            <div class="cal-wds">
              <?php foreach(['D','S','T','Q','Q','S','S'] as $l): ?>
              <div class="cal-wd"><?=$l?></div>
              <?php endforeach; ?>
            </div>
            <div class="cal-grid" id="cal-grid-<?=$b['id']?>"></div>

            <div class="cal-legend">
              <div class="leg-item"><div class="leg-dot" style="background:var(--green)"></div>Dia de trabalho</div>
              <div class="leg-item"><div class="leg-dot" style="background:var(--red)"></div>Bloqueado</div>
              <div class="leg-item"><div class="leg-dot" style="background:var(--amber)"></div>Aberto extra</div>
              <div class="leg-item"><div class="leg-dot" style="background:var(--blue)"></div>Personalizado</div>
            </div>

            <!-- Exceptions hidden + list -->
            <input type="hidden" name="b[<?=$b['id']?>][excecoes_json]" id="exc-json-<?=$b['id']?>">

            <div class="exc-section">
              <div class="exc-hdr">Exceções configuradas</div>
              <div id="exc-list-<?=$b['id']?>">
                <span class="no-exc">Nenhuma exceção configurada.</span>
              </div>
            </div>

          </div>
        </div><!-- right -->

      </div><!-- .grid -->
    </div><!-- .b-panel -->
    <?php endforeach; ?>

    <div class="form-actions">
      <button type="reset" class="btn btn-ghost">Cancelar</button>
      <button type="submit" class="btn btn-save">
        <svg style="display:inline;vertical-align:middle;margin-right:6px" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
        Salvar configurações
      </button>
    </div>

  </form>
</div>

<script>
/* ═══════════════════════════════════════════════
   CONSTANTS & STATE
═══════════════════════════════════════════════ */
const MONTHS = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
const WDAYS  = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
const WSHORT = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];
const now    = new Date();

/*
  state[barbId] = {
    year, month,
    weeklyDays: Set of 0-6 (0=Sun),
    exceptions: { 'YYYY-MM-DD': { mode:'blocked'|'extra'|'custom', entrada, saida, intervalo, _open } }
  }
*/
const state = {};

<?php foreach($barbeiros as $b): ?>
state[<?=$b['id']?>] = {
  year:  now.getFullYear(),
  month: now.getMonth(),
  weeklyDays: new Set([1,2,3,4,5]),   // Mon–Fri default
  exceptions: {}
};
<?php endforeach; ?>

/* ── Helpers ──────────────────────────────────── */
function pad(n){ return String(n).padStart(2,'0'); }
function dateKey(y,m,d){ return `${y}-${pad(m+1)}-${pad(d)}`; }
function keyParts(k){ const [y,m,d]=k.split('-'); return {y:+y,m:+m-1,d:+d}; }
function toMin(t){ const [h,m]=t.split(':').map(Number); return h*60+m; }
function fromMin(m){ return `${pad(Math.floor(m/60))}:${pad(m%60)}`; }
function todayKey(){ return dateKey(now.getFullYear(),now.getMonth(),now.getDate()); }
function isWorkDay(id, dateObj) {
  return state[id].weeklyDays.has(dateObj.getDay());
}

/* ── Barber Tabs ──────────────────────────────── */
function selectBarber(id, btn){
  document.querySelectorAll('.btab').forEach(t=>t.classList.remove('active'));
  document.querySelectorAll('.b-panel').forEach(p=>p.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('panel-'+id).classList.add('active');
  renderCal(id);
  updatePreview(id);
}

/* ── Pausa Toggle ─────────────────────────────── */
function togglePausa(id){
  const cb=document.getElementById('pausa-cb-'+id);
  const f=document.getElementById('pausa-fields-'+id);
  f.classList.toggle('open',cb.checked);
  f.style.maxHeight=cb.checked?(f.scrollHeight+'px'):'0';
  updatePreview(id);
}

/* ── Weekly Days ──────────────────────────────── */
function renderWeekGrid(id){
  const grid=document.getElementById('week-grid-'+id);
  grid.innerHTML='';
  for(let i=0;i<7;i++){
    const active=state[id].weeklyDays.has(i);
    const btn=document.createElement('button');
    btn.type='button';
    btn.className='wday-btn'+(active?' active':'');
    btn.innerHTML=`<span class="wday-name">${WSHORT[i]}</span>`;
    btn.onclick=()=>toggleWeekDay(id,i,btn);
    grid.appendChild(btn);
  }
  saveWeekly(id);
  renderCal(id);
}

function toggleWeekDay(id,day,btn){
  if(state[id].weeklyDays.has(day)) state[id].weeklyDays.delete(day);
  else state[id].weeklyDays.add(day);
  btn.classList.toggle('active');
  saveWeekly(id);
  renderCal(id);
}

function saveWeekly(id){
  document.getElementById('weekly-json-'+id).value=
    JSON.stringify([...state[id].weeklyDays].sort());
}

/* ── Calendar ─────────────────────────────────── */
function calNav(id,dir){
  let {year,month}=state[id];
  month+=dir;
  if(month>11){month=0;year++;}
  if(month<0){month=11;year--;}
  state[id].year=year; state[id].month=month;
  renderCal(id);
}

function renderCal(id){
  const {year,month,weeklyDays,exceptions}=state[id];
  document.getElementById('cal-label-'+id).textContent=MONTHS[month]+' '+year;
  const grid=document.getElementById('cal-grid-'+id);
  grid.innerHTML='';
  const firstDay=new Date(year,month,1).getDay();
  const days=new Date(year,month+1,0).getDate();
  const tKey=todayKey();

  // empty cells
  for(let i=0;i<firstDay;i++){
    const e=document.createElement('div');
    e.className='cal-day empty'; grid.appendChild(e);
  }

  for(let d=1;d<=days;d++){
    const key=dateKey(year,month,d);
    const dateObj=new Date(year,month,d);
    const isPast=dateObj<new Date(now.getFullYear(),now.getMonth(),now.getDate());
    const isWork=isWorkDay(id,dateObj);
    const exc=exceptions[key];
    const el=document.createElement('button');
    el.type='button'; el.className='cal-day';
    el.innerHTML=`<span>${d}</span><span class="day-dot" style="opacity:0"></span>`;
    const dot=el.querySelector('.day-dot');

    if(isPast){ el.classList.add('past'); }
    if(key===tKey){ el.classList.add('today'); }

    if(exc){
      if(exc.mode==='blocked'){ el.classList.add('exc-blocked'); dot.style.opacity=1; }
      else if(exc.mode==='extra'){ el.classList.add('exc-extra'); dot.style.opacity=1; }
      else if(exc.mode==='custom'){ el.classList.add('exc-custom'); dot.style.opacity=1; }
    } else if(isWork && !isPast){
      el.classList.add('work-day'); dot.style.opacity=1;
    }

    if(!isPast) el.onclick=()=>clickDate(id,key);
    grid.appendChild(el);
  }
}

function clickDate(id,key){
  const exc=state[id].exceptions;
  if(!exc[key]){
    // determine smart default
    const {y,m,d}=keyParts(key);
    const dateObj=new Date(y,m,d);
    const isWork=isWorkDay(id,dateObj);
    exc[key]={
      mode: isWork ? 'blocked' : 'extra',
      entrada:'', saida:'', intervalo:'',
      _open:true
    };
  } else {
    exc[key]._open=!exc[key]._open;
  }
  renderCal(id);
  renderExcList(id);
  saveExc(id);
}

/* ── Exception List ───────────────────────────── */
const MODEDESC={
  blocked:'🚫 Barbeiro não atenderá neste dia, mesmo sendo dia de trabalho.',
  extra:  '✅ Dia extra de atendimento fora da recorrência semanal.',
  custom: '✏️ Substitui o horário padrão somente nesta data específica.'
};

function renderExcList(id){
  const exc=state[id].exceptions;
  const sorted=Object.keys(exc).sort();
  const container=document.getElementById('exc-list-'+id);
  if(!sorted.length){
    container.innerHTML='<span class="no-exc">Nenhuma exceção configurada.</span>';
    return;
  }
  container.innerHTML=sorted.map(key=>{
    const info=exc[key];
    const {y,m,d}=keyParts(key);
    const dateObj=new Date(y,m,d);
    const label=`${pad(d)}/${pad(m+1)}/${y}`;
    const wday=WDAYS[dateObj.getDay()];
    const isOpen=info._open||false;
    const ent=info.entrada||'';
    const sai=info.saida||'';
    const intv=info.intervalo||'30';
    const modeLabel={blocked:'Bloqueado',extra:'Aberto extra',custom:'Personalizado'}[info.mode]||'';

    return `
    <div class="exc-item${isOpen?' open':''}" id="ei-${id}-${key.replace(/-/g,'_')}">
      <div class="exc-item-head" onclick="toggleExcOpen(${id},'${key}')">
        <div class="exc-item-left">
          <div class="exc-date-badge">${label}</div>
          <div>
            <div class="exc-name">${wday}</div>
            <div class="exc-desc">${info.mode==='blocked'?'🚫 Dia bloqueado':info.mode==='extra'?'✅ Aberto extra':`✏️ Personalizado ${ent?ent+' – '+sai:''}`}</div>
          </div>
        </div>
        <div class="exc-item-right">
          <span class="badge-mode ${info.mode}">${modeLabel}</span>
          <button type="button" class="btn-del" onclick="removeExc(event,${id},'${key}')" title="Remover">×</button>
          <span class="chevron">▼</span>
        </div>
      </div>
      <div class="exc-panel">
        <div class="exc-panel-inner">
          <div class="mode-tabs">
            <button type="button" class="mode-tab blocked${info.mode==='blocked'?' active':''}"
                    onclick="setMode(${id},'${key}','blocked')">🚫 Bloquear</button>
            <button type="button" class="mode-tab extra${info.mode==='extra'?' active':''}"
                    onclick="setMode(${id},'${key}','extra')">✅ Abrir extra</button>
            <button type="button" class="mode-tab custom${info.mode==='custom'?' active':''}"
                    onclick="setMode(${id},'${key}','custom')">✏️ Personalizar</button>
          </div>
          <div class="mode-desc">${MODEDESC[info.mode]||''}</div>
          <div class="custom-time-fields${info.mode==='custom'?' open':''}" id="ctf-${id}-${key.replace(/-/g,'_')}">
            <div class="field">
              <label>Entrada</label>
              <input type="time" value="${ent}" oninput="setExcField(${id},'${key}','entrada',this.value)">
            </div>
            <div class="field">
              <label>Saída</label>
              <input type="time" value="${sai}" oninput="setExcField(${id},'${key}','saida',this.value)">
            </div>
            <div class="field">
              <label>Intervalo</label>
              <select onchange="setExcField(${id},'${key}','intervalo',this.value)">
                ${['15','20','30','45','60'].map(v=>`<option value="${v}"${intv===v?' selected':''}>${v} min</option>`).join('')}
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>`;
  }).join('');
}

function toggleExcOpen(id,key){
  const info=state[id].exceptions[key];
  if(info) info._open=!info._open;
  renderExcList(id);
}

function setMode(id,key,mode){
  const info=state[id].exceptions[key];
  info.mode=mode;
  if(mode==='custom' && !info.entrada){
    info.entrada=document.getElementById('entrada-'+id)?.value||'09:00';
    info.saida=document.getElementById('saida-'+id)?.value||'18:00';
    info.intervalo=document.getElementById('intervalo-'+id)?.value||'30';
  }
  info._open=true;
  renderCal(id);
  renderExcList(id);
  saveExc(id);
}

function setExcField(id,key,field,val){
  state[id].exceptions[key][field]=val;
  saveExc(id);
}

function removeExc(e,id,key){
  e.stopPropagation();
  delete state[id].exceptions[key];
  renderCal(id);
  renderExcList(id);
  saveExc(id);
}

function saveExc(id){
  const out={};
  for(const [key,info] of Object.entries(state[id].exceptions)){
    const {mode,entrada,saida,intervalo}=info;
    out[key]={mode,entrada,saida,intervalo};
  }
  document.getElementById('exc-json-'+id).value=JSON.stringify(out);
}

/* ── Preview ──────────────────────────────────── */
function updatePreview(id){
  const ent=document.getElementById('entrada-'+id)?.value||'09:00';
  const sai=document.getElementById('saida-'+id)?.value||'18:00';
  const intv=parseInt(document.getElementById('intervalo-'+id)?.value||30);
  const pausaCb=document.getElementById('pausa-cb-'+id);
  const pi=document.getElementById('pausa-ini-'+id)?.value||'12:00';
  const pf=document.getElementById('pausa-fim-'+id)?.value||'13:00';
  const pausaOn=pausaCb?.checked;

  let cur=toMin(ent);
  const end=toMin(sai);
  const piM=toMin(pi), pfM=toMin(pf);
  const slots=[];
  while(cur+intv<=end){
    const inPausa=pausaOn && cur>=piM && cur<pfM;
    slots.push({label:fromMin(cur),blocked:inPausa});
    cur+=intv;
  }
  const box=document.getElementById('slots-'+id);
  box.innerHTML=slots.length
    ? slots.map(s=>`<span class="chip${s.blocked?' blocked':''}">${s.label}</span>`).join('')
    : '<span class="chip-empty">Configure os horários acima.</span>';
}

/* ── Serialize all before submit ──────────────── */
function serializeAll(){
  <?php foreach($barbeiros as $b): ?>
  saveWeekly(<?=$b['id']?>);
  saveExc(<?=$b['id']?>);
  <?php endforeach; ?>
}

/* ── Init ─────────────────────────────────────── */
<?php foreach($barbeiros as $b): ?>
renderWeekGrid(<?=$b['id']?>);
renderCal(<?=$b['id']?>);
updatePreview(<?=$b['id']?>);
<?php endforeach; ?>
</script>
</body>
</html>