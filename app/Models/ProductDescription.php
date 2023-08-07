<?php
namespace App\Models;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;


class ProductDescription extends Model
{
    protected $connection= 'mysql2';
    protected $table = 'sd_product_description';
    protected $primaryKey = 'product_id';
    public $timestamps = false;
    protected $guarded = [];
    use Sluggable;

    public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

}
