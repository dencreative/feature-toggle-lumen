<?php

namespace CharlGottschalk\FeatureToggleLumen\Console;

use CharlGottschalk\FeatureToggleLumen\Models\Feature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class DisableFeature extends Command
{
    protected $signature = 'feature:disable {feature}';

    protected $description = 'Disable the given feature';

    public function handle()
    {
        $feature = Str::of($this->argument('feature'))->lower()->snake();
        $featureModel = Feature::on(config('features.connection', config('database.default')))
                                ->where('name', $feature)
                                ->first();

        if (!empty($featureModel)) {
            $featureModel->enabled = false;
            $featureModel->save();

            $this->info("Feature ({$feature}) disabled");
        } else {
            $this->info("Feature ({$feature}) does not exist");
        }
    }
}
