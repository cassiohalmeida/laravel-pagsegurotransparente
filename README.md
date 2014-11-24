Laravel 4 - PagSeguro API
=============================

Package que permitie a utilização da API do PagSeguro. Ideal para quem deseja integrar sua aplicação de forma "transparente"
para o usuário.

OBS: Esse package abstrai a API do backend, na hora de enviar sua requisição para o PagSeguro. Porém, a integração exige algumas configurações feita em JavaScript. Para ver a documentação completa, use o link abaixo:

Manual completo para a integração.

    http://download.uol.com.br/pagseguro/docs/pagseguro-checkout-transparente.pdf

A partir da página 6 começam as instruções para o Browser.
Você não precisa se preocupar em salvar os links para os arquivos de Produção e Homologação, uma vez que eles já estão configurados no package como você verá mais adiante. A única coisa que vcê precisa fazer é usar o seguinte código no seu front-end:

```php
<script type="text/javascript" src="{{PagSeguro::getPagSeguroData()->getJavascriptURL()}}"></script>
<script type="text/javascript">
    PagSeguroDirectPayment.setSessionId('{{PagSeguro::printSessionId()}}');
</script>
```

## Instalando

## Laravel 4.2

Adicione o repositório no seu arquivo `composer.json`.

	"require-dev": {
		"cassioalmeida/pagsegurotransparente": "dev-master"
	}

Em seguida, atualize com o comando abaixo:

    composer update

Após o package ser baixado, você precisa adicionar o 'service provider'. Abra o arquivo `app/config/app.php`, e adicione um novo índice ao array existente com a seguinte linha.

    'Cassioalmeida\Pagsegurotransparente\PagsegurotransparenteServiceProvider'

Ainda no arquivo `app/config/app.php`, adicione um aliases para o package:

    'PagSeguro'     =>     'Cassioalmeida\Pagsegurotransparente\Facades\PagSeguro'
    
Por último, mas não menos importante, digite o comando abaixo para que o arquivo de configuração do package seja criado:

    php artisan config:publish cassioalmeida/pagsegurotransparente

Esse comando irá criar o arquivo de configuração na pasta: `app/config/packages/cassioalmeida/pagsegurotransparente`, com o nome de 'environment.php'.

## Configurando

Depois dos passos anteriores, você deverá possuir o arquivo de configuração assim:

```php
<?php
/**
* Arquivo de configuração para o package;
* Preencha corretamente os dados para não correr o risco de ter problemas com configuração de ambientes;
*/
return array(

  //Ambiente de sandbox? True para Sanbox | False para Produção;
  'sandbox'   =>  true,


  //Dados para ambiente SANDBOX!
  //O package usará esses dados automaticamente de acordo com a configuração do indice anterior;
  'sandboxData'   =>  array(
    'credentials'  =>   array(
      'email'     =>  'seu-email',//E-mail a conta PagSeguro;
      'token'     =>  'seu-token',//Token da conta PagSeguro;
    ),
    'sessionURL' => "https://ws.sandbox.pagseguro.uol.com.br/v2/sessions", //URL de Sessões
    'transactionsURL' => "https://ws.sandbox.pagseguro.uol.com.br/v2/transactions", // URL para transações;
    'javascriptURL' => "https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js",//URL 	para javascript;
    'notificationURL' => 'https://ws.sandbox.pagseguro.uol.com.br/v2/transactions/notifications/' //URL para buscar notificacoes no PagSeguro;
    ),


    //Dados para ambiente PRODUÇÃO!
    //O package usará esses dados automaticamente de acordo com a configuração do indice anterior;
    'productionData'    =>  array(
      'credentials'  =>   array(
        'email'     =>  'seu-email',//E-mail a conta PagSeguro;
        'token'     =>  'seu-token',//Token da conta PagSeguro;
      ),
      'sessionURL' => "https://ws.pagseguro.uol.com.br/v2/sessions",
      'transactionsURL' => "https://ws.pagseguro.uol.com.br/v2/transactions",
      'javascriptURL' => "https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js",
      'notificationURL' => 'https://ws.pagseguro.uol.com.br/v2/transactions/notifications/'
      )

  );

```
Basicamente você irá editar o a flag `sandbox` para `true` OU `false` para indicar se o ambiente é de produção ou homologação. Por padrão o package usa a flag `sandbox => true`. Além disso, tanto o indice `sandboxData` como `productionData` possuem as credenciais do usuário, sendo `email` e `token`. Esses dados você consegue nas configurações da conta no painel do PagSeguro. Os demais indices são URL's fornecidas pela própria documentação do PagSeguro.

## Utilizando

Depois de todos os passos anteriores, veja um exemplo de como utilizar o Package:

## BOLETO BANCÁRIO

