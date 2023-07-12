<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompaniesAndInstitutesData extends Model
{
    use HasFactory;
    protected $guarded=['id'];
    //get path of photo
   /* public function getlogoAttribute($value){
        $actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return ($value == null ? '' : $actual_link . 'company/' . $value);
    }*/
}
