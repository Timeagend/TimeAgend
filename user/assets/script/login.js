/* ── TOAST ── */
let toastTimer = null;
function showToast(tipo, titulo, texto, duracao) {
    duracao = duracao || 4000;
    var toast = document.getElementById('toast');
    var icons = { error: '⚠', success: '✔', warning: 'ℹ' };
    toast.className = 'toast toast-' + tipo;
    document.getElementById('toastIcon').textContent  = icons[tipo] || icons.warning;
    document.getElementById('toastTitle').textContent = titulo;
    document.getElementById('toastText').textContent  = texto;
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(closeToast, duracao);
}
function closeToast() {
    document.getElementById('toast').classList.remove('show');
    clearTimeout(toastTimer);
}
function isEmailValido(v) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
}

/* ── VALIDAÇÃO LOGIN ── */
document.getElementById('loginForm').addEventListener('submit', function(e) {
    var email = document.getElementById('login');
    var senha = document.getElementById('password');
    email.classList.remove('input-error');
    senha.classList.remove('input-error');

    if (!email.value.trim() || !isEmailValido(email.value.trim())) {
        e.preventDefault();
        email.classList.add('input-error');
        email.focus();
        showToast('warning', 'Email inválido', 'Digite um email válido para continuar.');
        return;
    }
    if (!senha.value.trim()) {
        e.preventDefault();
        senha.classList.add('input-error');
        senha.focus();
        showToast('warning', 'Senha vazia', 'Por favor, digite sua senha.');
        return;
    }
    if (senha.value.trim().length < 6) {
        e.preventDefault();
        senha.classList.add('input-error');
        senha.focus();
        showToast('warning', 'Senha curta', 'A senha deve ter pelo menos 6 caracteres.');
        return;
    }
});
document.getElementById('login').addEventListener('input', function() { this.classList.remove('input-error'); });
document.getElementById('password').addEventListener('input', function() { this.classList.remove('input-error'); });

/* ── MÁSCARA TELEFONE ── */
document.getElementById('cadastro-numero').addEventListener('input', function() {
    var v = this.value.replace(/\D/g, '').slice(0, 11);
    if (v.length > 10)      v = v.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
    else if (v.length > 6)  v = v.replace(/^(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
    else if (v.length > 2)  v = v.replace(/^(\d{2})(\d+)/, '($1) $2');
    else if (v.length > 0)  v = '(' + v;
    this.value = v;
});

/* ── VALIDAÇÃO CADASTRO ── */
document.getElementById('cadastroForm').addEventListener('submit', function(e) {
    var nome     = document.getElementById('cadastro-username');
    var email    = document.getElementById('cadastro-email');
    var tel      = document.getElementById('cadastro-numero');
    var senha    = document.getElementById('cadastro-senha');
    var confirma = document.getElementById('cadastro-confirma-senha');

    [nome, email, tel, senha, confirma].forEach(function(el) { el.classList.remove('input-error'); });

    var valido = true;
    var primeiro = null;
    var msg = '';

    if (!nome.value.trim() || nome.value.trim().length < 3) {
        nome.classList.add('input-error');
        primeiro = primeiro || nome;
        msg = msg || 'O nome deve ter pelo menos 3 caracteres.';
        valido = false;
    }
    if (!email.value.trim() || !isEmailValido(email.value.trim())) {
        email.classList.add('input-error');
        primeiro = primeiro || email;
        msg = msg || 'Digite um email válido.';
        valido = false;
    }
    if (tel.value.replace(/\D/g, '').length < 10) {
        tel.classList.add('input-error');
        primeiro = primeiro || tel;
        msg = msg || 'Digite um telefone válido com DDD.';
        valido = false;
    }
    if (senha.value.trim().length < 6) {
        senha.classList.add('input-error');
        primeiro = primeiro || senha;
        msg = msg || 'A senha deve ter pelo menos 6 caracteres.';
        valido = false;
    }
    if (senha.value.trim() !== confirma.value.trim()) {
        confirma.classList.add('input-error');
        primeiro = primeiro || confirma;
        msg = msg || 'As senhas não coincidem.';
        valido = false;
    }

    if (!valido) {
        e.preventDefault();
        if (primeiro) primeiro.focus();
        showToast('warning', 'Campos inválidos', msg);
    }
});

['cadastro-username','cadastro-email','cadastro-numero','cadastro-senha','cadastro-confirma-senha']
    .forEach(function(id) {
        document.getElementById(id).addEventListener('input', function() {
            this.classList.remove('input-error');
        });
    });

/* Confirmação em tempo real */
document.getElementById('cadastro-confirma-senha').addEventListener('input', function() {
    var senha = document.getElementById('cadastro-senha').value;
    if (this.value && senha && this.value !== senha) {
        this.classList.add('input-error');
    } else {
        this.classList.remove('input-error');
    }
});