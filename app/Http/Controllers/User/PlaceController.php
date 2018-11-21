<?php

namespace App\Http\Controllers\User;

use App\Model\Admin\Place\Place;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlaceController extends Controller
{
    public function search($text)
    {
        $textParts = explode(',', $text);

        $places = Place::search($textParts[0])->where('published', 1)->get();
        foreach ($places as $place) {
            $place->description;
            $place->photos;
        }
        return response()->json(['places' => $places], 200);
    }

    public function show($slug)
    {
        $place = Place::whereSlug($slug)->with('categories', 'tags', 'photos', 'description')->first();
        if ($place) {
            return response()->json(['place' => $place], 200);
        } else return response()->json(['error' => 'Not found'], 404);
    }

    public function getWeatherStatus($lat, $lon)
    {

        $baseUrl = 'https://api.darksky.net/forecast/';
        $latitude = $lat;
        $longitude = $lon;
        $query = '?units=si';

        $url = $baseUrl . DARK_SKY_API_KEY . '/' . $latitude . ',' . $longitude . $query;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);

        $obj = json_decode($result);

        $today = Carbon::today($obj->timezone);

        $weather = array();

        foreach ($obj->daily->data as $day) {

            $today->diffInDays(Carbon::createFromTimestamp($day->time, $obj->timezone)) == 0 ? $datName = "Today" : $datName = Carbon::createFromTimestamp($day->time, $obj->timezone)->format('D');
            $dailyForecast = array();
            $dailyForecast['day'] = $datName;
            $dailyForecast['src'] = "/img/animated/{$day->icon}.svg";
            $dailyForecast['summary'] = $day->summary;
            $dailyForecast['minTemp'] = $day->temperatureMin;
            $dailyForecast['maxTemp'] = $day->temperatureMax;

            array_push($weather, $dailyForecast);
        }

        return response()->json(['weather' => $weather], 200);
    }

    public function geocode(Request $request)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($request['name']) . '&key=' . MAPS_API_KEY);
        $result = curl_exec($ch);
        curl_close($ch);

        $obj = json_decode($result);


        if (strcmp($obj->status, "OK") == 0) {
            return  response()->json(['location' => $obj->results[0]->geometry->location], 200);
        } else return response()->json(['error' => 'Something went wrong'], 404);
    }

    function reverseGeocode(Request $request)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $request['latlng'] . '&key=' . MAPS_API_KEY);
        $result = curl_exec($ch);
        curl_close($ch);

        $obj = json_decode($result);

        if (strcmp($obj->status, "OK") == 0) {
            return response()->json(['location' => $obj->results[0]->formatted_address], 200);
        } else return response()->json(['error' => 'Location not found'], 404);
    }

    public function autocomplete(Request $request)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'https://maps.googleapis.com/maps/api/place/autocomplete/json?input=' . urlencode($request['queryText']) . '&key=' . PLACES_API_KEY);
        $result = curl_exec($ch);
        curl_close($ch);

        $obj = json_decode($result);

        $locations = array();

        foreach ($obj->predictions as $prediction) {
            array_push($locations, $prediction->description);
        }

        return response()->json(['locations' => $locations], 200);
    }
}
