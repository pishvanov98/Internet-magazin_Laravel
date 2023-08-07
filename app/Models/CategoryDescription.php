<?php
namespace App\Models;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;


class CategoryDescription extends Model
{
    protected $connection= 'mysql2';
    protected $table = 'sd_category_description';
    protected $primaryKey = 'category_id';
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
