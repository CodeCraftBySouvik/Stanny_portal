<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{

    public function todoList(Request $request){
        try {
            $todos = TodoList::with(['customer:id,name', 'staff:id,name'])
                ->when($request->start_date, fn($q) => $q->whereDate('todo_date', '>=', $request->start_date))
                ->when($request->end_date, fn($q) => $q->whereDate('todo_date', '<=', $request->end_date))
                ->when($request->staff_id, fn($q) => $q->where('user_id', $request->staff_id))
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Todo List fetched successfully',
                'todos' => $todos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching todos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function noteStore(Request $request){
        try {
        $validator = Validator::make($request->all(),[
            'remarks' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Get logged-in user
        $user = Auth::guard('api')->user();
         if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized access.',
            ], 401);
        }

        $note = new Note();
        $note->remarks = $request->remarks;
        $note->created_by = $user->id;
        $note->save();

        return response()->json([
            'status'  => true,
            'message' => 'Note created successfully.',
            'data'    => $note
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
                'status'  => false,
                'message' => 'An error occurred while creating the note.',
                'error'   => $e->getMessage(),
            ], 500);
        }

    }
}
