<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Chat;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\ChatDetail;
use App\Models\Class_List;
use App\Models\Faculty;
use App\Models\Lecturer_Degree_Type;
use Illuminate\Support\Facades\Auth;
use DB;

class ChatDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $new = new ChatDetail;
        if($req->type == 'text') {
            $new->message = $req->message;
        } elseif ($req->type == 'image') {
            $image = str_replace('data:image/jpeg;base64,', '', $req->message);
            $image = str_replace(' ', '+', $image);
            $imageName = time().'.'.'jpg';
            Storage::disk('store_message')->put($imageName, base64_decode($image));
            $new->message = 'store/messages/' .$imageName;
        }
        $new->type = $req->type;
        $new->chat_history_id = $req->id_chat;
        $new->sender_id = $req->sender_id;
        $new->time = date("Y-m-d h:i:s");

        if (!$new->save()) return response()->json(['message' => "Error"], 500);
        return response()->json($new, 200);
    }

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
                $faculty = Class_List::find(Student::find($chat->user_2)->class_id)->class_name;
            }
            else{
                $lecturer = Lecturer::find($chat->user_1);
                $name = Lecturer_Degree_Type::find($lecturer->degree)->abbreviation.''.$lecturer->first_name_lecturer .' ' .$lecturer->last_name_lecturer;
                $avatar = $lecturer->avatar;
                $faculty = Faculty::find($lecturer->faculty_id)->faculty_name;
            }
        }
        $details = ChatDetail::where('chat_history_id', $id)->get();
        
        return response()->json([
            'success' => true,
            'name' => $name,
            'avatar' =>$avatar,
            'faculty' =>$faculty,
            'messages' => $details
        ], 200);
        // dd(DB::getQueryLog());
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
