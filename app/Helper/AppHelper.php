<?php

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\SearchQuery;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Subset;


	function random_id($prefix){

		return $prefix.Str::random(4).uniqid().Auth::id();

	}

	function country_string_to_code($string){

		$json = json_decode(File::get(public_path('storage/json/backup.json')), true); 

		foreach($json as $country){
	
			if ($country['name'] == $string) {
				return $country['code'];
			}

		}

		return $string;

	}

	function country_code_to_string($code){

		$json = json_decode(File::get(public_path('storage/json/backup.json')), true); 

		foreach($json as $country){
	
			if ($country['code'] == $code) {
				return $country['name'];
			}

		}

		return $code;

	}

	function country_code_to_currency($code){
		$json = json_decode(File::get(public_path('storage/json/country.json')), true); 

		foreach($json as $country){
	
			if ($country['code'] == $code) {
				return strtoupper($country['currency']);
			}

		}

		return $code;
	}

	function searchQuery($query){

		if ($query->c == 'click') {
			SearchQuery::find($query->i)->increment('click');
		}else{
			$search = new SearchQuery;
		    $search->user_id = Auth::id();
		    $search->query = $query->search;
		    $search->save();
		}

	}

	function global_time(){

	    $ip = file_get_contents("http://ipecho.net/plain");
	    $url = 'http://ip-api.com/json/'.$ip;
	    $tz = file_get_contents($url);
	    $tz = json_decode($tz,true)['timezone'];
	    return $tz;
	}