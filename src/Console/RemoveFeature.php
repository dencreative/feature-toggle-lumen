<?php

namespace CharlGottschalk\FeatureToggleLumen\Console;

use CharlGottschalk\FeatureToggleLumen\Models\Feature;
use CharlGottschalk\FeatureToggleLumen\Models\FeatureRole;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RemoveFeature extends Command
{
    protected $signature = 'feature:remove {feature}';

    protected $description = 'Remove the given feature';

    public function handle()
    {
        $feature = Str::of($this->argument('feature'))->lower()->snake();

        $featureModel = Feature::on(config('features.connection', config('database.default')))
                                ->where('name', $feature)
                                ->first();

        if (!empty($featureModel)) {

            $featureModel->roles()->detach();
            $featureModel->delete();

            $this->info("Feature ({$feature}) and associated roles removed");
        } else {
            $this->info("Feature ({$feature}) does not exist");
        }
    }
}
