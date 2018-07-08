<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterFormRequest;
use App\User;
use App\Client;
use Auth;

class AuthController extends Controller
{
    public function register(RegisterFormRequest $request)
    {
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();
        return response([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ( ! $token = auth()->attempt($credentials)) {
                return response([
                    'status' => 'error',
                    'error' => 'invalid.credentials',
                    'msg' => 'Invalid Credentials.'
                ], 400);
        }
        $client = Client::whereUserId(Auth::user()->id)->first();
        if($client){
            switch ($client->status) {
                case 'M':
                    $client->status = 'A';
                    $client->save();
                    $response = [
                        'status' => 'success'
                    ];
                    break;
                
                case 'I':
                    auth()->logoff();
                    $response = [
                        'status' => 'error',
                        'msg' => 'Cliente inativo'
                    ];
                
                default:
                    $response = [
                        'status' => 'success'
                    ];
                    break;
            }
        } else {
            $user = User::find(Auth::user()->id);
            if($user->role == 'A')
            {
                $response = [
                    'status' => 'success'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'msg' => 'Usuário inválido. Verifique o banco de dados'
                ];
                auth()->logoff();
            }
        }
        return response($response)
            ->header('Authorization', $token);
    }

    public function logout()
    {
        auth()->logoff();
        return response([
                'status' => 'success',
                'msg' => 'Logged out Successfully.'
            ], 200);
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response([
                'status' => 'success',
                'data' => $user
            ]);
    }
    public function refresh()
    {
        return response([
                'status' => 'success'
            ]);
    }
}
