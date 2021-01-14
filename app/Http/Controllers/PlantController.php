<?php  

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Plant;
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
            'categories' => Category::select('id','title')->get(),
            'plants' => Plant::with('categories:id,title')
            ->when($request->term, function ($query, $term) {
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
        // dd(request('categories'));
        request()->validate([
            'name' => 'required|string|unique:plants,name,except,id',
            'description' => 'required',
            'price' => 'required|numeric',
            'photo' => 'required|image|mimes:jpeg,jpg,png,gif',
            'categories' => 'required',
        ]);

        $plant = Plant::create([
            'name' => request('name'),
            'description' => request('description'),
            'price' => request('price'),
            'photo' => request()->file('photo')->store('plants'),
        ]);
        foreach (request('categories') as $item) {
            $items[] = $item['id'];
        }
        $plant->categories()->sync($items);
        
        return redirect()->back()->with('message', 'Plant Created!!');
    }  

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function update(Request $request, Plant $plant)

    {
        $category = json_decode($request['data']);   
        // Validate
        $request->validate([
            'data.categories' => 'array',        
            'name' => 'required|string|unique:plants,name,'.$plant->id,
            'description' => 'required',
            'price' => 'required|numeric',
            'photo' => request()->file('photo') ? 'image|mimes:jpeg,jpg,png,gif|size:1024' : '',
        ]);
        if (request()->file('photo')) {
            Storage::delete($plant->photo);
            $photo = request()->file('photo')->store('plants');
        } else if($plant->photo != null) {  
            $photo = $plant->photo;
        } else {
            $photo = null;
        }
        
        foreach ($category->categories as $item) {
            $items[] = $item->id;
        }

        $plant->update([
            'name' => request('name'),
            'description' => request('description'),
            'price' => request('price'),
            'photo' => $photo,
        ]);
        $plant->categories()->sync($items);
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
        return Inertia::render('Plant/Index', [
            'trashedMode' => true,
            'categories' => Category::select('id','title')->get(),
            'plants' => Plant::onlyTrashed()
            ->with('categories:id,title')
            ->when($request->term, function ($query, $term) {
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
        return back();
    }

    public function deleteOnlyTrashed(Request $request)
    {
        Storage::delete($request->photo);
        Plant::onlyTrashed()->find($request->id)->forceDelete();
        return back();
    }

    public function restoreAllTrashed()
    {
        Plant::onlyTrashed()->restore();
        return back();
    }

    public function deleteAllTrashed()
    {   
        $plant = Plant::onlyTrashed()->get();
        foreach ($plant as $data) {
            Storage::delete($data->photo);
        }
        Plant::onlyTrashed()->forceDelete();
        return back();
    }
}