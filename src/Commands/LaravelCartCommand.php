<?php

namespace OguzcanDemircan\LaravelCart\Commands;

use Illuminate\Console\Command;

class LaravelCartCommand extends Command
{
    public $signature = 'laravel-cart';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
