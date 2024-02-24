<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        // validation rule extended for sum of equal tow column
        Validator::extend('sum_equal', function($attribute, $value, $parameters, $validator) {
            $totalValue = 0;
            $equalTo = 0;
            $data = $validator->getData();
            if (str_contains($attribute, '.')){
                $valuePrams = array_values(array_filter(explode('.', $attribute),
                    function($arrayEntry) {
                        return !is_numeric($arrayEntry);
                    }
                ));
                if (isset($valuePrams[0]) && isset($valuePrams[1])){
                    $totalValue = array_sum(array_pluck(array_get($data, $valuePrams[0]), $valuePrams[1]));
                }
            }else{
                $totalValue = is_array($value) ? array_sum($value) : $value;
            }

            if (isset($parameters[0]) && str_contains($parameters[0], '.*.')){
                $arrayPrams = explode('.*.', $parameters[0]);
                if (isset($arrayPrams[0]) && isset($arrayPrams[1])){
                    $equalTo = array_sum(array_pluck(array_get($data, $arrayPrams[0]), $arrayPrams[1]));
                }
            }elseif(isset($parameters[0])){
                if(is_numeric($parameters[0])){
                    $equalTo = $parameters[0];
                }else{
                    $equalTo = isset($data[$parameters[0]]) ? $data[$parameters[0]] : 0;
                }
                $equalTo = is_array($equalTo) ? array_sum($equalTo) : $equalTo;
            }
            return ($totalValue == $equalTo);
        });

        Validator::extend('equal', function($attribute, $value, $parameters, $validator) {
            $equalTo = 0;
            $totalValue = is_array($value) ? array_sum($value) : $value;
            if(isset($parameters[0])){
                $equalTo = (float) $parameters[0];
                $equalTo =  is_array($equalTo) ? array_sum($equalTo) : $equalTo;
            }
            return ($totalValue == $equalTo);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
