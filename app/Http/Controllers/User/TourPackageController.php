<?php

namespace App\Http\Controllers\User;

use App\Model\Admin\TourPackage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TourPackageController extends Controller
{
    public function index()
    {
        $tourPackages = TourPackage::wherePublished(1)->with('photos', 'description', 'admin')->get();

        foreach ($tourPackages as $tourPackage) {
            unset($tourPackage['duration'], $tourPackage['origin'], $tourPackage['destination'], $tourPackage['transport'], $tourPackage['tax'],
        $tourPackage['accommodation'], $tourPackage['breakfast'], $tourPackage['lunch'], $tourPackage['dinner'], $tourPackage['sight_seeing'],
        $tourPackage['guide_service'], $tourPackage['plans'], $tourPackage['admin_id'], $tourPackage['published_by'], $tourPackage['published']);

            $tourPackage['photo'] = $tourPackage['photos'][0];
            unset($tourPackage['photos']);
        }

        return response()->json(['tourPackages' => $tourPackages], 200);
    }

    public function show($slug) {
        $tourPackage = TourPackage::whereSlug($slug)->with('categories', 'tags', 'photos', 'description', 'admin')->first();
        if ($tourPackage) {
            $tourPackage['plans'] = json_decode($tourPackage->plans);
            return response()->json(['tourPackage' => $tourPackage], 200);
        }
        else return response()->json(['error' => 'Not found'], 404);
    }
}
