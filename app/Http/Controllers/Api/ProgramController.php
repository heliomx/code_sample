<?php

namespace App\Http\Controllers\Api;

use App\Program;
use App\ProgramFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('downloads') && $request->input('downloads'))
        {
            $programs = Program::with('files')->whereHas('files', function ($query) {
                return $query->whereStatus(ProgramFile::STATUS_PUBLISHED);
            })->get();
            $r = response()->json([ 'data' => $programs ]);
        } else {
            $r = response()->json([ 'data' => Program::all()]);
        }
        return $r;
    }

    public function download(Request $request, $id)
    {
        $file = ProgramFile::find($id);
        return Storage::drive('packages')->download($file->file_name);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return response()->json( [ 'data' => Program::find($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $program = Program::find($id);
        
        $path = $request->file('img')->store('public/programs');
        $program->fill([
            'name'          => $request->input('name'),
            'description'   => $request->input('description'),
            'program_type'  => $request->input('program_type'),
            'img'           => $path
        ]);
        

        $program->save();

        return response()->json( [ 'data' => $program ] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Program $program)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        Program::find($id)->delete();
        return response()->json(['success' => true]);
    }
}
