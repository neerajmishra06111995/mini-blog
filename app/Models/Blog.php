<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    public function getImage($imageName)
    {
        if (env('APP_ENV') == 'local')
        {
            return $blogImage = !empty($imageName) ? ("images/" . $imageName) : "images/default_image.png";
        }
        else
        {
            return $blogImage = !empty($imageName) ? ("storage/blog-image/" . $imageName) : "images/default_image.png";
        }
    }
}
