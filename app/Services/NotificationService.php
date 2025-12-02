<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    /**
     * Create a notification.
     *
     * @param int $senderId
     * @param int $receiverId
     * @param string $title
     * @param string $content
     * @param string $slug
     * @param string $type
     * @param string $notificationFor
     * @return Notification
     */
    public function createNotification($senderId,$receiverId, $title, $content, $slug, $type, $notificationFor)
    {
        return Notification::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'title' => $title,
            'content' => $content,
            'slug' => $slug,
            'type' => $type,
            'status' => 'UnRead', // Default status is unread
            'notification_for' => $notificationFor,
            'read_at' => null, // Initially set to null
        ]);
    }

    /**
     * Mark a notification as read.
     *
     * @param Notification $notification
     * @return bool
     */
    public function markAsRead(Notification $notification)
    {
        $notification->status = 'Read';
        $notification->read_at = now();
        return $notification->save();
    }

    /**
     * Get unread notifications for a user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUnreadNotificationsForUser($userId)
    {
        return Notification::where('receiver_id', $userId)
            ->where('status', 'UnRead')
            ->get();
    }

    /**
     * Get all notifications for a user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllNotificationsForUser($userId)
    {
        return Notification::where('receiver_id', $userId)->get();
    }
}
