<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Request;
use Config;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        $this->setRouteLang();
    }

    public function setRouteLang()
    {
    	$language = Request::segment(4);
		$routeLang = '';

		if (isset(config('app.locales')[$language])) {
		    App::setLocale($language);
		    $routeLang = $language;
		}

		Config::set('app.routeLang', $routeLang);
    }
}
