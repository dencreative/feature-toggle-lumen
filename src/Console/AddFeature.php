<?php

namespace CharlGottschalk\FeatureToggleLumen\Console;

use CharlGottschalk\FeatureToggleLumen\Models\Feature;
use Illuminate\Console\Command;

class AddFeature extends Command
{
    protected $signature = 'feature:add {feature} {enabled=true}';

    protected $description = 'Add a new feature toggle';

    public function handle()
    {
        $feature = $this->argument('feature');
        $enabled = $this->argument('enabled') == 'true';

        Feature::on(config('features.connection', config('database.default')))
                ->insert([
                    'name' => $feature,
                    'enabled' => $enabled
                ]);

        $state = $enabled ? 'enabled' : 'disabled';

        $this->info("Feature ({$feature}) created and {$state}");
    }
}
