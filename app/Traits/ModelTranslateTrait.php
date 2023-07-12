<?php

namespace App\Traits;

use App\Models\ProvinceTranslate;

trait ModelTranslateTrait
{

    public $className = self::class;

    private function get_class_name_use(){
        $namespaceclass = explode("\\",$this->className );
        $name = end($namespaceclass);
        return strtolower($name);
    }

    public function all_translate()
    {


        return $this->hasMany($this->className ."Translate", $this->get_class_name_use().'_id' , 'id');
    }


    public function locale_translate()
    {

        return $this->hasOne($this->className ."Translate")
            ->whereLang(config('app.locale'))
            ->select($this->get_class_name_use().'_id', 'name');
    }

    public function first_translate()
    {


        return $this->hasOne($this->className ."Translate")->select($this->get_class_name_use().'_id', 'name');
    }



    public function getNameAttribute()
    {
        if ($this->locale_translate) {
            return $this->locale_translate->name;
        } else {
            return $this->first_translate->name;
        }
    }


    // public function getDescriptionAttribute()
    // {
    //     if ($this->locale_translate) {
    //         return $this->locale_translate->name;
    //     } else {
    //         return $this->first_translate->name;
    //     }
    // }
}
