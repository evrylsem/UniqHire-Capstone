<?php
// NotificationController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\TrainingProgram;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->unreadNotifications;
        return response()->json($notifications);
    }

    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        $notificationId = $request->input('notification_id');
        $notification = $user->notifications->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 404);
    }


    public function getNotifications()
    {
        $user = Auth::user();
        $notifications = $user->unreadNotifications->filter(function ($notification) {
            $programId = $notification->data['training_program_id'];
            return TrainingProgram::find($programId) !== null;
        });

        return response()->json($notifications);
    }
}
