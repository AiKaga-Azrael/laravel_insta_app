<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    protected $table = 'category_post'; // this model should be connected to this table name 'category_post'
    public $timestamps = false; // make the model aware that we do not need/want to use the timestamps
    protected $fillable = ['category_id', 'post_id'];

    # to get the name of the category_id
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
