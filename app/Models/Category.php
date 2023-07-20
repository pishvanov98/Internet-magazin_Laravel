<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $connection='mysql2';
    protected $table='sd_category';

    use HasFactory;


    public function description()
    {
        return $this->hasMany(Category_description::class, 'category_id','category_id');
    }

}
