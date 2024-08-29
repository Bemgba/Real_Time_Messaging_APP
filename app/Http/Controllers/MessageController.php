<?php

namespace App\Http\Controllers;

use App\Events\SocketMessage;
use app\Models\User;
use app\Models\Group;
use app\Models\Message;
use app\Models\Conversation;
use App\Http\Requests\StoreMessageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // This is for auth() helper
use Inertia\Inertia; // This is for InertiaJS
use App\Http\Resources\MessageResource;
use App\Models\MessageAttachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    //Load message by User
    public function byUser(User $user)
    {
        $messages = Message::where('sender_id', auth()->id())
            ->where('receiver_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->latest()
            ->paginate(10);

        return inertia('Home', [
            'selectedConversation' => $user->toConversationArray(),
            'messages' => MessageResource::collection($messages),
        ]);
    }
    ////Load message by Group
    public function byGroup(Group $group)
    {
        $messages = Message::where('group_id', $group->id)
            ->latest()
            ->paginate(50);
        return inertia([
            'selectedConversation' => $group->toConversationArray(),
            'messages' => MessageResource::collection($messages),


        ]);
    }
    //Load all message
    public function loadOlder(Message $message)
    {
        //Load older messages that are older than the given message, sort by lates
        if ($message->group_id) {
            $messages = Message::where('created_at', '<', $message->created_at)
                ->where('group_id', $message->group_id)
                ->latest()
                ->paginate(50);
        } else {
            $messages = Message::where('created_at', '<', $message->created_at)
                ->where(function ($query) use ($message) {
                    $query->where('sender_id', $message->sender_id)
                        ->where('receiver_id', $message->receiver_id)
                        ->orWhere('sender_id', $message->receiver_id)
                        ->where('receiver_id', $message->sender_id);
                })
                ->latest()
                ->paginate(10);
        }
        //Return messages as a resource
        return MessageResource::collection($messages);
    }
    //save message
    public function store(StoreMessageRequest $request)
    {

        $data = $request->validate();
        $data['sender_id'] = auth()->id();
        $receiverId = $data['receiver_id'] ?? null;
        $groupId = $data['group_id'] ?? null;
        $files = $data['attachments'] ?? null;
        $message = Message::create($data);
        $attachments = [];
        if ($files) {
            foreach ($files as $file) {
                $directory = 'attachments/' . Str::random(32);
                Storage::makeDirectory($directory);
                $model = [
                    'message_id' => $message->id,
                    'name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'path' => $file->store($directory, 'public'),
                ];
                $attachment = MessageAttachment::create($model);
                $attachments[] = $attachment;
            }
            $message->attachments = $attachments;
        }
        //update the messages, whether in personal chats or group
        if ($receiverId) {
            Conversation::updateConversationWithMessage($receiverId, auth()->id(), $message);
        }
        if ($groupId) {
            Group::updateGroupWithMessage($groupId, $message);
        }
        SocketMessage::dispatch($message);
        return new MessageResource($message);
    }

    //delete message
    public function destroy(Message $message) {

        //check if user is the owner of the message
        if($message->sender_id !== auth()->id()){
            return \response()->json(['message'=>'Forbiden'],403);
        }
        $message->delete();
        return response('',204);
    }
}
