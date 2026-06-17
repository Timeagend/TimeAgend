<?php
/**
 * ETAPA 2 — Formulário para digitar a nova senha
 * Caminho: user/reset_password.php
 */

session_start();
include_once('../config/url.php');
include_once('../config/conection.php');

$token = $_GET['token'] ?? '';
$email = filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL);

$validToken = false;

if ($token && $email) {
    $tokenHash = hash('sha256', $token);
    $con       = getDatabaseConnection();

    $stmt = $con->prepare("
        SELECT pr.id
        FROM password_resets pr
        INNER JOIN user u ON u.iduser = pr.user_id
        WHERE u.email_user = ?
          AND pr.token     = ?
          AND pr.expires_at > NOW()
        LIMIT 1
    ");
    $stmt->bind_param("ss", $email, $tokenHash);
    $stmt->execute();
    $stmt->store_result();
    $validToken = $stmt->num_rows > 0;
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha — TimeAgend</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>user/assets/css/reset-senha.css">
</head>
<body>
<div class="wrapper">
    <div class="card">

        <?php if (!$validToken): ?>

            <div class="card__icon card__icon--error">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>

            <h1 class="card__title">Link inválido ou expirado</h1>
            <p class="card__subtitle">Este link já foi usado ou expirou. Solicite uma nova redefinição de senha.</p>
            <a href="<?= BASE_URL ?>user/newpassword.php" class="btn-primary btn-primary--outline">
                Solicitar novo link
            </a>

        <?php else: ?>

            <div class="card__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>

            <h1 class="card__title">Redefinir Senha</h1>
            <p class="card__subtitle">Escolha uma senha segura com pelo menos 8 caracteres.</p>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert--error">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>models/auth/update_password.php" method="POST" class="form">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

                <div class="form__group">
                    <label for="password" class="form__label">Nova Senha</label>
                    <div class="form__input-wrap">
                        <input type="password" id="password" name="password"
                               class="form__input" placeholder="Mínimo 8 caracteres"
                               required minlength="8">
                        <button type="button" class="toggle-eye" data-target="password" aria-label="Mostrar senha">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form__group">
                    <label for="password_confirm" class="form__label">Confirmar Senha</label>
                    <div class="form__input-wrap">
                        <input type="password" id="password_confirm" name="password_confirm"
                               class="form__input" placeholder="Repita a nova senha"
                               required minlength="8">
                        <button type="button" class="toggle-eye" data-target="password_confirm" aria-label="Mostrar senha">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    <p id="match-msg" class="match-msg"></p>
                </div>

                <button type="submit" class="btn-primary">Salvar nova senha</button>
            </form>

        <?php endif; ?>

        <a href="<?= BASE_URL ?>user/login.php" class="back-link">← Voltar ao login</a>

    </div>
</div>

<script>
    /* toggle olho */
    document.querySelectorAll('.toggle-eye').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.target);
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });

    /* validação de confirmação em tempo real */
    const pw  = document.getElementById('password');
    const pw2 = document.getElementById('password_confirm');
    const msg = document.getElementById('match-msg');

    if (pw2) {
        pw2.addEventListener('input', () => {
            if (!pw2.value) { msg.textContent = ''; return; }
            if (pw.value === pw2.value) {
                msg.textContent = '✓ Senhas coincidem';
                msg.className   = 'match-msg match-msg--ok';
            } else {
                msg.textContent = '✗ Senhas não coincidem';
                msg.className   = 'match-msg match-msg--err';
            }
        });
    }
</script>
</body>
</html>