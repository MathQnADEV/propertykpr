<?php

namespace App\Services;

use App\Models\Category;
use App\Models\City;
use App\Models\House;

class HouseService
{
    public function getCategoriesAndCities(): array
    {
        return [
            // withCount instead of with — only load the count, not all house records
            'categories' => Category::withCount('availableHouses')->latest()->get(),
            'cities'     => City::latest()->get(),
        ];
    }

    public function searchHouses(array $filters): array
    {
        $query = House::query()->where('is_available', true)->with(['category', 'city']);

        if (!empty($filters['city'])) {
            $query->where('city_id', $filters['city']);
        }

        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        // Paginate to avoid loading unbounded results into memory
        $houses   = $query->latest()->paginate(12);
        // Validated upstream so these always exist — but guard anyway
        $category = Category::find($filters['category'] ?? null);
        $city     = City::find($filters['city'] ?? null);

        return compact('houses', 'category', 'city');
    }

    public function getHouseDetails(House $house): House
    {
        $house->load(['photos', 'interest.bank']);
        return $house;
    }
}
