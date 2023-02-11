<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Participant;
use Illuminate\Http\Request;
use App\Models\Zoom;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ZoomController extends Controller
{

    public function createZoom(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'massage' => 'Validator error',
                    'error' => $validator->error(),
                ], 401);
            }
            $user = auth('sanctum')->user()->id;
            $data['name'] = $request->input('name');
            $data['user_id'] = $user;
            Zoom::create($data);
            return response()->json([
                'status' => true,
                'message' => "Zoom created",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getZoomAll()
    {
        $zoom = Zoom::all();
        $collection = Collection::make($zoom);
        return response()->json([
            "zooms" => $collection,
        ]);
    }

    public function getZoom(Request $request,  $zoomId, $id)
    {
        try {
            $room = Room::select('id','name')->where('id',$id)->get();
            $user_room = Participant::select('user_id')
                        ->where('room_id',$id)->get();
            $mess = DB::table('users')->join('messages','users.id','=','messages.user_id')
                        ->select('users.username as sender','messages.message')
                        ->where('messages.room_id',$id)->get();

            return response()->json([
                'room' => $room,
                'users'=>$user_room,
                'message'=>$mess,
            ],200);
        }
        catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'User Not Found'
            ], 500);
        }
        // try {
        //     $arr1 = DB::select('select zooms.id as IdZoom,zooms.name as ZoomName from zooms where zooms.id = ' . $zoomId);
        //     $arr2 = DB::select('select participants.user_id as UsersId from participants where participants.zoom_id = ' . $zoomId);
        //     $arr3 = DB::select("select (select name  from users where id = messages.user_id ) as sender,messages.message as message
        //     from messages
        //     where messages.zoom_id = " . $zoomId . "
        //     GROUP BY messages.user_id,messages.message");

        //     $getAllRoom = [];
        //     $users = [];
        //     $mess = [];


        //     foreach ($arr2 as $ar2) {
        //         array_push($users, $arr2);
        //     }
        //     foreach ($arr1 as $arr1) {
        //             foreach ($arr3 as $arr3) {
        //                 array_push($mess, $arr3);
        //                 $getAllRoom = [
        //                     "id" => $arr1->IdZoom,
        //                     "name" =>  $arr1->ZoomName,
        //                     "admin" =>  $arr1->IdZoom,
        //                     "users" => $users,
        //                     "messages" =>  $mess,
        //                 ];
        //             }

        //     }

        //     return response()->json(
        //         ['rooms' => $getAllRoom]
        //     );
        // } catch (\Exception $th) {
        //     return response()->json(['message' => $th->getMessage()]);
        // }
    }

    public function deleteZoom( $zoomId)
    {
        try {
            $zoom = Zoom::find($zoomId);
            $zoom->delete();
            $participants = Participant::where('zoom_id', $zoomId)->first();
            if($participants != null) {
                $participants->delete();
            }
            $mess = Message::where('zoom_id', $zoomId)->first();
            if($mess != null) {
                $mess->delete();
            }
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

}
