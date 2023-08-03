<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class ProductDescription extends Model
{
    protected $connection= 'mysql2';
    protected $table = 'sd_product_description';
    protected $primaryKey = 'product_id';

    use SearchableTrait;

    protected $searchable = [
        'columns' => [
            'sd_product_description.name' => 10,
            'sd_product_description.description' => 5,
            'sd_product_description.tag' => 3
        ]
    ];

}
