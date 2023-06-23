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
                $response[] = ["name" => $item, "value" => true];
                continue;
            }
            $response[] = ["name" => $item, "value" => false];
        }
        return $response;
    }
    protected function setPermissions (Request $request) : JsonResponse
    {
        $role = Role::findByName($request->role);
        foreach ($request->permissions as $item) {
            if ($item["value"]) {
                $role->givePermissionTo($item["name"]);
            } else {
                $role->revokePermissionTo($item["name"]);
            }
        }
        return response()->json(true);
    }
    protected function getRoles () : array
    {
        return Role::whereNot("name", "Admin")->orderBy("id")->get()->toArray();
    }
}
