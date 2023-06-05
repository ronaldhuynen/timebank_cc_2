<?php

namespace App\Models;

use App\Models\Category;
use App\Models\PostTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['postable_id', 'postable_type'];


    /**
     * Get the creator of the post (i.e. user or organisation).
     *
     * @return void
     */
    public function postable()
    {
        return $this->morphTo();
    }


    /**
     * Get the related translations of the post
     *
     * @return void
     */
    public function translations()
    {
        return $this->hasMany(PostTranslation::class);
    }


    /**
     * Get the related catogory of the post
     *
     * @return void
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    /**
     * Get the related images of the posts
     *
     * @return void
     */
    public function images()
    {
    return $this->morphToMany(Image::class, 'categorizable');
    }
}