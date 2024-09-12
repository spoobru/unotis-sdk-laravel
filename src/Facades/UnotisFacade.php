<?php namespace Spoob\UnotisLaravel\Facades;

use Illuminate\Support\Facades\Facade;


class UnotisFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'unotis-laravel';
    }
}
