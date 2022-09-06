<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $params = $request -> validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'        
        ]);
 
        $user = User::create([
            'name' => $params['name'],
            'email' => $params['email'],
            'password' => bcrypt($params['password']),
            'priority' => 1
        ]);

        $response =[
            'user' => $user
        ];
 
        return response($response, 201);
    }
    public function createOpenApiKey(Request $request)
    {
        $params = $request->validate([
            "email" => "required|string",
            // "password" => "required|string"
        ]);

        // Check api key is admin
        if (auth()->user()->priority == 1)
        {
            return response([
                'message' => "Unauthorized"
            ], 401);
        }

        // Check email
        $user = User::where('email', $params['email'])->first();

        // Check it is normal user
        if (!$user || $user->priority == 0)
        {
            return response([
                'message' => "Not Acceptable"
            ], 406);
        }

        // Check user's token already exist
        if (count($user->tokens))
        {
            return response([
                'message' => "Conflict"
            ], 409);
        }

        $openApiKey = $user->createToken('openApiKey')->plainTextToken;

        $response = [
            'user' => $user,
            'openApiKey' => $openApiKey
        ];

        return response($response, 201);
        // Check password
        //if (!$user || !Hash::check($params['password'], $user->password) || $user->priority == 1)
        //{
        //    return response([
        //        'message' => "Unauthorized"
        //    ], 401);
        //}
    }

    public function createAdminUser(Request $request)
    {
        $params = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        // Check api key is admin
        if (auth()->user()->priority == 1)
        {
            return response([
                'message' => "Unauthorized"
            ], 401);
        }

        // Check email
        $user = User::create([
            'name' => $params['name'],
            'email' => $params['email'],
            'password' => bcrypt($params['password']),
            'priority' => 0
        ]);
        
        $response = [
            'user' => $user,
        ];

        return response($response, 201);
    }
    
    public function createAdminApiKey(Request $request)
    {
        $params = $request->validate([
            "email" => "required|string",            
        ]);

        // Check api key is admin
        if (auth()->user()->priority == 1)
        {
            return response([
                'message' => "Unauthorized"
            ], 401);
        }

        $user = User::where('email', $params['email'])->first();

        // Check it is admin user
        if (!$user || $user->priority == 1)
        {
            return response([
                'message' => "Not Acceptable"
            ], 406);
        }
        // Check user's token already exist
        if (count($user->tokens))
        {
            return response([
                'message' => "Conflict"
            ], 409);
        }

        $adminApiKey = $user->createToken('adminApiKey')->plainTextToken;

        $response = [
            'user' => $user,
            'adminApiKey' => $adminApiKey
        ];

        return response($response, 201);        
    }
}
