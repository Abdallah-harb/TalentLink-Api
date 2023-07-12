<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function majors(){
        return $this->belongsToMany(Major::class,"course_majors","course_id","major_id");
    }

    // institus that publush the course
    public function user(){
      return  $this->belongsTo(User::class);
    }


}
