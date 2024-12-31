<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $name = $request->query('name');
        $email = $request->query('email');
        $pageSize = $request->query('per_page', 10);

        $query = User::query();

        $query->when($name, function ($q) use ($name) {
            $q->orWhere('name', 'like', "%{$name}%");
        });

        $query->when($email, function ($q) use ($email) {
            $q->orWhere('email', 'like', "%{$email}%");
        });

        $users = $query->paginate($pageSize);

        return response()->json([
            'message' => 'Users retrieved successfully',
            'data' => UserResource::collection($users),
            'total_results' => $users->total(),
            'page' => $users->currentPage(),
            'pageSize' => $users->perPage(),
        ], 200);
    }

    public function show($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json([
                'message' => 'User retrieved successfully',
                'data' => new UserResource($user),
            ], 200);
        }

        return response()->json(['message' => 'User not found!'], 404);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'All fields required',
                'error' => $validator->messages(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'data' => new UserResource($user)
        ], 201);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully!',
        ], 200);
    }
}

