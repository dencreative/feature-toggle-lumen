<?php

use CharlGottschalk\FeatureToggleLumen\Facades\Feature;

if (! function_exists('feature_enabled')) {
    function feature_enabled($feature, $default = true) {
        return Feature::enabled($feature, $default);
    }
}

if (! function_exists('enabled_for')) {
    function enabled_for($feature, $roles = null, $default = true) {
        return Feature::enabledFor($feature, $roles, $default);
    }
}
