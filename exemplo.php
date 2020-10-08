<?php
//cartao TESTE
//4111 1111 1111 1111 
//123
//12/20
require_once 'iugu.class.php';
$iugu = new IuguApi("key gerada no site", 'id da conta');

//PAGAMENTO BOLETO
$cliente = array(
    'email' => 'joe@gmail.com', 
    'nome'=> 'Joe', 
    'cpf' => '37495047844', 
    'cep' => '09572200', 
    'numero' => '1000',
    'iuguId' => 'F0D44B8E1E3843648D8ACC8467CFCDB7'
);
$item = [[
    "description"=> "Item Um",
    "price_cents"=> 1000,
    "quantity"=> 1,
    "price"=> "R$ 10,00"
]];

// Exemplo de cartao de credito 
//$cartao = $iugu->Cartao($_POST['token'], 'diego@teste.com',$item);

//Exemplo de boleto
//$boleto = $iugu->Boleto($cliente, $item);

//Exemplo de reembolso
//$reembolso = $iugu->Reembolso('Id da venda gerado pela API');

?>