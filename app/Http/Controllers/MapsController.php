<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mapper;
use App\Marker;

class MapsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Mapper::map(55.751244, 37.618423, ['marker' => false]);
        $markers = Marker::orderBy('created_at', 'desc')->paginate(10);
        foreach(Marker::all() as $marker) {
            Mapper::informationWindow($marker->lat, $marker->long, $marker->description . "<br><small>Added by ".$marker->user->name."</small>");
        }
        return view('maps.map')->with('markers', $markers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Mapper::map(55.751244, 37.618423, ['marker' => false, 'eventBeforeLoad' => 'addMarkerListener(map);']);
        return view('maps.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required',
            'lat' => 'required',
            'long' => 'required',
        ]);

        $marker = new Marker;
        $marker->description = $request->input('description');
        $marker->lat = $request->input('lat');
        $marker->long = $request->input('long');
        $marker->user_id = auth()->user()->id;
        $marker->save();

        return redirect('/mapper')->with('success', 'Mark Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Mapper::map(55.751244, 37.618423, ['marker' => false]);
        $marker = Marker::find($id);
        Mapper::informationWindow($marker->lat, $marker->long, $marker->description . "<br><small>Added by ".$marker->user->name."</small>");
        return view('maps.show')->with('marker', $marker);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Mapper::map(55.751244, 37.618423, ['marker' => false, 'eventBeforeLoad' => 'addMarkerListener(map);']);
        $marker = Marker::find($id);
        Mapper::informationWindow($marker->lat, $marker->long, $marker->description . "<br><small>Added by ".$marker->user->name."</small>");

        if(auth()->user()->id != $marker->user_id) {
            return redirect('/mapper')->with('error', 'Unauthorized page');
        }

        return view('maps.edit')->with('marker', $marker);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'description' => 'required',
            'lat' => 'required',
            'long' => 'required',
        ]);

        $marker = Marker::find($id);
        $marker->description = $request->input('description');
        $marker->lat = $request->input('lat');
        $marker->long = $request->input('long');
        $marker->save();

        return redirect('/mapper')->with('success', 'Mark Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marker = Marker::find($id);

        if (auth()->user()->id !== $marker->user_id) {
            return redirect('/posts')->with('error', 'Unauthorized page');
        }

        $marker->delete();

        return redirect('/mapper')->with('success', 'Marker Removed');
    }
}
