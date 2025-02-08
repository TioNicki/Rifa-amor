<?php
require "vendor/autoload.php";
MercadoPago\SDK::setAccessToken("TEST-8008261255382605-020719-ec1c4b2c84fa54917d6a1c129099e60c-210535770");

$dados = json_decode(file_get_contents("php://input"), true);

if ($dados["type"] == "payment") {
    $payment = MercadoPago\Payment::find_by_id($dados["data"]["id"]);

    if ($payment->status == "approved") {
        file_put_contents("pagamentos_confirmados.txt", "Pagamento aprovado para: " . $payment->description . "\n", FILE_APPEND);
    }
}

http_response_code(200);
?>
