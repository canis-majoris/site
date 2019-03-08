<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index($lang, $page = '', Request $request)
    {
    	switch ($page) {
    		case '':
    		case 'home': 			 $page_view = 'site.pages.home'; break;
    		case 'charts-page': 	 $page_view = 'site.pages.charts-page'; break;
    		case 'commission-plans': $page_view = 'site.pages.commission-plans'; break;
    		case 'contact-us': 		 $page_view = 'site.pages.contact-us'; break;
    		case 'faq': 			 $page_view = 'site.pages.faq'; break;
    		case 'marketing-tools':  $page_view = 'site.pages.marketing-tools'; break;
    		case 'news': 			 $page_view = 'site.pages.news'; break;
    		case 'partner-types': 	 $page_view = 'site.pages.partner-types'; break;
    		//case 'sign-in': 		 $page_view = 'site.pages.sign-in'; break;
    		//case 'sign-up': 		 $page_view = 'site.pages.sign-up'; break;
    		default: $page_view = 'site.pages.home'; break;
    	}

    	//dd($page_view, session('loggedUser'));

    	return view($page_view);
    }

    public function profile($lang, Request $request) {
    	return view('pages.charts-page');
    }
}
