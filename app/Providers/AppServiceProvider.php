<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Add this custom validation rule.
    	Validator::extend('alpha_num_spaces', function ($attribute, $value) {

        // This will only accept alpha, numbers and spaces. 
        return preg_match('/^[\pL\pN\s]+$/u', $value); 

    	});
			
		Validator::extend('alpha_spaces', function ($attribute, $value) {

        // This will only accept alpha and spaces. 
        return preg_match('/^[\pL\s]+$/u', $value); 

    	});
		
		Validator::extend('cuenta_punto', function ($attribute, $value) {

        // This will only accept alpha, numbers and spaces. 
        return preg_match('/^[\pL\pN]{1,}\.[\pL\pN]+$/u', $value); 

    	});
		
		Validator::extend('alpha_num_special_char', function ($attribute, $value) {

        // This will only accept alpha, numbers and spaces. 
        return preg_match('/^[\pL\pN\.\-\s]+$/u', $value); 

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
