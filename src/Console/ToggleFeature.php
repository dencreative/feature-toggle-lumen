<?php

namespace CharlGottschalk\FeatureToggleLumen\Console;

use CharlGottschalk\FeatureToggleLumen\Models\Feature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ToggleFeature extends Command
{
    protected $signature = 'feature:toggle {feature}';

    protected $description = 'Toggle the given feature';

    public function handle()
    {
        $feature = Str::of($this->argument('feature'))->lower()->snake();
        $featureModel = Feature::on(config('features.connection', config('database.default')))
                                ->where('name', $feature)
                                ->first();

        if (!empty($featureModel)) {
            $featureModel->enabled = !$featureModel->enabled;
            $featureModel->save();

            $state = $featureModel->enabled ? 'enabled' : 'disabled';

            $this->info("Feature ({$feature}) {$state}");
        } else {
            $this->info("Feature ({$feature}) does not exist");
        }
    }
}
