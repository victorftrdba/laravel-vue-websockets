<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        $userLogged = Auth::user();

        $users = User::where('id', '!=', $userLogged->id)->get();

        return response()->json([
            'users' => $users,
        ], Response::HTTP_OK);
    }

    public function show($id, User $user)
    {
        return response()->json(['user' => $user->findOrFail($id)], Response::HTTP_OK);
    }
}