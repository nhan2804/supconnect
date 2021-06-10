<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Student;
use App\Models\Lecturer;
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

    public function index(Request $req)
    {

        $chats = Chat::where('user_1', $req->input('user_id'))
                        ->orWhere('user_2', $req->input('user_id'))
                        ->get();

        foreach($chats as $chat) {
            $message = ChatDetail::where('chat_history_id', $chat->chat_history_id)
            ->orderBy('chat_history_detail_id', 'desc')
            ->first();
            $chat->message = $message->message;
            $chat->messageTime = $message->time;
            if($req->input('user_id') == $chat->user_1) {
                if(Student::find($chat->user_2)!=null){
                    $name = Student::find($chat->user_2)->first_name .' ' .Student::find($chat->user_2)->last_name;
                    $avatar = Student::find($chat->user_2)->avatar;
                }
                else{
                    $name = Lecturer::find($chat->user_2)->first_name_lecturer .' ' .Lecturer::find($chat->user_2)->last_name_lecturer;
                    $avatar = Lecturer::find($chat->user_2)->avatar;
                }
            } else {
                if(Student::find($chat->user_1)!=null){
                    $name = Student::find($chat->user_1)->first_name .' ' .Student::find($chat->user_1)->last_name;
                    $avatar = Student::find($chat->user_1)->avatar;
                }
                if($name == '') {
                    $name = Lecturer::find($chat->user_1)->first_name_lecturer .' ' .Lecturer::find($chat->user_1)->last_name_lecturer;
                    $avatar = Lecturer::find($chat->user_1)->avatar;
                }
            }

            $chat->name = $name;
            $chat->avatar = $avatar;
        }

        return response()->json([
            'success' => true,
            'chat' => $chats
        ], 200);
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
    public function store(Request $req)
    {

        $new = new Chat;
        $new->user_1 = $req->user_id1;
        $new->user_2 = $req->user_id2;
        if ($new->save()) return response()->json(['message' => "Tạo thành công"], 200);
        return response()->json(['message' => "Có lỗi xảy ra, vui lòng thử lại"], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $req)
    {

        $chat = Chat::find($id);
        if($req->input('user_id') == $chat->user_1) {
            if(Student::find($chat->user_2)!=null){
                $name = Student::find($chat->user_2)->first_name .' ' .Student::find($chat->user_2)->last_name;
                $avatar = Student::find($chat->user_2)->avatar;
            }
            else{
                $name = Lecturer::find($chat->user_2)->first_name_lecturer .' ' .Lecturer::find($chat->user_2)->last_name_lecturer;
                $avatar = Lecturer::find($chat->user_2)->avatar;
            }
        } else {
            if(Student::find($chat->user_1)!=null){
                $name = Student::find($chat->user_1)->first_name .' ' .Student::find($chat->user_1)->last_name;
                $avatar = Student::find($chat->user_1)->avatar;
            }
            else{
                $name = Lecturer::find($chat->user_1)->first_name_lecturer .' ' .Lecturer::find($chat->user_1)->last_name_lecturer;
                $avatar = Lecturer::find($chat->user_2)->avatar;
            }
        }

        $messages = ChatDetail::where('chat_history_id', $id)->get();
        return response()->json([
            'success' => true,
            'name' => $name,
            'avatar' =>$avatar,
            'messages' => $messages
        ], 200);
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
