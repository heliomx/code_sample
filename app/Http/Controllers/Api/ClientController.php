<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Download;
use Log;
use Auth;
use App\User;
use App\Http\Resources\Client as ClientResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Exception;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Client::with('user');
        if ($request->has('q')) {
            if (strlen($request->input('q')) > 2) {
                $query->where('radio_name', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('address_city', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('annotations', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhereHas('user', function ($q) use($request) {
                    return $q->where('email', 'LIKE', '%' . $request->input('q') . '%');
                });
                
                $count = Client::where('radio_name', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('address_city', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('annotations', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhereHas('user', function ($q) use($request){
                    return $q->where('email', 'LIKE', '%' . $request->input('q') . '%');
                })
                ->count();
            } else {
                $query->where('address_uf', 'LIKE', '%' . $request->input('q') . '%');
                $count = Client::where('address_uf', 'LIKE', '%' . $request->input('q') . '%')->count();
            }
        } else {
            $count = Client::count();
        }

        if ($request->input('rowsPerPage') != -1)
        {
            $query->limit($request->input('rowsPerPage'))
                ->offset($request->input('rowsPerPage') * ($request->input('page') - 1));    
        }
        
        
        if ($request->has('sortBy')) {
            $query->orderBy($request->input('sortBy'), $request->input('descending') == 'true' ? 'DESC' : 'ASC');
        }

        
        
        $data = ClientResource::collection($query->get());

        return response()->json( [ 'items' => $data, 'total' => $count  ] );
    }

    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        return response()->json(['used' => User::whereEmail($email)->count() >= 1]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->input('user.name'),
                'email' => $request->input('user.email'),
                'role'  => 'C',
                'password' => bcrypt($request->input('user.password')),
            ]);
            
            $client = Client::create([
                'user_id'       => $user->id,
                'radio_type'    => $request->input('radio_type'),
                'radio_name'    => $request->input('radio_name'),
                'cpf'  => $request->input('cpf'),
                'cnpj'  => $request->input('cnpj'),
                'address'   => $request->input('address'),
                'address_cep' => $request->input('address_cep'),
                'address_complement'    => $request->input('address_complement'),
                'address_city' => $request->input('address_city'),
                'address_uf'    => $request->input('address_uf'),
                'tel' => $request->input('tel'),
                'tel_mobile'   => $request->input('tel_mobile'),
                'site' => $request->input('site'),
                'status' => $request->input('status', Client::STATUS_ACTIVE),
                'annotations' => $request->input('annotations'),
            ]);

            $programs = $request->input('programs');
            
            $client->programs()->attach($request->input('programs'));
            DB::commit();
            $r = new ClientResource(Client::with('user', 'programs')->find($client->id));
        } catch ( \Exception $e ) {
            DB::rollback();
            throw $e;
        }
        
        return $r;
    }

    public function downloadHistory(Request $request, $id)
    {
        $data = Download::with('programFile')
            ->whereClientId($id)
            ->limit($request->input('rowsPerPage'))
            ->offset($request->input('rowsPerPage') * ($request->input('page') - 1))
            ->orderBy('download_date', 'desc')
            ->get();
        $count = Download::with('programFile')
            ->whereClientId($id)->count();

        return [ 'items' => $data, 'total' => $count ];
    }

   
    public function show(Request $request, $id)
    {
        $user = Auth::user();
        if($user->role == User::ROLE_ADMIN || ($user->role != User::ROLE_ADMIN && $id == $user->client_id))
        {
            $client = Client::with('user', 'programs')->find($id);
            $data = new ClientResource($client, $user->role == User::ROLE_ADMIN);
            return $data;
        } else {
            abort(403, 'Unauthorized action.');
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $client = Client::find($id);
            $client->fill([
                'radio_type'    => $request->input('radio_type'),
                'radio_name'    => $request->input('radio_name'),
                'cpf'  => $request->input('cpf'),
                'cnpj'  => $request->input('cnpj'),
                'address'   => $request->input('address'),
                'address_cep' => $request->input('address_cep'),
                'address_complement'    => $request->input('address_complement'),
                'address_city' => $request->input('address_city'),
                'address_uf'    => $request->input('address_uf'),
                'tel' => $request->input('tel'),
                'tel_mobile'   => $request->input('tel_mobile'),
                'site' => $request->input('site'),
                'status' => $request->input('status', Client::STATUS_ACTIVE),
                'annotations' => $request->input('annotations'),
            ]);
           
            $client->user->fill([
                'name' => $request->input('user.name'),
                'email' => $request->input('user.email'),
                'role'  => 'C'
            ]);
            if (!empty($request->input('user.password')))
            {
                $client->user->password = bcrypt($request->input('user.password'));
            }
            $client->user->save();

            $client->programs()->detach();
            $client->programs()->attach($request->input('programs'));

            $client->save();
            $r = new ClientResource(Client::with('user', 'programs')->find($client->id));
            DB::commit();
        } catch ( \Exception $e ) {
            DB::rollback();
            throw $e;
        }
        
        return $r;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        Client::find($id)->delete();
        return response()->json(['success' => true]);
    }
}