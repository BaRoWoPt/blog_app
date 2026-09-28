<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:test_bai')]
#[Description('Command description')]
class Test_bai extends Command
{
    /**
     * Execute the console command.
     */
    public function handle() {}
}
