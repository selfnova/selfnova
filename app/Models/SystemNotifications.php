<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification;

class SystemNotifications extends DatabaseNotification
{
    use HasFactory;

	protected $table = 'system_notifications';
}
