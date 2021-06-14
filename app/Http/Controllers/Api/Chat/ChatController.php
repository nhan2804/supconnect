<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\ChatDetail;
use App\Models\Class_List;
use App\Models\Faculty;
use App\Models\Lecturer_Degree_Type;
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

        foreach($chats as $key => $chat) {
            $message = ChatDetail::where('chat_history_id', $chat->chat_history_id)
            ->orderBy('chat_history_detail_id', 'desc')
            ->first();
            if($message==null){
                unset($chats[$key]);
            }
            else{
                if($message->type = 'image') {
                $chat->message = 'Hình ảnh';
                } elseif($message->type = 'text') {
                    $chat->message = $message->message;
                }
                $chat->messageTime = $message->time;
                if($req->input('user_id') == $chat->user_1) {
                    if(Student::find($chat->user_2)!=null){
                        $name = Student::find($chat->user_2)->first_name .' ' .Student::find($chat->user_2)->last_name;
                        $avatar = Student::find($chat->user_2)->avatar;
                    }
                    else{
                        $lecturer = Lecturer::find($chat->user_2);
                        $name = Lecturer_Degree_Type::find($lecturer->degree)->abbreviation.''.$lecturer->first_name_lecturer .' '.$lecturer->last_name_lecturer;
                        $avatar = Lecturer::find($chat->user_2)->avatar;
                    }
                } else {
                    if(Student::find($chat->user_1)!=null){
                        $name = Student::find($chat->user_1)->first_name .' ' .Student::find($chat->user_1)->last_name;
                        $avatar = Student::find($chat->user_1)->avatar;
                    }
                    if($name == '') {
                        $lecturer = Lecturer::find($chat->user_1);
                        $name = Lecturer_Degree_Type::find($lecturer->degree)->abbreviation.''.$lecturer->first_name_lecturer .' ' .$lecturer->last_name_lecturer;
                        $avatar = Lecturer::find($chat->user_1)->avatar;
                    }
                }

                $chat->name = $name;
                $chat->avatar = $avatar;
            }

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

    function checkIsValidRoom($user_1, $user_2) {
        $room = Chat::where([
            ['user_1', '=', $user_1 ],
            ['user_2', '=', $user_2 ]
        ])->orWhere([
            ['user_1', '=', $user_2 ],
            ['user_2', '=', $user_1 ]
        ])->first();
        $isHasRoom = false;
        if(isset($room)) {
            $isHasRoom = true;
            return $room->chat_history_id;
        }

        if(!$isHasRoom) {
            $room = new Chat();
            $room->user_1 = $user_1;
            $room->user_2 = $user_2;
            $room->save();
            return $room->id;
        }
    }

    public function show($id, Request $req)
    {
        if($id == 0) {
            $id = $this->checkIsValidRoom($req->user_1, $req->user_2);
        }
        $chat = Chat::find($id);
        if($req->input('user_1') == $chat->user_1) {
            if(Student::find($chat->user_2)!=null){
                $name = Student::find($chat->user_2)->first_name .' '.Student::find($chat->user_2)->last_name;
                $avatar = Student::find($chat->user_2)->avatar;
                $faculty = Class_List::find(Student::find($chat->user_2)->class_id)->class_name;
            }
            else{
                $lecturer = Lecturer::find($chat->user_2);
                $name = Lecturer_Degree_Type::find($lecturer->degree)->abbreviation.''.$lecturer->first_name_lecturer .' ' .$lecturer->last_name_lecturer;
                $avatar = $lecturer->avatar;
                $faculty = 'Khoa '.Faculty::find($lecturer->faculty_id)->faculty_name;
            }
        } else {
            if(Student::find($chat->user_1)!=null){
                $name = Student::find($chat->user_1)->first_name .' '.Student::find($chat->user_1)->last_name;
                $avatar = Student::find($chat->user_1)->avatar;
                $faculty = Class_List::find(Student::find($chat->user_1)->class_id)->class_name;
            }
            else{
                $lecturer = Lecturer::find($chat->user_1);
                $name = Lecturer_Degree_Type::find($lecturer->degree)->abbreviation.''.$lecturer->first_name_lecturer .' ' .$lecturer->last_name_lecturer;
                $avatar = $lecturer->avatar;
                $faculty = Faculty::find($lecturer->faculty_id)->faculty_name;
            }
        }

        $messages = ChatDetail::where('chat_history_id', $id)->get();
        return response()->json([
            'success' => true,
            'name' => $name,
            'avatar' =>$avatar,
            'faculty' =>$faculty,
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
