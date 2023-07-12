<?php

use App\Http\Controllers\Api\BusinessPioneer\BusinessPioneerController;
use App\Http\Controllers\Api\BusinessPioneer\ProjectController;
use App\Http\Controllers\Api\Companies\AdsController;
use App\Http\Controllers\Api\Companies\ApplyedJobController;
use App\Http\Controllers\Api\Companies\JobController;
use App\Http\Controllers\Api\Companies\MainDataController;
use App\Http\Controllers\Api\Institutes\CourseController;
use App\Http\Controllers\Api\Institutes\InstituteController;
use App\Http\Controllers\Api\Institutes\SubscriberController;
use App\Http\Controllers\Api\JobSeekers\CourseFavController;
use App\Http\Controllers\Api\JobSeekers\EducationDataController;
use App\Http\Controllers\Api\JobSeekers\ExperienceController;
use App\Http\Controllers\Api\JobSeekers\FavJobController;
use App\Http\Controllers\Api\JobSeekers\JobforJobSeekerController;
use App\Http\Controllers\Api\JobSeekers\Jobs\OrderJobController;
use App\Http\Controllers\Api\JobSeekers\PersonalDataController;
use App\Http\Controllers\Api\JobSeekers\ProgrammeController;
use App\Http\Controllers\Api\Public\Auth\AuthController;
use App\Http\Controllers\Api\Public\DataController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of th
em will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::group(["prefix"=>"job-seeker","middleware" => ['api_auth','Jop_Seeker']],function(){

    Route::group(['prefix' => 'personalData'],function(){
        Route::post('store',[PersonalDataController::class,'store']);
        Route::get('show',[PersonalDataController::class,'show']);
        Route::post('update',[PersonalDataController::class,'update']);
    });

    Route::group(['prefix' => 'previousExperience'],function (){
        Route::post('store',[ExperienceController::class,'store']);
        Route::get('show',[ExperienceController::class,'show']);
        Route::post('update',[ExperienceController::class,'update']);
        Route::post('delete/{id}',[ExperienceController::class,'destroy']);
    });
    Route::group(['prefix' => 'EducationData'],function(){
        Route::post('store',[EducationDataController::class,'store']);
        Route::get('show',[EducationDataController::class,'show']);
        Route::post('update',[EducationDataController::class,'update']);
        Route::post('delete/{id}',[EducationDataController::class,'destroy']);
    });

        //enroll in Qualifying programme
    Route::group(['prefix' => 'programme'],function(){
            Route::post('enroll',[ProgrammeController::class,'store']);
            Route::group(['middleware' => 'pass_program'],function(){
                Route::get('/',[ProgrammeController::class,'index']);
                Route::get('show/{id}',[ProgrammeController::class,'show']);
                Route::get('download-certificate',[ProgrammeController::class,'download']);
            });
    });

    Route::group(['prefix' => 'jobs-published' , 'middleware' => 'pass_program'],function (){
                //jobs and enroll on it
        Route::get('/',[JobforJobSeekerController::class,'index']);
        Route::get('best',[JobforJobSeekerController::class,'bestJobs']);
        Route::get('show/{id}',[JobforJobSeekerController::class,'show']);
        Route::post('enroll/{id}',[JobforJobSeekerController::class,'store']);

        Route::group(['prefix' => 'order-job'],function(){
            Route::get('/',[OrderJobController::class,'index']);
            Route::post('store',[OrderJobController::class,'store']);
            Route::get('show/{id}',[OrderJobController::class,'show']);
            Route::post('update/{id}',[OrderJobController::class,'update']);
            Route::delete('delete/{id}',[OrderJobController::class,'destroy']);
        });
                // favourite jobs
        Route::group(['prefix' => 'fav-jobs'],function(){
            Route::get('/',[FavJobController::class,'index']);
            Route::post('Add',[FavJobController::class,'store']);
            Route::delete('delete',[FavJobController::class,'destroy']);
        });
        // favourite courses
        Route::group(['prefix' => 'fav-courses'],function(){
            Route::get('/',[CourseFavController::class,'index']);
            Route::post('Add',[CourseFavController::class,'store']);
            Route::delete('delete',[CourseFavController::class,'destroy']);
        });

    });

});


