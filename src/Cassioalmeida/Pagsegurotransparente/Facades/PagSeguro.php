<?php

namespace Cassioalmeida\Pagsegurotransparente\Facades;

use Illuminate\Support\Facades\Facade;

class PagSeguro extends Facade{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'pagsegurotransparente'; }
} 