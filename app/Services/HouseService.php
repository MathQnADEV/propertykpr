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
            'categories' => Category::with('availableHouses')->latest()->get(),
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

        $houses   = $query->get();
        // Validated upstream so these always exist — but guard anyway
        $category = Category::find($filters['category'] ?? null);
        $city     = City::find($filters['city'] ?? null);

        return compact('houses', 'category', 'city');
    }

    public function getHouseDetails(House $house): House
    {
        $house->load(['photos', 'facilities', 'facilities.facility', 'interest.bank']);
        return $house;
    }
}
