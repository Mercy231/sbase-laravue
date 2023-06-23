<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class HelperController extends Controller
{
    protected function getCountries () : JsonResponse
    {
        return response()->json(["countries" => Country::get(["id", "name"])]);
    }
    protected function getStates (Request $request) : JsonResponse
    {
        if (!empty($request->country)) {
            return response()->json(["states" => State::where("country_id", $request->country)
                ->get(["id", "name"])]);
        } else {
            return response()->json(["success" => false]);
        }
    }
    protected function getCities (Request $request) : JsonResponse
    {
        if (!empty($request->state)) {
            return response()->json(["cities" => City::where("state_id", $request->state)
                ->get(["id", "name"])]);
        } else {
            return response()->json(["success" => false]);
        }
    }
    protected function getPermissions (Request $request) : array
    {
        $permissions = Permission::all()->pluck("name")->toArray();
        $rolePermissions = Role::findByName($request->role)->getAllPermissions()->pluck("name")->toArray();
        $response = [];
        foreach ($permissions as $item) {
            if (in_array($item, $rolePermissions)) {
                $response[] = true;
                continue;
            }
            $response[] = false;
        }
        return $response;
    }
    protected function setPermissions (Request $request) : JsonResponse
    {
        $role = Role::findByName($request->role);
        $allPermissions = Permission::all()->pluck("name")->toArray();
        $rolePermissions = array_combine($allPermissions, $request->permissions);
        foreach ($rolePermissions as $key => $value) {
            if ($value) {
                $role->givePermissionTo($key);
            } else {
                $role->revokePermissionTo($key);
            }
        }
        return response()->json(true);
    }
}