Route::group(['prefix' => 'company','middleware' => ['api_auth','company']],function (){
    Route::group(['prefix' => 'main-data'],function(){
        Route::post('store',[MainDataController::class,'store']);
        Route::get('show',[MainDataController::class,'show']);
        Route::post('update',[MainDataController::class,'update']);
    });
            //publish jobs that company need
    Route::group(['prefix'=>'jobs'],function(){
        Route::get('/',[JobController::class,'index']);
        Route::post('store',[JobController::class,'store']);
        Route::get('show/{id}',[JobController::class,'show']);
        Route::post('update/{id}',[JobController::class,'update']);
        Route::post('delete/{id}',[JobController::class,'destroy']);
                //Recommendation for jobs
        Route::group(['prefix' => "recommendationJobs"],function(){
            Route::get('job-seeker-recommendation',[ApplyedJobController::class,'jobSeekerRecommend']);
        });
        Route::group(['prefix' => 'applauded-job'],function(){
            Route::get('/',[ApplyedJobController::class,'index']);
            Route::post('recommend',[ApplyedJobController::class,'store']);
        });
    });

    Route::group(['prefix' => 'search-employee'],function(){
            //Search for jobSeekers
        Route::get('/',[ApplyedJobController::class,'search']);
    });
        //Add Ads
    Route::group(['prefix' => 'ads'],function(){
        Route::controller(AdsController::class)->group(function (){
          Route::get('/','index');
          Route::post('store','store');
          Route::get('show/{id}','show');
          Route::post('update','update');
          Route::post('delete/{id}','destroy');
        });
    });
});


Route::group(["prefix"=>"institutes","middleware" => ["api_auth","institute"]],function(){
    Route::group(['prefix' => 'main-data'],function(){
        Route::post('store',[InstituteController::class,'store']);
        Route::get('show',[InstituteController::class,'show']);
        Route::post('update',[InstituteController::class,'update']);
    });
    Route::group(['prefix' => 'courses'],function(){
        Route::get('/',[CourseController::class,'index']);
        Route::post('store',[CourseController::class,'store']);
        Route::get('show/{id}',[CourseController::class,'show']);
        Route::post('update/{id}',[CourseController::class,'update']);
        Route::post('delete/{id}',[CourseController::class,'destroy']);
    });
    Route::group(['prefix'=> 'subscribers'],function(){
        Route::get('/',[SubscriberController::class,'index']);
        Route::get('info/{id}',[SubscriberController::class,'show']);
        Route::post('graduate/{id}',[SubscriberController::class,'update']);
    });
});

Route::group(["prefix"=>"business-pioneer",'middleware' => ['api_auth','business_pioneer']],function(){
    Route::group(['prefix' => 'main-data'],function(){
        Route::get('/',[BusinessPioneerController::class,'index']);
        Route::post('store',[BusinessPioneerController::class,'store']);
        Route::get('show/{id}',[BusinessPioneerController::class,'show']);
        Route::post('update/{id}',[BusinessPioneerController::class,'update']);
        Route::post('delete/{id}',[BusinessPioneerController::class,'destroy']);
    });
    Route::group(['prefix' => 'project'],function(){
        Route::get('/',[ProjectController::class,'index']);
        Route::post('store',[ProjectController::class,'store']);
        Route::get('show/{id}',[ProjectController::class,'show']);
        Route::post('update/{id}',[ProjectController::class,'update']);

    });

});




Route::group(["prefix"=>"public"],function(){
    Route::post("register",[AuthController::class, "register" ]);
    Route::post("login",[AuthController::class, "login" ]);
            //reset Password
    Route::post('reset',[AuthController::class, "resetPassword" ]);
    Route::post('updatepassword',[AuthController::class, "updatePassword" ]);

    #################### routes auth ####################

    Route::group(['middleware' =>'api_auth'],function(){
        Route::post("logout",[AuthController::class,"logout"]);

        // verification
        Route::post("checkcode",[AuthController::class,"checkcode"]);
        Route::post("resend",[AuthController::class,"resendCode"]);
    });
        // data
    Route::group(['prefix' => 'data'], function() {
        Route::get('provinces',[DataController::class , "provinces"]);
        Route::get('cities/{province_id}',[DataController::class , "cities"]);
        Route::get("majors",[DataController::class, "majors"]);
        Route::get("languages",[DataController::class, "languages"]);
        Route::get("types-education",[DataController::class, "types_education"]);
        Route::get("nationalities",[DataController::class, "nationalities"]);
        Route::get("projectType",[DataController::class, "projectTypes"]);
    });

});



