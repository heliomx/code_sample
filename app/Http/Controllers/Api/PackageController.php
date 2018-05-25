<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Package;

class PackageController extends Controller
{
    public function show(Request $request, $id)
    {
        $package = Package::with('upload')->whereHas('upload',function($query) use($id) { 
                return $query->where('request_id', '=', $id);
            })
            ->first();

        return response()->json(['data' => $package]);
    }
}
