<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ImagesController extends Controller
{
    public function upload(Request $request, $id)
    {
        $user = User::find($id);
        if (count($request->images)) {
            foreach ($request->images as $image) {
                $user->image = $image->store('images');
            }
        }


        return response()->json([
            "message" => "Done"
        ]);
    }
}
