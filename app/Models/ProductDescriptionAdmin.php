<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;


class ProductDescriptionAdmin extends Model
{
    protected $connection= 'mysql2';
    protected $table = 'sd_product_description';
    protected $primaryKey = 'product_id';
    public $timestamps = false;
    protected $guarded = [];

    use SearchableTrait;


    protected $searchable = [
        'columns' => [
            'sd_product_description.name' => 10
        ]
    ];

}
