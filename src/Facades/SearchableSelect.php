<?php

namespace Williamug\SearchableSelect\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Williamug\SearchableSelect\SearchableSelect
 */
class SearchableSelect extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Williamug\SearchableSelect\SearchableSelect::class;
    }
}
