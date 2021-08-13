<?php

namespace CharlGottschalk\FeatureToggleLumen\Http\Middleware;

use Closure;
use CharlGottschalk\FeatureToggleLumen\Facades\Feature;

class CheckFeature
{
    public function handle($request, Closure $next, $feature)
    {
        if (!Feature::enabled($feature)) {
            abort(404);
        }

        return $next($request);
    }
}
