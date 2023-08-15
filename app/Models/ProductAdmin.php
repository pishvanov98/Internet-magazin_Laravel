<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class ProductAdmin extends Model
{
    protected $connection= 'mysql2';
    protected $table = 'sd_product';
    protected $primaryKey = 'product_id';
    public $timestamps = false;
    protected $guarded = [];

}
