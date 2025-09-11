<?php

namespace App\Http\Controllers;

use App\Models\AnnouncementFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AnnouncementFeedbackController extends Controller
{
    public function store(Request $request)
    {
        $this->middleware('auth');

        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'feature_id' => ['nullable','integer'],
            'reaction' => ['required','in:like,dislike'],
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        $fb = AnnouncementFeedback::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'feature_id' => $data['feature_id'] ?? null,
                'reaction' => $data['reaction'],
            ],
            []
        );

        return response()->json(['message' => 'Feedback saved', 'id' => $fb->id]);
    }
}
