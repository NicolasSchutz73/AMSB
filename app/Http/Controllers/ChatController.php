<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageEvent;
use App\Events\GroupChatMessageEvent;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    // ChatController.php

    public function store(Request $request)
    {
        $request->validate([
            'groupId' => 'required|integer',
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:1024000', // Limite de 10 MB pour les fichiers
        ]);

        $message = new Message();
        $message->group_id = $request->input('groupId');
        $message->user_id = auth()->id();
        $message->content = $request->input('message'); // Enregistrez le message texte s'il est présent

        // Vérifiez si un fichier est attaché et traitez-le
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('data_message', $filename, 'public'); // Stockez le fichier

            // Enregistrez les détails du fichier dans la base de données
            $message->file_path = $filePath;
            $message->file_type = $file->getClientMimeType();
            $message->file_size = $file->getSize();
        }

        $message->save(); // Sauvegardez le message dans la base de données

        // Récupérez le nom complet de l'utilisateur pour l'afficher dans le chat
        $firstname = auth()->user()->firstname;
        $lastname = auth()->user()->lastname;

        // Déclenchez l'événement de diffusion du message
        event(new GroupChatMessageEvent(
            $request->input('groupId'),
            $message,
            $firstname,
            $lastname,
            $message->file_path ,
            $message->file_type
        ));
        Log::info($message);
        // Incluez les détails du fichier dans la réponse JSON
        return response()->json([
            'success' => true,
            'messageId' => $message->id,
            'messageContent' => $message->content,
            'filePath' => $message->file_path ? asset('storage/' . $message->file_path) : null,
            'fileType' => $message->file_type,
            'fileSize' => $message->file_size,
        ]);

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
