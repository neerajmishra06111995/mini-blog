<?php

namespace App\Jobs;

use App\Mail\DailyUpdateMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class SendDailyUpdateEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $data )
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->data['email'])->queue( new DailyUpdateMail($this->data) );
        if (!Mail::failures()) {
            $outputMsg['type']      = 'danger';
            $outputMsg['message']   = 'Something went wrong.';
            return view('daily_update.index', compact('outputMsg'));
        }
    }
}
