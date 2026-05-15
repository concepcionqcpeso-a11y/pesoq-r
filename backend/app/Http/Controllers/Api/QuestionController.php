<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\JsonResponse;

class QuestionController extends Controller {

    // GET /api/roles — return unique roles
    public function roles(): JsonResponse {
        $roles = Question::select('role_key', 'role_label')
            ->distinct()
            ->get();

        return response()->json($roles);
    }

    // GET /api/questions/{role_key} — return 5 questions for a role
    public function byRole(string $roleKey): JsonResponse {
        $questions = Question::where('role_key', $roleKey)
            ->limit(5)
            ->get(['id', 'question_en', 'question_tl', 'tip_en', 'tip_tl']);

        if ($questions->isEmpty()) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        return response()->json($questions);
    }
}