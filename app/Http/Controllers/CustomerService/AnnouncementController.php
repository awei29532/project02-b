<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::select('*');

        $status = $request->status ?? 'all';
        if ($status != 'all') {
            $query->where('status', $status);
        }

        $res = $query->paginate($request->per_page);

        return $this->returnPaginate(
            $res->map(function (Announcement $row) {
                return [
                    'id' => $row->id,
                    'title' => $row->title,
                    'content' => $row->content,
                    'status' => $row->status,
                    'udpated_at' => $row->udpated_at,
                    'created_at' => $row->created_at,
                ];
            }),
            $res
        );
    }

    public function show($id)
    {
        $res = Announcement::findOrFail($id);

        return $this->returnData($res);
    }

    public function store(Request $request)
    { }

    public function update(Request $request, $id)
    { }

    public function destroy($id)
    { }
}
