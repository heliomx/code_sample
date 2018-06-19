<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\User;
use App\Content;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{

    public function storeImage(Request $request, $docType)
    {
        $path = $request->img->storeAs('public/content', $request->id . '.' . $request->img->extension());
        $path = preg_replace('/public/', '/storage', $path);
        return response()->json( [ 'data' => $path ] );
    }

    public function store(Request $request, $docType)
    {
        $content = Content::whereDocType($docType)->first();
        $content->doc = $request->doc;
        $content->save();

        return response()->json( [ 'data' => $content ]);
    }

    public function show(Request $request, $docType)
    {
        $content = Content::whereDocType($docType)->first();

        return response()->json( ['data' => $content]);
    }
}