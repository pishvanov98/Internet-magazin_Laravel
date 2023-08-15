<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class AttributeDescriptionAdmin extends Model
{
    protected $connection= 'mysql2';
    protected $table = 'sd_attribute_description';
    protected $primaryKey = 'attribute_id';
    public $timestamps = false;
    protected $guarded = [];







}
