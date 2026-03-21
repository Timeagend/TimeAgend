<?php
session_start();
require '../auth/authFunctions.php';
require 'vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

MercadoPagoConfig::setAccessToken('TEST-6643239305109260-033017-3f821df86315c5038cbb0b15cac4ee2d-1079075441');

if (!isset($_POST['plano'])) {
    die("Nenhum plano selecionado.");
}

$planoSelecionado = $_POST['plano'];

$planos = [
    'basic' => ['nome' => 'Plano Basic - Barbearia', 'preco' =>  39.90],
    'plus' => ['nome' => 'Plano Plus - Barbearia', 'preco' => 69.90],
    'premium' => ['nome' => 'Plano Premium - Barbearia', 'preco' => 109.90],
];

if (!array_key_exists($planoSelecionado, $planos)) {
    die("Plano inválido.");
}

$plano = $planos[$planoSelecionado];

$preferenceClient = new PreferenceClient();


$preferenceData = [
    "items" => [
        [
            "title" => $plano['nome'],
            "quantity" => 1,
            "currency_id" => "BRL",
            "unit_price" => $plano['preco']
        ]
    ],
    "back_urls" => [
        "success" => "https://seusite.com.br/TimeAgend/public/planos.php",
        "failure" => "https://seusite.com.br/TimeAgend/public/planos.php",
        "pending" => "https://seusite.com.br/TimeAgend/public/planos.php"
    ],
    "auto_return" => "approved"
];



// $preferenceData = [
//     "items" => [
//         [
//             "title" => $plano['nome'],
//             "quantity" => 1,
//             "currency_id" => "BRL",
//             "unit_price" => $plano['preco']
//         ]
//     ],
//     "back_urls" => [
//         "success" => "http://localhost/TimeAgend/public/planos.php",
//         "failure" => "http://localhost/TimeAgend/public/planos.php",
//         "pending" => "http://localhost/TimeAgend/public/planos.php"
//     ],
//     "auto_return" => "approved"
// ];

try {
    $preference = $preferenceClient->create($preferenceData);
    header("Location: " . $preference->init_point);
    $_SESSION['plano'] = $planoSelecionado;
    exit;
} catch (MPApiException $e) {
    // echo "Erro ao criar a preferência de pagamento: " . $e->getMessage();
     echo "Erro ao criar a preferência de pagamento: " . $e->getMessage();
    echo "<pre>";
    print_r($e->getApiResponse());
    echo "</pre>";
}
?>
