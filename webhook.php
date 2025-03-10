<?php
    // Passo 1: Carregar a biblioteca do Composer
    require_once 'vendor/autoload.php';

    // Teste para verificar se o autoload está funcionando
    if (class_exists('MercadoPago\Client\Payment\PaymentClient')) {
        echo "Autoload funcionando corretamente. Classe PaymentClient encontrada.\n";
    } else {
        echo "Erro: Classe PaymentClient não encontrada. Verifique o autoload.\n";
    }

    use MercadoPago\Client\Common\RequestOptions;
    use MercadoPago\Client\Payment\PaymentClient;
    use MercadoPago\Exceptions\MPApiException;
    use MercadoPago\MercadoPagoConfig;

    // Passo 2: Configurar o token de acesso do Mercado Pago
    MercadoPagoConfig::setAccessToken("TEST-8008261255382605-020719-ec1c4b2c84fa54917d6a1c129099e60c-210535770"); // Substitua pelo seu token de acesso

    // Passo 2.1 (opcional - o padrão é SERVER): Definir ambiente de teste ou produção
    MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL); // Definindo para testar localmente

    // Passo 3: Inicializar o cliente da API
    $client = new PaymentClient();

    try {
        // Passo 4: Criar o array de requisição
        $request = [
            "transaction_amount" => 10.00,  // Valor do pagamento
            "description" => "Rifa número " . $data["numero"],  // Descrição do pagamento
            "payment_method_id" => "pix",  // Método de pagamento
            "payer" => [
                // Não estamos mais enviando email, first_name, last_name ou número de CPF
            ]
        ];

        // Passo 5: Criar as opções de requisição, configurando o cabeçalho X-Idempotency-Key
        $request_options = new RequestOptions();
        $request_options->setCustomHeaders(["X-Idempotency-Key: " . uniqid()]); // Cabeçalho para garantir idempotência

        // Passo 6: Realizar a requisição
        $payment = $client->create($request, $request_options);
        echo $payment->id;

    // Passo 7: Tratar exceções
    } catch (MPApiException $e) {
        echo "Status code: " . $e->getApiResponse()->getStatusCode() . "\n";
        echo "Content: ";
        var_dump($e->getApiResponse()->getContent());
        echo "\n";
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
?>
