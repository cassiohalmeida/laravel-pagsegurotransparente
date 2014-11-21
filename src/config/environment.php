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
        'javascriptURL' => "https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"//URL para javascript;
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
        'javascriptURL' => "https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"
    )

);