<?php

namespace App\Http\Controllers;

use App\Marker;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index(Request $request)
    {
        $latFrom = $request->input('latFrom');
        $latTo = $request->input('latTo');

        $longFrom = $request->input('longFrom');
        $longTo = $request->input('longTo');

        $markers = Marker::select('lat', 'long', 'description')->whereBetween('lat', [(float)$latFrom, (float)$latTo])->whereBetween('long', [(float)$longFrom, (float)$longTo])->get();

        return response()->json($markers);
    }
}
