<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'item_per_page' => 'required',
                'current_page' => 'required',
            ]);
        if ($validator->fails())
            return response()->json([
                    'status' =>'error',
                    'message' => $validator->errors()
                ]);
        $users = User::query();
        $count = $users->count();
        $item_per_page = $request->item_per_page;
        $current_page = $request->current_page;
        if($item_per_page > 0)
            $users = $users->skip($item_per_page * ($current_page - 1))
                            ->take($item_per_page);
        return response()->json([
                'status' => 'ok',
                'total_items' => $count,
                'data' => $users->get()->toArray(),
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $user = User::findOrFail($user_id);
        return response()->json([
                'status' => 'ok',
                'data' => $user->toArray(),
        ]);
    }

}
