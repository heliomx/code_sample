<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterFormRequest;
use App\User;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Mail;
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
        $user->role = 'A';
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

    public function indexUser(Request $request)
    {

        if (strlen($request->input('q')) > 0) {
            $query = User::where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('email', 'LIKE', '%' . $request->input('q') . '%');
            })->whereRole('A');
            
            $count = User::whereRole('A')->where('name', 'LIKE', '%' . $request->input('q') . '%')
            ->orWhere('email', 'LIKE', '%' . $request->input('q') . '%')
            ->count();
        } else {
            $count = User::whereRole('A')->count();
            $query = User::whereRole('A');
        }

        $query->limit($request->input('rowsPerPage'))
            ->offset($request->input('rowsPerPage') * ($request->input('page') - 1));
        
        if ($request->has('sortBy')) {
            $query->orderBy($request->input('sortBy'), $request->input('descending') == 'true' ? 'DESC' : 'ASC');
        }

        
        
        $data = $query->get();

        return response()->json( [ 'items' => $data, 'total' => $count, 'sql' => $query->toSql() ] );
    }

    public function show(Request $request, $id)
    {
        $user = User::find($id);
        return response([
                'status' => 'success',
                'data' => $user
            ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::destroy($id);
        return response([
            'status' => 'success'
        ]);
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response([
                'status' => 'success',
                'data' => $user
            ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->email = $request->email;
        $user->name = $request->name;
        if (!empty($request->password))
        {
            $user->password = bcrypt($request->password);
        }
            
        $user->save();

        return response([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function forgotPassword(Request $request)
    {
        $user = User::whereEmail($request->email)->first();
        $r = [ "success" => false ];
        if ($user)
        {
            $user->forgot_pwd_token = str_random();
            $user->save();
            Mail::to($user)->send(new ForgotPassword($user));
            $r = [ "success" => true ];
        }

        return response()->json($r);
    }

    public function userByToken(Request $request) 
    {
        $user = User::whereForgotPwdToken($request->input('pwd_token'))->first();
        $r = [ "success" => false ];

        if ($user)
        {
            $r['success'] = true;
            $r['data'] = $user;
        }

        return response()->json($r);
    }

    public function updateForgottenPassword(Request $request)
    {
        $user = User::whereForgotPwdToken($request->input('pwd_token'))->first();
        $r = [ "success" => false ];
        if ($user)
        {
            $user->forgot_pwd_token = '';
            $user->password = bcrypt($request->password);
            $user->save();
            $r = [ "success" => true ];
        }

        return response()->json($r);
    }

    public function refresh()
    {
        return response([
                'status' => 'success'
            ]);
    }
}
