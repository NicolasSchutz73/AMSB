<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageEvent;
use App\Events\GroupChatMessageEvent;
use App\Models\File;
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
            'files' => 'nullable|array',
            'files.*' => 'file|max:1024000', // Validation pour chaque fichier
        ]);

        Log::info($request);


        $message = new Message();
        $message->group_id = $request->input('groupId');
        $message->user_id = auth()->id();
        $message->content = $request->input('message');
        $message->save();

        $filesData = []; // Initialisez la variable avant de l'utiliser

        if ($request->hasFile('files')) {
            Log::info('Nombre de fichiers reçus : ' . count($request->file('files')));

            foreach ($request->file('files') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('data_message', $filename, 'public');

                // Création d'un enregistrement dans la table des fichiers pour chaque fichier
                $fileModel = new File();
                $fileModel->message_id = $message->id; // Assurez-vous que votre modèle File a un champ message_id
                $fileModel->file_path = $filePath;
                $fileModel->file_type = $file->getClientMimeType();
                $fileModel->file_size = $file->getSize();
                $fileModel->save();

                // Ajoutez les informations sur le fichier à $filesData
                $filesData[] = [
                    'filePath' => $filePath,
                    'fileType' => $file->getClientMimeType(),
                    'fileSize' => $file->getSize(),
                ];
            }
        }

        $firstname = auth()->user()->firstname;
        $lastname = auth()->user()->lastname;

        // Passez $filesData à l'événement
        event(new GroupChatMessageEvent(
            $request->input('groupId'),
            $message,
            $firstname,
            $lastname,
            $filesData
        ));

        return response()->json([
            'success' => true,
            'messageId' => $message->id,
            'messageContent' => $message->content,
            'files' => $filesData, // Inclure les données des fichiers dans la réponse
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
