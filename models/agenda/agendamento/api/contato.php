<?php
require_once '../../../../config/url.php';
require_once '../../../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

header('Content-Type: application/json');

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../../');
$dotenv->load();

$data     = json_decode(file_get_contents('php://input'), true);
$nome     = htmlspecialchars($data['nome']     ?? '');
$email    = htmlspecialchars($data['email']    ?? '');
$mensagem = htmlspecialchars($data['mensagem'] ?? '');

if (empty($nome) || empty($email) || empty($mensagem)) {
    echo json_encode(['success' => false, 'erro' => 'Campos obrigatórios ausentes.']);
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['MAIL_USER'];
    $mail->Password   = $_ENV['MAIL_PASS'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom($_ENV['MAIL_USER'], 'TimeAgend');
    $mail->addAddress($_ENV['MAIL_USER']);
    $mail->addReplyTo($email, $nome);

    $mail->Subject = "Contato via site - $nome";
    $mail->Body    = "Nome: $nome\nE-mail: $email\n\nMensagem:\n$mensagem";

    $mail->send();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'erro' => $mail->ErrorInfo]);
}