<?php

declare(strict_types=1);

namespace tbclla\Revolut\Facades;

use Illuminate\Support\Facades\Facade;

class Revolut extends Facade
{
    /**
     * Get the registered name of the component.
     * 
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'revolut';
    }
}
