<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;


class CategoryDescriptionAdmin extends Model
{
    protected $connection= 'mysql2';
    protected $table = 'sd_category_description';
    protected $primaryKey = 'category_id';
    public $timestamps = false;
    protected $guarded = [];

use SearchableTrait;


 protected $searchable = [
     'columns' => [
         'sd_category_description.name' => 10
     ]
 ];


}
