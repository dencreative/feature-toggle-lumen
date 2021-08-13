<?php

namespace CharlGottschalk\FeatureToggleLumen\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Feature extends Model
{
    // Disable Laravel's mass assignment protection
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * Set the feature's name.
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute(string $value)
    {
        $value = str_replace(' ', '_', $value);
        $this->attributes['name'] = Str::of($value)->lower()->snake();
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(config('features.roles.model'), 'feature_roles', 'feature_id', 'role_id');
    }

    public function enable() {
        $this->enabled = true;
        $this->save();
    }

    public function disable() {
        $this->enabled = false;
        $this->save();
    }
}
