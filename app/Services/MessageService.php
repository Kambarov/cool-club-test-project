<?php


namespace App\Services;


use App\Models\Message;
use App\Notifications\SendMessageToEmail;
use Illuminate\Support\Facades\Notification;

class MessageService
{
    public function store(array $attributes)
    {
        return Message::create($attributes);
    }
}
