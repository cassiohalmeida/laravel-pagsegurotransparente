<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Sandbox
    |--------------------------------------------------------------------------
    |
    | Quando sua aplicação está em modo Sandbox os valores das transações não são reais.
    | Permitindo que você teste de forma mais fácil para ter certeza de que tudo está
    | funcionando para quando estiver em produção.
    | Valores: TRUE para Sandbox | FALSE para Produção.
    |
    */

    'sandbox'   =>  true,

    /*
	|--------------------------------------------------------------------------
	| Dados para ambiente de testes (Sandbox)
	|--------------------------------------------------------------------------
	|
	| Quando sua aplicação estiver com o modo Sandbox ativo,
	| o package irá usar automaticamente as configurações setadas no array abaixo.
	|
	*/

    'sandboxData'   =>  array(

        'credentials'   =>   array(
          'email'     =>  'seu-email',
          'token'     =>  'seu-token',
        ),

        'sessionURL'        => "https://ws.sandbox.pagseguro.uol.com.br/v2/sessions",
        'transactionsURL'   => "https://ws.sandbox.pagseguro.uol.com.br/v2/transactions",
        'javascriptURL'     => "https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js",
        'notificationURL'   => 'https://ws.sandbox.pagseguro.uol.com.br/v2/transactions/notifications/'
    ),


    /*
	|--------------------------------------------------------------------------
	| Dados para ambiente de produção (Production)
	|--------------------------------------------------------------------------
	|
	| Quando sua aplicação estiver com o modo Sandbox inativo (false),
	| o package irá usar automaticamente as configurações setadas no array abaixo.
	|
	*/
    'productionData'    =>  array(

      'credentials'  =>   array(
        'email'     =>  'seu-email',
        'token'     =>  'seu-token',
      ),

      'sessionURL'      => "https://ws.pagseguro.uol.com.br/v2/sessions",
      'transactionsURL' => "https://ws.pagseguro.uol.com.br/v2/transactions",
      'javascriptURL'   => "https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js",
      'notificationURL' => 'https://ws.pagseguro.uol.com.br/v2/transactions/notifications/'
      )
  );