<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageEvent;
use App\Events\GroupChatMessageEvent;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('chat.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $groupId = $request->input('groupId');
        $messageText = $request->input('message');

        // Créer et enregistrer le message
        $message = new Message();
        $message->group_id = $groupId; // Assurez-vous que cette valeur n'est pas nulle
        $message->user_id = auth()->id();
        $message->content = $messageText;
        $message->save();

        // Diffuse le message sur le canal du groupe
        $authorName = auth()->user()->firstname . ' ' . auth()->user()->lastname;

        event(new GroupChatMessageEvent($groupId, $message, $authorName));
        return response()->json(['success' => 'Message envoyé']);
    }





    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
