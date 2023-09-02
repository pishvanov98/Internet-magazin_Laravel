<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessSendingEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $message;
    protected $email;
    /**
     * Create a new job instance.
     */
    public function __construct($email,$massage)
    {
        $this->message = $massage;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $content = $this->message;
        Mail::mailer('smtp')->send('defaultMail',['content'=>$content],function ($message){
            $message->to($this->email,'ShopDent.ru');//кому
            $message->from(env('MAIL_FROM_ADDRESS'),'ShopDent.ru');//от
            $message->subject("Оформление заказа");
        });
    }
}
