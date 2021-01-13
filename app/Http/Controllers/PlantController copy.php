<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlantRequest;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PlantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Plant/Index', ["plant" => Plant::when($request->term, function ($query, $term) {
                $query->where('name', 'LIKE', '%'. $term .'%');
            })
            ->paginate(5)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Plant/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        request()->validate([
            'name' => 'required|string|unique:plants,name,except,id',
            'description' => 'required',
            'price' => 'required|numeric',
            'plant_photo_path' => request('plant_photo_path') ? 'image|mimes:jpeg,jpg,png,gif' : '',
        ]);

        Plant::create([
            'name' => request('name'),
            'description' => request('description'),
            'price' => request('price'),
            'plant_photo_path' => request()->file('plant_photo_path')->store('plants'),
        ]);

        return back()->with('success','Clean bandit sean paul');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plant  $plant
     * @return \Illuminate\Http\Response
     */
    public function show(Plant $plant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plant  $plant
     * @return \Illuminate\Http\Response
     */
    public function edit(Plant $plant)
    {
        return inertia('Plant/Edit',['plant' => $plant]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Plant  $plant
     * @return \Illuminate\Http\Response
     */
    public function update(Plant $plant)
    {
        request()->validate([
            'name' => 'required|string|unique:plants,name,except,id',
            'description' => 'required',
            'price' => 'required|numeric',
            'plant_photo_path' => 'nullable|image|mimes:jpeg,jpg,png,gif',
        ]);
        
        if (request('plant_photo_path')) {
            Storage::delete($plant->plant_photo_path);
            $plant_photo_path = request()->file('plant_photo_path')->store('plants');
        } else if($plant->plant_photo_path) {
            $plant_photo_path = $plant->plant_photo_path;
        } else {
            $plant_photo_path = null;
        }
        

        $plant->update([
            'name' => request('name'),
            'description' => request('description'),
            'price' => request('price'),
            'plant_photo_path' => $plant_photo_path ,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plant  $plant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plant $plant)
    {
        $plant->delete();
        return redirect()->back();
    }

    public function permanentDelete(Plant $plant)
    {
        // dd($plant->plant_photo_path);
        dd(Storage::delete($plant->plant_photo_path));
        $plant = Plant::where('id',$plant->id);
        dd($plant->plant_photo_path);
        
        return redirect()->back();
    }

    public function restore(Plant $plant, $id)
    {
        $plant->onlyTrashed()->where('id',$id)->restore();
        return redirect()->back();
    }

    public function trashed(Request $request)
    {
        // return inertia("Plant/Trashed", ['plant' => Plant::onlyTrashed()->paginate(5)] );
        
        return inertia("Plant/Trashed",['plant'=> Plant::onlyTrashed()->when($request->term, function ($query, $term) {
                $query->where('name', 'LIKE', '%'. $term .'%');
            })->paginate(5)
        ]);
    }
    
    

}
