<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $groups = auth()->user()->groups;

        $users = User::where('id', '<>', auth()->user()->id)->get();
        $user = auth()->user();

        return view('home', ['groups' => $groups, 'users' => $users, 'user' => $user]);
    }

    public function saveToken(Request $request)
    {
        Log::debug('Save Token Request', $request->all());

        auth()->user()->update(['device_token' => $request->token]);

        return response()->json(['message' => 'Token saved successfully.']);
    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        $SERVER_API_KEY = 'AAAAoXM2Djo:APA91bG4FIc9ClN16o2rpZ6jByJPYXqXwlpBW_GeRcgiomMslDKkgG8MszzC8eI36-KKb9iQFzL567Bk29xA2ZfadgNTg5c8EPeKxF4E8dcOyLyYtND_3Hvl4Y8seQDrcocnmpzBZxml';
        $notificationBody = $request->body ?: 'Image';

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $notificationBody,
                "content_available" => true,
                "priority" => "high",
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        if ($response === false) {
            // Gérer l'erreur cURL
            $error = curl_error($ch);
            dd("Erreur cURL : " . $error);
        }


        $responseData = json_decode($response, true);

        dd("Réponse FCM : " . $response);

        if (isset($responseData['success']) && $responseData['success'] > 0) {
            // La notification a été envoyée avec succès
            dd("Notification envoyée avec succès");
        } else {
            // Gérer l'échec de l'envoi de la notification
            dd("Échec de l'envoi de la notification");
        }

        // Fermeture de la ressource cURL
        curl_close($ch);


    }

}
