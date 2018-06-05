<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\User;
use App\Client;
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
            $user = Auth::user();
            if ($user->role == User::ROLE_ADMIN)
            {
                $programs = Program::with('files')->whereHas('files', function ($query) {
                    return $query->whereStatus(ProgramFile::STATUS_PUBLISHED);
                })->get();
                $r = response()->json([ 'data' => $programs ]);
            } else {
                $client = Client::whereUserId($user->id)->first();
                $actives = $client->programs()
                    ->whereStatus('A')
                    ->with('files')
                    ->whereHas('files', function ($q) {
                        return $q->whereStatus(ProgramFile::STATUS_PUBLISHED);
                    })->get();

                $waiting = $client->programs()
                    ->whereStatus('D')
                    ->with('files')
                    ->get();

                $r = response()->json([ 'data' => [ 'actives' => $actives, 'waiting' => $waiting ]]);
            }
            
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
        $program = new Program();
        
        $path = $request->has('img') ? $request->file('img')->store('public/programs') : '';
        $program->fill([
            'name'          => $request->input('name'),
            'description'   => $request->input('description'),
            'program_type'  => $request->input('program_type'),
            'publication_days'  => $request->input('publication_days'),
            'img'           => $path
        ]);
        

        $program->save();

        return response()->json( [ 'data' => $program ] );
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
        
        $path = $request->has('img') ? $request->file('img')->store('public/programs') : '';
        $program->fill([
            'name'          => $request->input('name'),
            'description'   => $request->input('description'),
            'program_type'  => $request->input('program_type'),
            'publication_days'  => $request->input('publication_days'),
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
