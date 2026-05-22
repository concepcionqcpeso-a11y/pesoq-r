<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller {

    public function stats(): JsonResponse {
        $roleCounts = ActivityLog::where('action', 'practiced')
            ->selectRaw('description as role, count(*) as count')
            ->groupBy('description')
            ->orderByDesc('count')
            ->get();

        return response()->json([
            'total_users'     => User::where('is_admin', false)->count(),
            'active_users'    => User::where('is_admin', false)->where('is_active', true)->count(),
            'inactive_users'  => User::where('is_admin', false)->where('is_active', false)->count(),
            'new_today'       => User::where('is_admin', false)->whereDate('created_at', today())->count(),
            'total_questions' => Question::count(),
            'total_logins'    => ActivityLog::where('action', 'login')->count(),
            'popular_roles'   => $roleCounts,
        ]);
    }

    public function users(): JsonResponse {
        $users = User::where('is_admin', false)
            ->orderByDesc('created_at')
            ->get()
            ->map(function($user) {
                return [
                    'id'         => $user->id,
                    'full_name'  => $user->full_name,
                    'email'      => $user->email,
                    'phone'      => $user->phone,
                    'is_active'  => $user->is_active,
                    'created_at' => $user->created_at->format('M d, Y h:i A'),
                ];
            });
        return response()->json($users);
    }

    public function toggleUser(int $id): JsonResponse {
        $user = User::where('is_admin', false)->findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        return response()->json(['message' => 'User status updated.', 'user' => $user]);
    }

    public function deleteUser(int $id): JsonResponse {
        $user = User::where('is_admin', false)->findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted.']);
    }

    public function questions(): JsonResponse {
        return response()->json(Question::orderBy('role_key')->get());
    }

    public function storeQuestion(Request $request): JsonResponse {
        $request->validate([
            'role_key'    => 'required|string',
            'role_label'  => 'required|string',
            'question_en' => 'required|string',
            'question_tl' => 'required|string',
            'tip_en'      => 'required|string',
            'tip_tl'      => 'required|string',
        ]);
        $question = Question::create($request->all());
        return response()->json($question, 201);
    }

    public function updateQuestion(Request $request, int $id): JsonResponse {
        $question = Question::findOrFail($id);
        $question->update($request->all());
        return response()->json($question);
    }

    public function deleteQuestion(int $id): JsonResponse {
        Question::findOrFail($id)->delete();
        return response()->json(['message' => 'Question deleted.']);
    }

    public function logs(): JsonResponse {
        $logs = ActivityLog::with('user:id,full_name,email')
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();
        return response()->json($logs);
    }
}
