<?php

namespace App\Models;

use App\Traits\ModelTranslateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory ,  ModelTranslateTrait;

    protected $guarded = ['id'];
    public $timestamps = true;
    protected $appends=['name'];

    public function scopeActive($query)
    {

        return $query->where('is_active', 1);
    }




}
