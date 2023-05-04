<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
