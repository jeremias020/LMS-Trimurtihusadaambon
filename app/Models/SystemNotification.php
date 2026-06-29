<?php

namespace App\Models;

/**
 * SystemNotification adalah alias dari Notification.
 * Dipertahankan untuk backward-compat dengan NotificationController.
 */
class SystemNotification extends Notification
{
    // Tidak ada override — semua behaviour dari Notification
}
