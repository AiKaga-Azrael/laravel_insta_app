<?php

namespace App\Http\Controllers;

use id;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Broadcasting\PrivateChannel;

class ChatController extends Controller
{
    private $chat;
    Private $user;

    public function __construct(Chat $chat, User $user)
    {
        $this->chat = $chat;
        $this->user = $user;
     }

    // store() - save the chat
    public function store(Request $request, $id)
    {
        $request->validate(
            [
                'chat_message' => 'required|max:150'
            ],
            [
                'chat_message' . '.required' => 'You cannot submit an empty message.',
                'chat_message' . '.max' => 'The message must not have more than 150 characters.'
            ]
        );
    
        $this->chat->sender_id = Auth::user()->id;
        $this->chat->receiver_id = $id;
        $this->chat->message    = $request->input('chat_message');
        $this->chat->save();
    
        return redirect()->back();
    }

   //show
    public function show($id)
    {
        $user = $this->user->findOrFail($id);
    
        // 🔥 Mark unread messages from $id as read
        $this->chat
            ->where('sender_id', $id)
            ->where('receiver_id', Auth::id())
            ->where('status', 1)
            ->update(['status' => 0]);
    
        // 📥 Load chat between authenticated user and the other user
        $chats = $this->chat
            ->where(function ($query) use ($id) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $id);
            })
            ->orWhere(function ($query) use ($id) {
                $query->where('sender_id', $id)
                      ->where('receiver_id', Auth::id());
            })
            ->orderBy('id', 'asc')
            ->get();
    
        return view('users.profile.chat')
            ->with('chats', $chats)
            ->with('user', $user);
    }

    public function index($id)
    {
            $unreadMessages = Chat::selectRaw('sender_id, COUNT(*) as message_count')
            ->where('receiver_id', Auth::id())
            ->where('status', 1) // 1 = unread
            // ->update(['status' => 0])
            ->groupBy('sender_id')
            ->with('sender')
            ->get();

            $user = User::find($id);
            $chat = Chat::where('sender_id', Auth::id())->get();

            return view('users.profile.unreadmessages', ['user' => $user, 'chat' => $chat], compact('unreadMessages'));
    }
  
}