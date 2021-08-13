<?php

namespace CharlGottschalk\FeatureToggleLumen\Http\Controllers;

use CharlGottschalk\FeatureToggleLumen\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeaturesController extends BaseController
{
    public function index()
    {
        $features = Feature::on(config('features.connection', config('database.default')))
                                ->orderBy('name')
                                ->get();
        return response()->json($features);
    }

    public function show(Request $request, $id)
    {
        $roles = config('features.roles.model')::orderBy(config('features.roles.column'))->get();
        $feature = Feature::on(config('features.connection', config('database.default')))
            ->with('roles')
            ->find($id);

        $linkedRoles = [];
        foreach ($roles as $role) {
            $linked = false;

            if($feature->roles->contains(function ($value) use ($role) {
                return $role->id == $value->id;
            })) {
                $linked = true;
            }

            $linkedRoles[] = [
                'linked' => $linked,
                'role' => $role
            ];
        }

        return response()->json(['feature' => $feature, 'linkedRoles' => $linkedRoles]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:CharlGottschalk\FeatureToggleLumen\Models\Feature'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed'], 400);
        }

        $feature = Feature::on(config('features.connection', config('database.default')))
            ->insert([
                'name' => $request->input('name'),
                'enabled' => $request->has('enabled')
            ]);

        return response()->json(['feature' => $feature]);
    }

    public function update(Request $request, $id)
    {
        $feature = Feature::on(config('features.connection', config('database.default')))
                            ->find($id);
        $feature->roles()->sync($request->input('roles'));
        return response()->json(['feature' => $feature]);
    }

    public function delete(Request $request, $id)
    {
        try {
            $feature = Feature::on(config('features.connection', config('database.default')))
                ->find($id);
            $feature->roles()->detach();
            $feature->delete();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not delete feature'], 500);
        }

        return response()->json(['success' => true]);
    }

    public function enable(Request $request, $id)
    {
        $feature = Feature::on(config('features.connection', config('database.default')))
                            ->find($id);
        $feature->enable();
        return response()->json(['success' => true]);
    }

    public function disable(Request $request, $id)
    {
        $feature = Feature::on(config('features.connection', config('database.default')))
                            ->find($id);
        $feature->disable();
        return response()->json(['success' => true]);
    }
}
