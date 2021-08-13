<?php

namespace CharlGottschalk\FeatureToggleLumen\Console;

use CharlGottschalk\FeatureToggleLumen\Models\Feature;
use CharlGottschalk\FeatureToggleLumen\Models\FeatureRole;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AddRoleToFeature extends Command
{
    protected $signature = 'feature:add:role {feature} {role}';

    protected $description = 'Add a role to the given feature';

    public function handle()
    {
        $feature = Str::of($this->argument('feature'))->lower()->snake();
        $role = $this->argument('role');

        $featureModel = Feature::on(config('features.connection', config('database.default')))
                            ->where('name', $feature)
                            ->first();

        if (!empty($featureModel)) {
            $featureRole = config('features.roles.model')::where(config('features.roles.column'), $role)->first();

            $featureModel->roles()->attach($featureRole->id);

            $this->info("Role ({$role}) added to feature ({$feature})");
        } else {
            $this->info("Feature ({$feature}) does not exist");
        }
    }
}
