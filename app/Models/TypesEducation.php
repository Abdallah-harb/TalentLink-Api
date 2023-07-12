<?php

namespace App\Models;

use App\Traits\ModelTranslateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypesEducation extends Model
{
    use HasFactory ;
    protected $guarded=['id'];


    protected $appends=['name'];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }


    public function all_translate()
    {


        return $this->hasMany(TypesEducationTranslate::class, 'types_education_id' , 'id');
    }


    public function locale_translate()
    {

        return $this->hasOne(TypesEducationTranslate::class)
            ->whereLang(config('app.locale'))
            ->select('types_education_id', 'name');
    }

    public function first_translate()
    {


        return $this->hasOne(TypesEducationTranslate::class)->select('types_education_id', 'name');
    }



    public function getNameAttribute()
    {
        if ($this->locale_translate) {
            return $this->locale_translate->name;
        } else {
            return $this->first_translate->name;
        }
    }
}
