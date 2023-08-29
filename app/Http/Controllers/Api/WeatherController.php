<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    
    public function current_weather(Request $request){


        $apiKey = env('OPENWEATHERMAP_KEY');
        $city = $request->input('city');

        $response = Http::get("http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric");

        if ($response->successful()) {
            $weatherData = $response->json();
            $temperature = $weatherData['main']['temp'];
            $windSpeed = $weatherData['wind']['speed'];
            $isRaining = collect($weatherData['weather'])->contains('main', 'Rain');

            return response()->json([
                'temperature' => $temperature,
                'wind_speed' => $windSpeed,
                'is_raining' => $isRaining,
            ]);
        } else {
            return response()->json(['error' => 'Could not fetch weather data'], 500);
        }


    }

}