```php
$params = array(
	'paymentMethod' => 'boleto',
	'senderEmail' => 'email do cliente',
	'senderName' => 'nome do cliente',
	'senderCPF' => 'cpf do cliente sem traço ou ponto',
	'senderAreaCode' => 'ddd do cliente',
	'senderPhone' => 'telefone do cliente',
	'shippingAddressPostalCode' => 'CEP do endereço de entrega',
	'shippingAddressStreet' => 'Rua do endereço de entrega',
	'shippingAddressNumber' => 'Número do endereço de entrega',
	'shippingAddressComplement' => 'Complemento do endereço de entrga',
	'shippingAddressDistrict' => 'Bairro do endereço de entrega',
	'shippingAddressCity' => 'Cidade do endereço de entrega',
	'shippingAddressState' => 'Estado do endereço de entrega, exemplo : SP',
	"shippingAddressCountry" => "BRA",
	'senderHash' => 'Sender HASH', //Utilizar documentação para ver como conseguir;
	'currency' => 'BRL',
	'shippingType'  =>  3, //Tipo de frete 1 – PAC, 2 – SEDEX, 3 - Desconhecido
	'shippingCost'  =>  'Valor do frete', // Decimal, com duas casas decimais separadas por ponto (ex 1234.56) maior que 0.00 e menor ou igual a 9999999.00;
	$params['itemId'] =  'ID ou SKU do seu produto',
        $params['itemDescription'] =  'Descrição do produto',
        $params['itemAmount'] =  'Preço do produto', //Decimal com duas casas decimais separadas por ponto (ex1234.56) maior que 0.00 e menor ou igual a 9999999.00;
        $params['itemQuantity'] =  'Qty do produto' // Um número inteiro maior ou igual a 1 e menor ou igual a 999
);

//Requisitando a API.

$pagSeguroReturn = PagSeguro::doPayment($params);

```

## CARTÃO DE CRÉDITO

```php
$params = array(
	'paymentMethod' =>  'creditCard',
        'currency'  =>  "BRL",
        'senderName'    =>  utf8_decode(\Auth::getUser()->getName()),
        'senderCPF'     =>  \Auth::getUser()->getCleanCpf(),
        'senderAreaCode'    =>  $this->getPhoneDdd("(",")", \Session::get("checkout_billing_address")["phone_1"]),
        'senderPhone'   =>  $this->getCleanedPhone(\Session::get("checkout_billing_address")["phone_1"]),
        'senderEmail'   =>  \Auth::getUser()->getEmail(),
        'senderHash'    =>  \Input::get('sender_hash'),
        'shippingAddressPostalCode' => \Session::get("checkout_shipping_address")["zip_code"],
        'shippingAddressStreet' => utf8_decode(\Session::get("checkout_shipping_address")["address"]),
        'shippingAddressNumber' => \Session::get("checkout_shipping_address")["number"],
        'shippingAddressComplement' => utf8_decode(\Session::get("checkout_shipping_address")["complement"]),
        'shippingAddressDistrict' => utf8_decode(\Session::get("checkout_shipping_address")["district"]),
        'shippingAddressCity' => utf8_decode(\Session::get("checkout_shipping_address")["city"]),
        'shippingAddressState' => \Session::get("checkout_shipping_address")["address_state_code"],
        "shippingAddressCountry" => "BRA",
        'shippingType'  =>  3,
        'shippingCost'  =>  \Session::get('delivery_rate'),
        'creditCardToken'   =>  \Input::get('credit_card_token'),
        'installmentQuantity'   =>  $this->getInstallmentQuantity(\Input::get('credit_card_installments')),
        'installmentValue'  =>      $this->getInstallmentValue(\Input::get('credit_card_installments')),
        'noInterestInstallmentQuantity' => 5,//Valor setado no metodo getInstallments (checkout.js) se alterar aqui tem que alterar lá e vice versa;
        'creditCardHolderName'  => \Input::get('credit_card_holder_name'),
        'creditCardHolderCPF'   =>  \Input::get('credit_card_holder_cpf'),
        'creditCardHolderBirthDate' =>  \Input::get('credit_card_holder_birthday'),
        'creditCardHolderAreaCode'  =>  \Input::get('credit_card_phone_ddd'),
        'creditCardHolderPhone'     =>  \Input::get('credit_card_phone_number'),
        'billingAddressStreet'  =>  utf8_decode(\Session::get("checkout_billing_address")["address"]),
        'billingAddressNumber'  =>  \Session::get("checkout_billing_address")["number"],
        'billingAddressComplement' => utf8_decode(\Session::get("checkout_billing_address")["complement"]),
        'billingAddressDistrict'    =>  utf8_decode(\Session::get("checkout_billing_address")["district"]),
        'billingAddressPostalCode'  =>  \Session::get("checkout_billing_address")["zip_code"],
        'billingAddressCity'    =>  utf8_decode(\Session::get("checkout_billing_address")["city"]),
        'billingAddressState'   =>  \Session::get("checkout_billing_address")["address_state_code"],
        'billingAddressCountry' =>  'BRA'
);

//Requisitando a API.

$pagSeguroReturn = PagSeguro::doPayment($params);

```


Caso exista algum erro nas informações, a variável `$pagSeguroReturn` terá um indice `errors`. Verifique seu retorno para descobrir qual o erro.
Em caso de SUCESSO na requisição a variável `$pagSeguroReturn` terá um indice `transaction` com seus respectivos dados como `código de transação`, `paymentLink` (no caso de boleto) entre outros.
