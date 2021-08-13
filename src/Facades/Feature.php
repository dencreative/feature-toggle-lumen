<?php

namespace CharlGottschalk\FeatureToggleLumen\Facades;

use Illuminate\Support\Facades\Facade;

class Feature extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'feature';
    }
}
