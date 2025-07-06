<?php

namespace App\Jobs;

use App\Services\MikroTikService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateMikroTikUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $username;
    protected $package;
    protected $validityDays;

    public function __construct($username, $package, $validityDays)
    {
        $this->username = $username;
        $this->package = $package;
        $this->validityDays = $validityDays;
    }

    public function handle()
    {
        $service = new MikroTikService();
        $service->createUser($this->username, $this->package, $this->validityDays);
    }
}
