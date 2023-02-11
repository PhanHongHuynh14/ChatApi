<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validator error',
                    'errors' => $validator->error()
                ], 400);
            }
            $user = User::create([
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => true,
                'message' => "User created",
                'token' => $user->createToken("API TOKEN")->plainTextToken,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validator error',
                    'errors' => $validator->error()
                ], 401);
            }

            if (!Auth::attempt($request->only((['name', 'password'])))) {
                return response()->json([
                    'status' => false,
                    'message' =>  'Name & password does not match with our record',
                ], 401);
            }

            $user = User::where('name', $request->name)->first();
            $expired_date = Carbon::now()->addSecond(60)->toDateTimeString();
            $bearerAuth = $user->createToken("API TOKEN")->plainTextToken;
            $tokenID = PersonalAccessToken::all()->last()->id;
            $PAT = PersonalAccessToken::findOrFail($tokenID);
            $PAT->expires_at = $expired_date;
            $PAT->save();
            return response()->json([
                'status' => true,
                'message' => "Login success",
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'expries' => $PAT->expires_at
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'massage' => $th->getMessage(),

            ], 500);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => true,
            'massage' => 'Logout',
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $user->delete();
            return response()->json([
                'status' => true,
                'message' => "Delete Success",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'massage' => $th->getMessage(),

            ], 500);
        }
    }

    public function getUser(Request $request, $id)
    {
        Validator::make($request->all(), [
            'token' => 'required'
        ]);
        $getUser = DB::select('select users.id as id, users.name,zooms.id as idZooms
            from users,zooms
            where users.id = zooms.id
            and users.id = '.$id);

        return response()->json([
            "user" => $getUser
        ]);
    }
    public function getAlluser()
    {
        $user = User::all();
        $collection = Collection::make($user);
        return response()->json([
            "users" => $collection
        ]);
    }

    public function getToken(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tokenID' => 'required',
            ]);

            if($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validator error',
                    'error' => $validator->error()
                ], 401);
            }

            $expired_date = Carbon::now()->addSecond(60)->toDateTimeString();
            $tokenID = PersonalAccessToken::all()->last()->id;
            $PAT = PersonalAccessToken::find($tokenID);
            $user = User::find($PAT->tokenable_id);
            $PAT->expires_at = $expired_date;
            $PAT->save();
            return response()->json([
                [ 'token' =>
                    [
                        'id' => $request->tokenID,
                        'name'=> $user->name,
                        'expries' => $PAT->expires_at
                    ]

                ]
            ],200);
        } catch (\Throwable $th) {
           return response()->json([
                'status' => false,
                'message' => $th->getMessage()
           ], 500);
        }
    }

}
