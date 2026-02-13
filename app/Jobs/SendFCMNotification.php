<?php
namespace App\Jobs;

use App\Http\Controllers\Admin\FCMController as AdminFCMController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\FCMController;

class SendFCMNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;

    public function __construct(
        public string $title,
        public string $body,
        public string $fcmToken,
        public int $recipientId,
        public string $modelType // 'user' or 'driver'
    ) {}

    public function handle(): void
    {
        AdminFCMController::sendMessage(
            $this->title,
            $this->body,
            $this->fcmToken,
            $this->recipientId,
            'order',
            $this->modelType
        );
    }
}