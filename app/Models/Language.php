<?php

namespace App\Models;

use App\Traits\ModelTranslateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory , ModelTranslateTrait;
    protected $guarded=['id'];


    protected $appends=['name'];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
