<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class AttributeAdmin extends Model
{
    protected $connection= 'mysql2';
    protected $table = 'sd_attribute';
    protected $primaryKey = 'attribute_id';
    public $timestamps = false;
    protected $guarded = [];







}
