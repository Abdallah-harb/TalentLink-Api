<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

        ###############################################################
        ###################### Start Relation #########################
    public function personaldata()
    {
        return $this->hasOne(PersonalDataOfJobSeeker::class);
    }

    public function prev_job()
    {
        return $this->hasOne(PreviousJobsForPreviousExperiences::class);
    }

    public function prev_experes()
    {
        return $this->hasMany(PreviousExperiencesOfJobSeeker::class);
    }

    public function education()
    {
        return $this->hasOne(BasicEducationData::class);
    }
    public function aquireLearning()
    {
        return $this->hasMany(AcquiredLearningData::class);
    }

    //favourite jobs
    public function favJobs(){
        return $this->belongsToMany(Job::class,'fav_job');
    }
    //return all favJobs of user and return favJobs that equal the job_id that i click of it
    public function favJobsHas($job_id){

        return self::favJobs()->where('job_id',$job_id)->exists();
    }

    //favCourses
    public function favCourses(){
        return $this->belongsToMany(Courses::class,'fav_courses','user_id','course_id');
    }
    //return all favJobs of user and return favJobs that equal the course_id that i click of it
    public function favCoursesHas($course_id){
        return self::favCourses()->where('course_id',$course_id)->exists();
    }

}
