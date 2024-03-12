<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupNotificationSetting; // Assurez-vous que le modèle existe et est correctement nommé
class GroupNotificationSettingController extends Controller
{
    public function toggleNotificationGroup(Request $request)
    {
        $request->validate([
            'group_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $setting = GroupNotificationSetting::updateOrCreate(
            ['group_id' => $request->group_id, 'user_id' => $request->user_id],
            ['notifications_enabled' => $request->boolean('enable')]
        );

        return response()->json([
            'message' => 'Notification setting updated successfully',
            'setting' => $setting,
        ]);
    }

    public function getNotificationStatus($groupId, $userId)
    {
        $setting = GroupNotificationSetting::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->first();

        if (!$setting) {
            return response()->json(['error' => 'Settings not found'], 404);
        }

        return response()->json(['notifications_enabled' => $setting->notifications_enabled]);
    }

}
