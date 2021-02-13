<?php

namespace App\Mail;

use App\Models\Test;
use App\Models\TestLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestFailed extends Mailable
{
    use Queueable, SerializesModels;

    public $test;
    public $log;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Test $test, TestLog $logData)
    {
        $this->test = $test;
        $this->log = $logData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.failedTest', [
            'test'=>"some message"
        ]);
    }
}
