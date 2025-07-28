<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Tags\Tag as TagsTag;
use Illuminate\Database\Eloquent\Model;

class Tag extends TagsTag
{
    public function products():MorphToMany  
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }
}
