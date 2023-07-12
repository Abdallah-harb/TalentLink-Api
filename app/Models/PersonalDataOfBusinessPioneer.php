<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalDataOfBusinessPioneer extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function children(){
        return $this->hasMany(PersonalDataOfBusinessPioneer::class,'parent_id');
    }
}
