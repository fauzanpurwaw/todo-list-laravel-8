<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\ItemList;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ItemListController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'checklist_id' => 'required|integer',
                'content' => 'required|string',
                'is_done' => 'required|integer',
            ]);

            $validator->validate();

            DB::beginTransaction();
            $itemList = ItemList::create([
                'checklist_id' => $request->checklist_id,
                'content' => $request->content,
                'is_done' => $request->is_done,
            ]);
            DB::commit();

            return response()->json($itemList, 200);
        } catch (ValidationException $e) {
            $response = [
                'errors' => [
                    'code'    => 422,
                    'message' => $e->getMessage()
                ]
            ];
            return response()->json($response, 422);
        }
    }

    public function show($id)
    {
        try {
            $itemList = ItemList::findOrFail($id)->get()->first();

            $response = [
                'itemList' => $itemList
            ];

            return response()->json($response, 200);
        } catch (ValidationException $e) {
            $response = [
                'errors' => [
                    'code'    => 422,
                    'message' => $e->getMessage()
                ]
            ];
            return response()->json($response, 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'content' => 'nullable|string',
                'is_done' => 'nullable|string',
            ]);

            $validator->validate();

            DB::beginTransaction();
            $itemList = ItemList::findOrFail($id);
            $itemList->content = $request->content ?? $itemList->content;
            $itemList->is_done = $request->is_done ?? $itemList->is_done;
            $itemList->save();
            DB::commit();

            return response()->json($itemList, 200);
        } catch (ValidationException $e) {
            $response = [
                'errors' => [
                    'code'    => 422,
                    'message' => $e->getMessage()
                ]
            ];
            return response()->json($response, 422);
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $itemList = ItemList::findOrFail($id);
            $itemList->delete();
            DB::commit();

            $response = [
                'message' => 'Data Has Been Deleted',
                'itemList' => $itemList
            ];

            return response()->json($response, 200);
        } catch (ValidationException $e) {
            $response = [
                'errors' => [
                    'code'    => 422,
                    'message' => $e->getMessage()
                ]
            ];
            return response()->json($response, 422);
        }
    }
}
