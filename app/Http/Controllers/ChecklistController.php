<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ChecklistController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
            ]);

            $validator->validate();

            DB::beginTransaction();
            $checklist = Checklist::create([
                'title' => $request->title,
            ]);
            DB::commit();

            return response()->json($checklist, 200);
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

    public function list()
    {
        try {
            $checklist = Checklist::select('id', 'title')->with('items')->get();

            $response = [
                'code' => 200,
                'data' => $checklist
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

    public function show($id)
    {
        try {
            $checklist = Checklist::findOrFail($id)->select('id', 'title')->with('items')->get()->first();

            $response = [
                'code' => 200,
                'data' => $checklist
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
                'title' => 'required|string',
            ]);

            $validator->validate();

            DB::beginTransaction();
            $checklist = Checklist::findOrFail($id);
            $checklist->title = $request->title;
            $checklist->save();
            DB::commit();

            $response = [
                'code' => 200,
                'data' => $checklist
            ];

            return response()->json($checklist, 200);
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
            $checklist = Checklist::findOrFail($id);
            $checklist->delete();
            DB::commit();

            $response = [
                'code' => 200,
                'message' => 'Data Has Been Deleted'
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
