<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;
use App\PageTranslated;

class PagesController extends Controller
{
    public function index($lang, $page = '', Request $request)
    {
    	switch ($page) {
    		case '':
    		case 'home': 			 $page_view = 'site.pages.home'; break;
    		case 'work-permit': 	 $page_view = 'site.pages.work-permit'; break;
    		case 'Manitoba':         $page_view = 'site.pages.manitoba'; break;
    		case 'terms_and_conditions': 		 $page_view = 'site.pages.terms_and_conditions'; break;
    		case 'get_in_touch_with_us': 			 $page_view = 'site.pages.get_in_touch_with_us'; break;
    		case 'privacy_policy':  $page_view = 'site.pages.privacy_policy'; break;
    		case 'canadian_economy': 			 $page_view = 'site.pages.canadian_economy'; break;
            case 'rising_children':   $page_view = 'site.pages.rising_children'; break;
            case 'banking_in_canada':   $page_view = 'site.pages.banking_in_canada'; break;
            case 'canadian_experience_program':   $page_view = 'site.pages.canadian_experience_program'; break;
            case 'canadian_dream':   $page_view = 'site.pages.canadian_dream'; break;
            case 'canadian_citizebship':   $page_view = 'site.pages.canadian_citizebship'; break;
            case 'health_in_canada':   $page_view = 'site.pages.health_in_canada'; break;
            case 'quebec_skilled_workers':   $page_view = 'site.pages.quebec_skilled_workers'; break;
            case 'quebec_experience_class':      $page_view = 'site.pages.quebec_experience_class'; break;
            case 'working_in_quebec':      $page_view = 'site.pages.working_in_quebec'; break;
            case 'quebec_enterpreneur_porogram':      $page_view = 'site.pages.quebec_enterpreneur_porogram'; break;
            case 'study_in_quebec':      $page_view = 'site.pages.study_in_quebec'; break;
            case 'quebec_investor_program':      $page_view = 'site.pages.quebec_investor_program'; break;
            case 'quebec_immigration_program_for_businessman':      $page_view = 'site.pages.quebec_immigration_program_for_businessman'; break;
    		case 'quebec_self_employed_program': 	 $page_view = 'site.pages.quebec_self_employed_program'; break;

            case 'british_columbia':     $page_view = 'site.pages.british_columbia'; break;
            case 'albertia':     $page_view = 'site.pages.albertia'; break;
            case 'new_brunswick':     $page_view = 'site.pages.new_brunswick'; break;
            case 'manitoba':     $page_view = 'site.pages.manitoba'; break;
            case 'nova_scotia':     $page_view = 'site.pages.nova_scotia'; break;
            case 'quebec_self_employed_program':     $page_view = 'site.pages.quebec_self_employed_program'; break;
            case 'quebec_self_employed_program':     $page_view = 'site.pages.quebec_self_employed_program'; break;
            case 'quebec_self_employed_program':     $page_view = 'site.pages.quebec_self_employed_program'; break;
            case 'quebec_self_employed_program':     $page_view = 'site.pages.quebec_self_employed_program'; break;
            case 'quebec_self_employed_program':     $page_view = 'site.pages.quebec_self_employed_program'; break;
            case 'quebec_self_employed_program':     $page_view = 'site.pages.quebec_self_employed_program'; break;
            case 'quebec_self_employed_program':     $page_view = 'site.pages.quebec_self_employed_program'; break;

    		//case 'sign-in': 		 $page_view = 'site.pages.sign-in'; break;quebec_immigration_program_for_businessman
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
