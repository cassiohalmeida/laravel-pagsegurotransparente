Laravel 4 - PagSeguro API
=============================

Package que permitie a utilização da API do PagSeguro. Ideal para quem deseja integrar sua aplicação de forma "transparente"
para o usuário.

## Isntalando

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
