<?php

namespace CharlGottschalk\FeatureToggleLumen;

use CharlGottschalk\FeatureToggleLumen\Models\Feature as FeatureModel;
use CharlGottschalk\FeatureToggleLumen\Models\FeatureRole;
use Illuminate\Support\Str;

class Feature
{
    private static function isOff(): bool
    {
        return !config('features.on');
    }

    private static function allIsOn() {
        return config('features.all_on');
    }

    private static function snake($value)
    {
        $value = str_replace(' ', '_', $value);
        return (string) Str::of($value)->lower()->snake();
    }

    private static function snakeArray($array)
    {
        array_walk($array, function(&$item) {
            $value = str_replace(' ', '_', $item);
            $item = (string) Str::of($value)->lower()->snake();
        });
        return $array;
    }

    private static function getFeature($feature)
    {        $name = self::snake($feature);

        return FeatureModel::on(config('features.connection', config('database.default')))
                            ->with('roles')
                            ->where('name', $name)
                            ->first();
    }

    public static function getUserRole($user) {
        if (empty($user)) {
            return null;
        }

        $roleProp = config('features.roles.property');

        if (property_exists($user, $roleProp)) {
            $roleValue = $user->$roleProp;
        } elseif (method_exists($user, $roleProp)) {
            $roleValue = $user->$roleProp();
        } else {
            $roleValue = null;
        }

        return $roleValue;
    }

    public static function add($feature, $enabled = true)
    {
        return Feature::on(config('features.connection', config('database.default')))
                    ->create([
                        'name' => $feature,
                        'enabled' => $enabled
                    ]);
    }

    public static function remove($feature)
    {
        $featureModel = self::getFeature(($feature));
        $featureModel->roles()->detach();
        $featureModel->delete();
    }

    public static function toggle($feature)
    {
        $featureModel = self::getFeature($feature);
        $featureModel->enabled = !$featureModel->enabled;
        $featureModel->save();
    }

    public static function enable($feature)
    {
        $featureModel = self::getFeature($feature);
        $featureModel->enabled = true;
        $featureModel->save();
    }

    public static function disable($feature)
    {
        $feature = self::getFeature($feature);
        $feature->enabled = false;
        $feature->save();
    }

    public static function addRoles($feature, $roles)
    {
        $featureModel = self::getFeature($feature);

        if(is_array($roles)) {
            $related = [];
            foreach ($roles as $role) {
                $related[] = new FeatureRole(['role' => $role]);
            }

            $featureModel->roles()->saveMany($related);
        } else {
            $role = new FeatureRole(['role' => $roles]);
            $featureModel->roles()->save($role);
        }

        return $featureModel;
    }

    public static function removeRoles($feature, $roles)
    {
        $featureModel = self::getFeature($feature);

        if(is_array($roles)) {
            $roles = self::snakeArray($roles);

            FeatureRole::where('feature_id', $featureModel->id)
                        ->whereIn('role', $roles)
                        ->delete();
        } else {
            $roles = self::snake($roles);

            FeatureRole::where('feature_id', $featureModel->id)
                ->where('role', $roles)
                ->delete();
        }

        return $featureModel;
    }

    public static function enabled($feature, $default = true)
    {
        if(self::isOff()) {
            return self::allIsOn();
        }

        $featureModel = self::getFeature($feature);

        if(empty($featureModel)) {
            return $default;
        }

        return $featureModel->enabled;
    }

    public static function enabledFor($feature, $roles = null, $default = true): bool
    {
        if(self::isOff()) {
            return self::allIsOn();
        }

        $featureModel = self::getFeature($feature);

        if(empty($featureModel)) {
            return $default;
        }

        if (!$featureModel->enabled) {
            return false;
        }

        $featureRoles = $featureModel->roles->pluck(config('features.roles.column'))->toArray();

        if (empty($roles)) {
            $roles = self::getUserRole(auth()->user());
        }

        if(is_array($roles)) {
            $roleAllowed = false;
            foreach ($featureRoles as $featureRole) {
                if(in_array($featureRole, $roles)) {
                    $roleAllowed = true;
                }
            }
            return $roleAllowed;
        } else {
            return in_array($roles, $featureRoles);
        }
    }
}
