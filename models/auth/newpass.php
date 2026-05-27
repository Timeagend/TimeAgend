<?php
/**
 * ETAPA 1 — Recebe o e-mail e envia o link de redefinição
 * Caminho: models/auth/newpass.php
 */

session_start();
include_once('../../config/url.php');
include_once('../../config/conection.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . 'user/newpassword.php');
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if (!$email) {
    $_SESSION['error'] = 'Por favor, informe um e-mail válido.';
    header('Location: ' . BASE_URL . 'user/newpassword.php');
    exit;
}

$con = getDatabaseConnection();

$genericMessage = 'Se este e-mail estiver cadastrado, você receberá um link em instantes.';

$stmt = $con->prepare("SELECT iduser, nome_user FROM user WHERE email_user = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    $_SESSION['success'] = $genericMessage;
    header('Location: ' . BASE_URL . 'user/newpassword.php');
    exit;
}

$token     = bin2hex(random_bytes(32));
$tokenHash = hash('sha256', $token);
$expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

$stmt = $con->prepare("DELETE FROM password_resets WHERE user_id = ?");
$stmt->bind_param("i", $user['iduser']);
$stmt->execute();
$stmt->close();

$stmt = $con->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user['iduser'], $tokenHash, $expiresAt);

if (!$stmt->execute()) {
    error_log('Erro ao salvar token: ' . $stmt->error);
    $_SESSION['error'] = 'Erro interno. Tente novamente.';
    header('Location: ' . BASE_URL . 'user/newpassword.php');
    $stmt->close();
    exit;
}
$stmt->close();

$resetLink = BASE_URL . 'user/reset_password.php?token=' . urlencode($token) . '&email=' . urlencode($email);
$nome      = htmlspecialchars($user['nome_user']);

$emailBody = "
<!DOCTYPE html>
<html lang='pt-br'>
<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
</head>
<body style='margin:0;padding:0;background:#f4f4f4;font-family:Arial,sans-serif;'>

  <table width='100%' cellpadding='0' cellspacing='0' style='background:#f4f4f4;padding:40px 0;'>
    <tr>
      <td align='center'>
        <table width='560' cellpadding='0' cellspacing='0' style='max-width:560px;width:100%;'>

          <!-- CABEÇALHO -->
          <tr>
            <td align='center' style='padding-bottom:24px;'>
              <p style='margin:0;font-size:20px;font-weight:bold;color:#0d0d0d;letter-spacing:2px;'>
                TIME<span style='color:#f0c000;'>AGEND</span>
              </p>
            </td>
          </tr>

          <!-- CARD -->
          <tr>
            <td style='background:#ffffff;border-radius:16px;padding:48px 40px;box-shadow:0 2px 16px rgba(0,0,0,0.07);'>

              <!-- ícone -->
              <table width='100%' cellpadding='0' cellspacing='0'>
                <tr>
                  <td align='center' style='padding-bottom:28px;'>
                    <div style='display:inline-block;background:#f0fdf4;border-radius:50%;width:64px;height:64px;line-height:64px;text-align:center;font-size:28px;'>
                      🔑
                    </div>
                  </td>
                </tr>
              </table>

              <h1 style='margin:0 0 8px;font-size:22px;color:#0d0d0d;text-align:center;'>
                Redefinição de senha
              </h1>
              <p style='margin:0 0 28px;font-size:14px;color:#6b7280;text-align:center;line-height:1.6;'>
                Olá, <strong style='color:#0d0d0d;'>{$nome}</strong>! Recebemos uma solicitação para redefinir a senha da sua conta.
              </p>

              <!-- BOTÃO -->
              <table width='100%' cellpadding='0' cellspacing='0'>
                <tr>
                  <td align='center' style='padding-bottom:28px;'>
                    <a href='{$resetLink}'
                       style='display:inline-block;background:#0d0d0d;color:#ffffff;font-size:15px;
                              font-weight:bold;text-decoration:none;padding:14px 36px;
                              border-radius:8px;letter-spacing:0.5px;'>
                      Redefinir minha senha
                    </a>
                  </td>
                </tr>
              </table>

              <!-- link alternativo -->
              <p style='margin:0 0 8px;font-size:12px;color:#9ca3af;text-align:center;'>
                Ou copie e cole o link abaixo no navegador:
              </p>
              <p style='margin:0 0 28px;font-size:11px;color:#f0c000;text-align:center;word-break:break-all;'>
                {$resetLink}
              </p>

              <!-- aviso -->
              <table width='100%' cellpadding='0' cellspacing='0'>
                <tr>
                  <td style='background:#fafafa;border-left:3px solid #f0c000;border-radius:4px;padding:12px 16px;'>
                    <p style='margin:0;font-size:12px;color:#6b7280;line-height:1.6;'>
                      ⏱️ Este link expira em <strong>1 hora</strong>.<br>
                      Se não foi você quem solicitou, ignore este e-mail.
                    </p>
                  </td>
                </tr>
              </table>

            </td>
          </tr>

          <!-- RODAPÉ -->
          <tr>
            <td align='center' style='padding-top:24px;'>
              <p style='margin:0;font-size:11px;color:#9ca3af;'>
                © " . date('Y') . " TimeAgend · Enviado automaticamente, não responda este e-mail.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>
";

try {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['MAIL_USER'];
    $mail->Password   = $_ENV['MAIL_PASS'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom($_ENV['MAIL_USER'], 'TimeAgend');
    $mail->addAddress($email, $user['nome_user']);

    $mail->isHTML(true);
    $mail->Subject = '🔑 Redefinição de senha — TimeAgend';
    $mail->Body    = $emailBody;
    $mail->AltBody = "Olá, {$nome}! Acesse o link abaixo para redefinir sua senha (válido por 1 hora):\n\n{$resetLink}\n\nSe não foi você, ignore este e-mail.";

    $mail->send();

} catch (Exception $e) {
    error_log('Erro ao enviar e-mail: ' . $mail->ErrorInfo);
}

$_SESSION['success'] = $genericMessage;
header('Location: ' . BASE_URL . 'user/newpassword.php');
exit;