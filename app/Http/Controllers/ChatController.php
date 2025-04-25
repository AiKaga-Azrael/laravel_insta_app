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
                'chat_message' => 'nullable|max:150',
                'chat_image' => 'nullable|image|max:2048',
            ],
            [
                'chat_message.required' => 'You cannot submit an empty message.',
                'chat_message.max' => 'The message must not have more than 150 characters.',
                'chat_image.image' => 'Only image files are allowed.',
                'chat_image.max' => 'The image must be less than 2MB.',
            ]
        );

        // If both message and image are empty, reject it
        if (!$request->filled('chat_message') && !$request->hasFile('chat_image')) {
            return back()->withErrors(['chat_message' => 'Please enter a message or select an image.']);
        }

        $chat = new Chat();
        $chat->sender_id = Auth::id();
        $chat->receiver_id = $id;
        $chat->message = $request->input('chat_message');

        if ($request->hasFile('chat_image')) {
            $chat->image = $request->file('chat_image')->store('chat_images', 'public');
        }

        $chat->save();

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
            // ->where('status', 1)
            ->whereNull('read_at')
            ->update([
                'status' => 0,
                'read_at' => now()]
            );
    
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