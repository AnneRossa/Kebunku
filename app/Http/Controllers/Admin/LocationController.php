<?php

namespace App\Http\Controllers\Admin;
use App\Models\Location;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    public function index() {
        $locations = Location::get();

        return Inertia::render('Admin/Location/Index',['locations'=>$locations]);
    }

    // store
    public function store(Request $request)
    {

        $location = new Location;
        $location->name = $request->name;
        $location->slug = $request->slug;
        $location->save();
        return redirect()->route('admin.locations.index')->with('success', 'Location created successfully.');
    }

    //update
    public function update(Request $request, $id)
    {

        $location = Location::findOrFail($id);

        // dd($location);
        $location->name = $request->name;
        $location->slug = $request->slug;

        $location->update();
        return redirect()->route('admin.locations.index')->with('success', 'Location updated successfully.');
    }

    // delete
    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();
        return redirect()->route('admin.locations.index')->with('success', 'Location deleted successfully.');
    }
}
