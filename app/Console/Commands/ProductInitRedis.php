<?php

namespace App\Console\Commands;

use App\Components\ImageComponent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProductInitRedis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:product-init-redis';

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
        ini_set('memory_limit', '2048M');
        //$mass_prod_id=[];
        $products_id=DB::connection('mysql2')->table('sd_product')->where('status','=',1)->where('price','>',0)->latest('product_id')->pluck('product_id');
        $ProgressBar = $this->output->createProgressBar(count($products_id));
        $ProgressBar->start();
        $products_id->each(function ($item) use(&$ProgressBar){
            //$mass_prod_id[]=$item->product_id;
            app('Product')->ProductInit($item);
            $ProgressBar->advance();

        });
        $ProgressBar->finish();
      //  app('Product')->ProductInit($mass_prod_id);
    }
}
