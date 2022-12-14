<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create User
     * @param CreateUserRequest $request
     * @return  User
     *
     */
    public function createUser(CreateUserRequest $request){

        try{
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password)
            ]);

            return response()->json([
                "status" => true,
                "message" => "user create successfull",
                "data" => $user,
                "token" => $user->createToken("MY TOKEN")->plainTextToken
            ], 200);

        }catch(\Throwable $throwable){
            return response()->json([
                "status" => false,
                "message" => $throwable->getMessage(),
            ], 500);
        }

    }

    /**
     * Login the user
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        $validateUser = null;

        try{
            $validateUser = Validator::make($request->all(),
                [
                    "email" => "required|email",
                    "password" => "required|min:8"
                ],
                [
                    "email.required" => "Il campo email è obbligatorio",
                    "email.email" => "Il formato email non è valido",
                    "password.required" => "Il campo password è obbligatorio",
                    "password.min" => "Il campo password minimo 8 caratteri"
                ]
            );

            if($validateUser->fails()){
                return response()->json([
                    "status" => false,
                    "message" => "validation error",
                    "errors" => $validateUser->errors()
                ]);
            }

            if(!Auth::attempt($request->only(["email","password"]))){
                return response()->json([
                    "status" => false,
                    "message" => "Email e Password does not match with our record"
                ], 401);
            }

            $user = User::where("email", $request->email)->first();

            return response()->json([
                "status" => true,
                "message" => "User logged in successful",
                "token" => $user->createToken("MY TOKEN")->plainTextToken
            ], 200);

        }catch(\Throwable $throwable){
            return response()->json([
                "status" => false,
                "message" => $throwable->getMessage(),
                "errors" =>$validateUser->errors()
            ], 500);
        }

    }


}
