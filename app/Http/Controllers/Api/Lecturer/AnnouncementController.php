<?php

namespace App\Http\Controllers\Api\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Announcement\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

     /**
      * type: type of the announcement. There is 6 type in database.
        title: title of the announcement
        description: content of the announcement
        attachment: additional files. currently unsupported.
      */
    public function store(Request $r)
    {
        $new = new Announcement;
        $new->announcement_type_id = $r->type;
        $new->title = $r->title;
        $new->create_date = date('Y-m-d H:i:s');
        $new->description = $r->description;
        $new->attachment = 'none';
        if ($new->save()) 
            return response()->json([
                'success' => true,
                'message' => 'Announcement sent',
                'announcement' => $new
            ], 200);
        
        return response()->json([
            'message' => 'Error, Announcement cancelled'
            ], 500);
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
