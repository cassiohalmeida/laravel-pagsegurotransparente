<?php namespace Cassioalmeida\Pagsegurotransparente;

use Illuminate\Support\ServiceProvider;

class PagsegurotransparenteServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('cassioalmeida/pagsegurotransparente');
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app['pagsegurotransparente'] = $this->app->share(function($app)
        {
            $sandbox = \Config::get('pagsegurotransparente::environment.sandbox');
            $sandboxData = \Config::get('pagsegurotransparente::environment.sandboxData');
            $productionData = \Config::get('pagsegurotransparente::environment.productionData');
            return new PagSeguro($sandbox, $sandboxData, $productionData);
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('pagsegurotransparente');
	}

}
