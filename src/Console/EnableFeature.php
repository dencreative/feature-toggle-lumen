<?php

namespace CharlGottschalk\FeatureToggleLumen\Console;

use CharlGottschalk\FeatureToggleLumen\Models\Feature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class EnableFeature extends Command
{
    protected $signature = 'feature:enable {feature}';

    protected $description = 'Enable the given feature';

    public function handle()
    {
        $feature = Str::of($this->argument('feature'))->lower()->snake();
        $featureModel = Feature::on(config('features.connection', config('database.default')))
                                ->where('name', $feature)
                                ->first();

        if (!empty($featureModel)) {
            $featureModel->enabled = true;
            $featureModel->save();

            $this->info("Feature ({$feature}) enabled");
        } else {
            $this->info("Feature ({$feature}) does not exist");
        }
    }
}
