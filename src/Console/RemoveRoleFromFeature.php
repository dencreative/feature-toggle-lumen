<?php

namespace CharlGottschalk\FeatureToggleLumen\Console;

use CharlGottschalk\FeatureToggleLumen\Models\Feature;
use CharlGottschalk\FeatureToggleLumen\Models\FeatureRole;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RemoveRoleFromFeature extends Command
{
    protected $signature = 'feature:remove:role {feature} {role}';

    protected $description = 'Remove a role to the given feature';

    public function handle()
    {
        $feature = Str::of($this->argument('feature'))->lower()->snake();
        $role = $this->argument('role');

        $featureModel = Feature::on(config('features.connection', config('database.default')))
                                ->where('name', $feature)
                                ->first();

        if (!empty($featureModel)) {
            $featureRole = config('features.roles.model')::where(config('features.roles.column'), $role)->first();

            if (!empty($featureRole)) {
                $featureModel->roles()->detach($featureRole->id);

                $this->info("Role ({$role}) removed from feature ({$feature})");
            } else {
                $this->info("Role ({$role}) for feature ({$feature}) does not exist");
            }
        } else {
            $this->info("Feature ({$feature}) does not exist");
        }
    }
}
