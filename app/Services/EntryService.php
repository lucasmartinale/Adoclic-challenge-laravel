<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Entity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;

final class EntryService
{

    public function collectEntries(): bool
    {
        $response = Http::get('https://api.publicapis.org/entries');
        if ($response->successful()) {
            $entries = $response->json()['entries'];
            
            try {
                foreach ($entries as $entryData) {
                    $category = Category::firstOrCreate(['category' => $entryData['Category']]);
                    $now = Carbon::now();

                    Entity::updateOrCreate([
                        'api' => $entryData['API'],
                        'description' => $entryData['Description'],
                        'link' => $entryData['Link'],
                        'category_id' => $category->id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }

    public function getEntityByCategory(Category $category): NULL|Collection
    {
        $entities = $category->entities;
        if(isset($entities)) {
            return $entities;
        }
        return NULL;
    }
}
