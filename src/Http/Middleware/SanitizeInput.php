<?php

namespace CharlGottschalk\FeatureToggleLumen\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class SanitizeInput
{
    public function handle($request, Closure $next)
    {
        $input = $request->all();

        array_walk($input, function(&$value, $key) {

            if($key == 'name') {
                $value = str_replace(' ', '_', $value);
                $value = (string) Str::of($value)->lower()->snake();
            }

        });

        $request->merge($input);

        return $next($request);
    }
}
