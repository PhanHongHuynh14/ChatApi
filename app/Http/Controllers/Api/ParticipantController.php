<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $participants = DB::table('users')
        ->join('participants', 'participants.user_id', '=' , 'users.id')
        ->select('users.id', 'users.name')
        ->get();
        return response()->json([
            'users' => $participants
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
    public function store(Request $request, $zoom_id)
    {
        try {
            $count = Participant::selectRaw('count(*) as total')
            ->where('zoom_id', $zoom_id)
            ->where('user_id', $request->user_id)
            ->first();
            if($count->total >= 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'You have already in this room.',
                ], 401);
            }

            Participant::create([
                'user_id' => $request->user_id,
                'zoom_id' => $zoom_id,
            ]);

            return response()->json([
                'status' => true,
                    'message' => "Participant created",
                ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
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
