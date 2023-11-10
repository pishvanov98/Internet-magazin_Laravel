<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class GenerateTokenApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $path = base_path('.env');
        if (file_exists($path)) {
            $random=Crypt::encrypt(Str::random(13));
            file_put_contents($path, str_replace(
                'TokenApi='.env('TokenApi'), 'TokenApi='.$random, file_get_contents($path)
            ));
            $this->info($random);
            Artisan::call('config:clear');

        }

    }
}
