<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildingController extends Controller
{
    public function building(Request $request)
    {
        $community_code = $request->get('q');

        return Building::city()->where('community_code', $community_code)->get('id', 'name');
    }
}
