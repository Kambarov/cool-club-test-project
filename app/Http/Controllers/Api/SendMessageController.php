<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Notifications\SendMessageToEmail;
use App\Services\MessageService;
use Illuminate\Http\Request;
use App\Http\Requests\Api\SendMessageRequest;
use Illuminate\Support\Facades\Notification;

class SendMessageController extends Controller
{
    /**
     * @var MessageService
     */
    private $service;

    public function __construct(MessageService $service)
    {
        $this->service = $service;
    }

    public function send(SendMessageRequest $request)
    {
        $message = $this->service->store($request->validated());

        Notification::route('mail', 'Kambarov0209@gmail.com')
            ->notify(new SendMessageToEmail($message->content));

        return (new MessageResource($message))
            ->additional([
                'message' => 'Your mail was successfully sent',
            ]);
    }
}
