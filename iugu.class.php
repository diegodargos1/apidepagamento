<?php 
require_once "lib/Iugu.php";
class IuguApi {
    private $storeId;
    function __construct($iuguKey, $iuguId){
        Iugu::setApiKey($iuguKey); // Ache sua chave API no Painel
        $this->storeId = $iuguId;
    }

    /**
     *  Criar Cliente
     *
     * @param $cliente token do cartao gerado pelo JS
     *      formato array do cliente:
     *  [
     *   'email' => 'joe@gmail.com', 
     *   'nome'=> 'Joe', 
     *   'cpf' => '37495047844', 
     *   'cep' => '09572200', 
     *   'numero' => '1000',
     *  ]
     * @return json
     */
    public function Cliente($data = false){
        try{
            if(!$data)throw new Exception('Favor informar os dados do cliente.');

            if(!$data)return false;
            $customer = (isset($data['iuguId'])) ? Iugu_Customer::search($data['iuguId']) : Iugu_Customer::create([
                "email" => $data['email'],
                "name"  => $data['nome'],
            ]);
             return $customer;
        }catch  (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        
    }

    /**
     *  Pagamento Boleto
     *
     * @param $cliente token do cartao gerado pelo JS
     *      formato array do cliente:
     *  [
     *   'email' => 'joe@gmail.com', 
     *   'nome'=> 'Joe', 
     *   'cpf' => '37495047844', 
     *   'cep' => '09572200', 
     *   'numero' => '1000',
     *   'iuguId' => 'F0D44B8E1E3843648D8ACC8467CFCDB7'
     *   ]
     * @param $items itens solicitados pelo cliente
     *      formato array dos itens:
     *      [[
     *          "description"=> "Item Um",
     *          "price_cents"=> 1000,
     *          "quantity"=> 1,
     *          "price"=> "R$ 10,00"
     *      ]];
     * @return json
     */

    public function Boleto($cliente = false,$items = false){
        try{
            if(!$cliente)throw new Exception('Favor informar dados do cliente.');
            if(!$items)throw new Exception('Favor informar os items.');

            self::Cliente($cliente);
            return Iugu_Invoice::create(
                [
                    "due_date"=> date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 5, date('Y'))),
                    "email" => $cliente["email"],
                    "items"=> $items,
                    "payer"=> [
                        "cpf_cnpj" => $cliente['cpf'],
                        "name" => $cliente['nome'],
                        "address" => [
                            "zip_code" => $cliente['cep'],
                            "number" => $cliente['numero']
                        ]
                    ]
                ]
            );
        }catch  (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    /**
     *  Pagamento cartão de credito
     *
     * @param $token token do cartao gerado pelo JS
     * @param $email email do cliente
     * @param $items itens solicitados pelo cliente
     *      formato array dos itens:
     *      [[
     *          "description"=> "Item Um",
     *          "price_cents"=> 1000,
     *          "quantity"=> 1,
     *          "price"=> "R$ 10,00"
     *      ]];
     * @return json
     */
    public function Cartao($token,$email,$items){

        try{
            if(!$token)throw new Exception('Favor informar o token.');
            if(!$email)throw new Exception('Favor informar email do cliente.');
            if(!$items)throw new Exception('Favor informar os items.');

            return Iugu_Charge::create(
                [
                    "token"=> $token,
                    "email"=>$email,
                    "items" => $items
                ]
            );
        }catch  (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        
    }
    
    /**
     *  Solicitar reembolso
     *
     * @param $key id do pagamento realizada
     * @return json
     */
    public function Reembolso($key = false){

        try{
            if(!$key)throw new Exception('Favor informar o id do reembolso.');

            $r = new Iugu_Invoice();
            return $r->refund($key);
        }catch  (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        
    }
}

?>