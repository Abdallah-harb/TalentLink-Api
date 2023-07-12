<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //job has many majors
    public function majors(){
        return $this->belongsToMany(Major::class,'job_majors','job_id','major_id');
    }
    //city
    public function city(){
        return $this->belongsTo(City::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function userjob(){
        return $this->hasMany(UserJob::class);
    }

}
