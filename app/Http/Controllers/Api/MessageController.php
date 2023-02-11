<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($zoom_id, $user_id)
    {

        $messages = DB::table('messages')
        ->select('id', 'message', 'user_id', 'zoom_id')
        ->where('zoom_id',$zoom_id)
        ->where('user_id', $user_id)
        ->get();

        return response()->json([
            'messages' => $messages
        ]);
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
    public function store(Request $request, $user_id, $zoom_id)
    {
        try {
            Validator::make($request->all(), [
                'message' => 'required',
            ]);

            Message::create([
                'user_id' => $user_id,
                'zoom_id' => $zoom_id,
                'message' => $request->message
            ]);

            return response()->json([
                'status' => true,
                'message' => "Message created",
            ], 200);

        } catch (\Throwable $th) {
           return response()->json([
            'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function getZoom($zoom_id)
    {
        $messages = DB::table('messages')
        ->select('id','message','zoom_id')
        ->where('zoom_id', $zoom_id)
        ->get();

        return response()->json([
            'messages' => $messages
        ]);

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
