<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation;



class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    // public function share(Request $request): array
    // {
    //     return [
    //         ...parent::share($request),
    //         'auth' => [
    //             'user' => $request->user(),
    //         ],
    //         'conversations'=>Auth::id() ? Conversation::getConversationsForSiderbar (Auth::user()) : [] ,
    //     ];
    // }
    public function share(Request $request): array
    {
        // Fetch all conversations for the sidebar
        $conversations = Auth::check() ? Conversation::getConversationsForSiderbar(Auth::user()) : [];

        // Determine the selected conversation, e.g., by a request parameter
        $selectedConversation = null;
        if ($request->has('conversation_id')) {
            $selectedConversation = Conversation::find($request->input('conversation_id'));
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'conversations' => $conversations,
            'selectedConversation' => $selectedConversation,
        ];
    }
}
