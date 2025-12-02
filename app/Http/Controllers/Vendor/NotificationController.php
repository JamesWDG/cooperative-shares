<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Exception;

class NotificationController extends Controller
{
    public $viewFolderPath = 'screens.vendor.notification.';

    /**
     * List all notifications for the logged-in vendor
     */
    public function list(Request $request)
    {
        $authUserId = auth()->id();
        //dd($authUserId);
        // Mark unread Vendor notifications as read for this vendor
        Notification::where([
                'notification_for' => 'Vendor',
                'status'           => 'UnRead',
                'receiver_id'      => $authUserId,
            ])
            ->update([
                'status'  => 'Read',
                'read_at' => now(),
            ]);

        // Fetch all vendor notifications for this vendor
        $vendornotificationslist = Notification::where([
                'notification_for' => 'Vendor',
                'receiver_id'      => $authUserId,
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view($this->viewFolderPath . 'list', compact('vendornotificationslist'));
    }

    /**
     * View single notification detail
     */
    public function view($slug)
    {
        $authUserId = auth()->id();

        $notification = Notification::where([
                'notification_for' => 'Vendor',
                'receiver_id'      => $authUserId,
                'slug'             => $slug,
            ])
            ->firstOrFail();

        if ($notification->status === 'UnRead') {
            $notification->update([
                'status'  => 'Read',
                'read_at' => now(),
            ]);
        }

        return view($this->viewFolderPath . 'view', compact('notification'));
    }

    /**
     * Delete multiple notifications (AJAX)
     */
    public function deleteMultiple(Request $request)
    {
        DB::beginTransaction();

        try {
            $ids = $request->input('ids');

            if (empty($ids) || !is_array($ids)) {
                return response()->json([
                    'status' => false,
                    'msg'    => 'No notifications selected.',
                ]);
            }

            // Restrict deletion to this vendor & Vendor notifications
            Notification::whereIn('id', $ids)
                ->where('notification_for', 'Vendor')
                ->where('receiver_id', auth()->id())
                ->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'msg'    => 'Selected notifications deleted successfully.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'msg'    => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
