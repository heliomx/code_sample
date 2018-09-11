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
        $time = time();
        $path = $request->img->storeAs('public/content', $request->id . "-$time" . '.' . $request->img->extension());
        $path = preg_replace('/public/', '/storage', $path);
        return response()->json( [ 'data' => $path ] );
    }

    public function store(Request $request, $docType)
    {
        $content = Content::firstOrNew(['doc_type' => $docType]);
        $content->doc = $request->doc;
        $content->save();

        return response()->json( [ 'data' => $content ]);
    }

    public function show(Request $request, $docType)
    {
        $content = Content::firstOrNew(['doc_type' => $docType]);
        return response()->json( ['data' => $content]);
    }
}