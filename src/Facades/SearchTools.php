<?php

namespace bnjns\SearchTools\Facades;

use Illuminate\Support\Facades\Facade;

class SearchTools extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'search-tools';
    }
}