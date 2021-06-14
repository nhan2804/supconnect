<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Announcement_Type;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $announcements =
            Announcement::join(
                'announcement_type',
                'announcement_type.announcement_type_id',
                'announcement.announcement_type_id'
            )
            ->orderBy('announcement.announcement_id', 'desc')
            ->get();


        return response()->json([
            'success' => true,
            'announcement' => $announcements
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $announcement = Announcement::join(
            'announcement_type',
            'announcement_type.announcement_type_id',
            'announcement.announcement_type_id'
        )
            ->orderBy('announcement.announcement_id', 'desc')
            ->where('announcement_id', $id)
            ->get();
        return response()->json([
            'success' => true,
            'announcement' => $announcement
        ]);
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
