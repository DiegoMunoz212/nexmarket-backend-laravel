<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request, $id)
    {
        $conversation = Conversation::where(function($q) use ($request) {
                $q->where('buyer_id', $request->user()->id)
                  ->orWhere('seller_id', $request->user()->id);
            })
            ->findOrFail($id);

        $messages = Message::with('sender')
            ->where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Marcar como leídos
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json($messages);
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $conversation = Conversation::where(function($q) use ($request) {
                $q->where('buyer_id', $request->user()->id)
                  ->orWhere('seller_id', $request->user()->id);
            })
            ->findOrFail($id);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $request->user()->id,
            'body'            => $request->body,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json($message->load('sender'), 201);
    }
}