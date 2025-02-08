<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require "vendor/autoload.php";

MercadoPago\SDK::setAccessToken("TEST-8008261255382605-020719-ec1c4b2c84fa54917d6a1c129099e60c-210535770");

$json = file_get_contents("php://input");
$data = json_decode($json, true);

$payment = new MercadoPago\Payment();
$payment->transaction_amount = 10.00;
$payment->description = "Rifa número " . $data["numero"];
$payment->payment_method_id = "pix";
$payment->payer = [
    "email" => "comprador@example.com",
    "first_name" => $data["nome"],
    "last_name" => "",
    "identification" => [
        "type" => "CPF",
        "number" => "12345678900"
    ]
];

$payment->save();

if ($payment->status == "pending") {
    echo json_encode(["qr_code" => $payment->point_of_interaction->transaction_data->qr_code]);
} else {
    echo json_encode(["error" => "Erro ao processar o pagamento"]);
}
?>
