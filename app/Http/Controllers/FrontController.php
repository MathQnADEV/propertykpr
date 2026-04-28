<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\House;
use App\Models\User;
use App\Services\HouseService;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    protected $houseService;

    public function __construct(HouseService $houseService)
    {
        $this->houseService = $houseService;
    }

    public function index()
    {
        $data = $this->houseService->getCategoriesAndCities();
        return view('front.index', $data);
    }

    public function browse(Request $request)
    {
        $validated = $request->validate([
            'category' => 'nullable|integer|exists:categories,id',
            'city'     => 'nullable|integer|exists:cities,id',
            'search'   => 'nullable|string|max:100',
        ]);

        $query = House::query()
            ->where('is_available', true)
            ->with(['category', 'city']);

        if (!empty($validated['category'])) {
            $query->where('category_id', $validated['category']);
        }

        if (!empty($validated['city'])) {
            $query->where('city_id', $validated['city']);
        }

        if (!empty($validated['search'])) {
            $query->where('name', 'like', '%' . $validated['search'] . '%');
        }

        $houses     = $query->latest()->paginate(12);
        $categories = Category::select('id', 'name', 'slug')->get();
        $cities     = City::select('id', 'name')->get();

        return view('front.browse', compact('houses', 'categories', 'cities'));
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|integer|exists:categories,id',
            'city'     => 'required|integer|exists:cities,id',
        ]);

        $data = $this->houseService->searchHouses($validated);
        return view('front.search', $data);
    }

    public function category(Category $category)
    {
        $category->load(['availableHouses']);
        return view('front.category', compact('category'));
    }

    public function details(House $house)
    {
        if (!$house->is_available) {
            abort(404);
        }

        $houseDetails = $this->houseService->getHouseDetails($house);
        $agents       = $house->agent
            ? collect([$house->agent])
            : collect();
        $mapsApiKey   = config('services.google.maps_api_key');

        return view('front.details', compact('houseDetails', 'agents', 'mapsApiKey'));
    }
}
