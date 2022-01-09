<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events\Cheat\SendMessage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class MessageController extends Controller
{
    public function listMessages($id, User $user)
    {
        $userFrom = Auth::user()->id;
        $userTo = $user->findOrFail($id)->id;

        $messages = Message::where(
            function ($query) use ($userFrom, $userTo) {
                $query->where([
                    'from' => $userFrom,
                    'to' => $userTo,
                ]);
            }
        )->orWhere(
            function ($query) use ($userFrom, $userTo) {
                $query->where([
                    'from' => $userTo,
                    'to' => $userFrom,
                ]);
            }
        )->orderBy('created_at', 'asc')->get();

        return response()->json([
            'messages' => $messages,
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $message = Message::create([
            'from' => Auth::user()->id,
            'to' => $request->get('to'),
            'content' => $request->get('content'),
        ]);

        Event::dispatch(new SendMessage($message, $request->get('to')));

        return response()->json([]);
    }
}