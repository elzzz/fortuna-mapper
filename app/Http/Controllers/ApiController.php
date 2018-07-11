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

        $zoom = $request->input('zoom');

        $markers = Marker::select('lat', 'long', 'description')->whereBetween('lat', [(float)$latFrom, (float)$latTo])->whereBetween('long', [(float)$longFrom, (float)$longTo])->get();

        $arr = $markers->toArray();
        $clustered = $this->cluster($arr, 100, $zoom);
        return response()->json($clustered);
    }

    private $OFFSET = 268435456;
    private $RADIUS = 85445659.4471;

    private function lonToX($lon) {
        return round($this->OFFSET + $this->RADIUS * $lon * pi() / 180);
    }

    private function latToY($lat) {
        return round($this->OFFSET - $this->RADIUS *
            log((1 + sin($lat * pi() / 180)) /
                (1 - sin($lat * pi() / 180))) / 2);
    }

    private function pixelDistance($lat1, $lon1, $lat2, $lon2, $zoom) {
        $x1 = $this->lonToX($lon1);
        $y1 = $this->latToY($lat1);

        $x2 = $this->lonToX($lon2);
        $y2 = $this->latToY($lat2);

        return sqrt(pow(($x1-$x2),2) + pow(($y1-$y2),2)) >> (21 - $zoom);
    }

    private function cluster($markers, $distance, $zoom) {
        $clustered = [];

        while (count($markers)) {
            $marker = array_pop($markers);
            $cluster = [];

            foreach ($markers as $key => $target) {
                $pixels = $this->pixelDistance(
                    $marker['lat'], $marker['long'], $target['lat'], $target['long'], $zoom
                );

                if ($distance > $pixels) {
                    unset($markers[$key]);
                    $cluster[] = $target;
                }
            }

            if (count($cluster) > 0) {
                $cluster[] = $marker;
                $clustered[] = $cluster;
            } else {
                $clustered[] = $marker;
            }

        }
        return $clustered;
    }
}
