<?php  

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Plant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PlantController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return Inertia::render('Plant/Index', [
            'plants' => Plant::when($request->term, function ($query, $term) {
                $query->where('name', 'LIKE', '%'. $term .'%');
            })
            ->paginate(5)
            ->through(function ($plant, $key) {
                $plant['photo_url']= $plant->photoUrl(['w' => 40, 'h' => 40, 'fit' => 'crop']);
                return $plant;
            })
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store()
    {
        request()->validate([
            'name' => 'required|string|unique:plants,name,except,id',
            'description' => 'required',
            'price' => 'required|numeric',
            'photo' => 'required|image|mimes:jpeg,jpg,png,gif',
        ]);

        Plant::create([
            'name' => request('name'),
            'description' => request('description'),
            'price' => request('price'),
            'photo' => request()->file('photo')->store('plants'),
        ]);

        return redirect()->back()->with('message', 'Plant Created!!');
    }  

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function update(Request $request, Plant $plant)

    {
        request()->validate([
            'name' => 'required|string|unique:plants,name,'.$plant->id,
            'description' => 'required',
            'price' => 'required|numeric',
            'photo' => request()->file('photo') ? 'image|mimes:jpeg,jpg,png,gif' : '',
        ]);
        if (request()->file('photo') != null) {
            Storage::delete($plant->photo);
            $photo = request()->file('photo')->store('plants');
        } elseif($plant->photo != null) {  
            $photo = $plant->photo;
        } else {
            $photo = null;
        }
        
        $plant->update([
            'name' => request('name'),
            'description' => request('description'),
            'price' => request('price'),
            'photo' => $photo ,
        ]);

        return redirect()->back()->with('message', 'Plant Updated!!');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function destroy(Plant $plant)
    {
        $plant->delete();
        return back();
    }

    public function fetchOnlyTrashed(Request $request)
    {
        return Inertia::render('Plant/Index', ['trashedMode' => true,'plants' => Plant::onlyTrashed()->when($request->term, function ($query, $term) {
            $query->where('name', 'LIKE', '%'. $term .'%');
            })
            ->paginate(5)
            ->through(function ($plant, $key) {
                $plant['photo_url']= $plant->photoUrl(['w' => 40, 'h' => 40, 'fit' => 'crop']);
                return $plant;
            }),
        ]);
    }
    public function restoreOnlyTrashed(Request $request)
    {
        Plant::onlyTrashed()->find($request->id)->restore();
        return redirect()->back()->with('message', 'Plant Restored!!');
    }

    public function deleteOnlyTrashed(Request $request)
    {
        Storage::delete($request->photo);
        Plant::onlyTrashed()->find($request->id)->forceDelete();
        return redirect()->back()->with('message', 'Plant Deleted!!');
    }

    public function restoreAllTrashed()
    {
        Plant::onlyTrashed()->restore();
        return redirect()->back()->with('message', 'All Plant Restored!!');
    }

    public function deleteAllTrashed()
    {   
        $plant = Plant::onlyTrashed()->get();
        foreach ($plant as $data) {
            Storage::delete($data->photo);
        }
        Plant::onlyTrashed()->forceDelete();
        return redirect()->back()->with('message', 'All Plant Deleted!!');
    }
}