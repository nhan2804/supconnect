<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\ChatDetail;
use Illuminate\Support\Facades\Auth;
use DB;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        // Auth::user()->account_id chưa có login
        $id_user = 4;
        // DB::enableQueryLog(); // Enable query log
        $chats = Chat::where('user_1', $id_user)->with(['user'])->paginate(20);
        // Your Eloquent query executed by using get()

        // dd(DB::getQueryLog()); // Show results of log

        return response()->json($chats, 200);
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
    public function store(Request $r)
    {
        // Auth::user()->account_id chưa có login
        $id_user = 4;
        $new = new Chat;
        $new->user_1 = $id_user;
        $new->user_2 = $r->id;
        if ($new->save()) return response()->json(['message' => "Tạo thành công"], 200);
        return response()->json(['message' => "Có lỗi xảy ra, vui lòng thử lại"], 500);
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
        $details = ChatDetail::where('chat_history_id', $id);
        return response()->json($details, 200);
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
