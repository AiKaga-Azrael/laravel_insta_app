<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    # To get all the posts under a category
    public function categoryPost()
    {
        return $this->hasMany(CategoryPost::class);
    }

}
