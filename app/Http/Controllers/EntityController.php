<?php

namespace App\Http\Controllers;

use App\Http\Resources\EntityResource;
use App\Models\Category;
use App\Services\EntryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    private EntryService $entryService;

    public function __construct(EntryService $entryService){
        $this->entryService = $entryService;
    }

    public function collectEntries(): JsonResponse 
    {
        $success = $this->entryService->collectEntries();
        if($success == false) {
            return response()->json(['success' => false,'message' => 'An error occurred while collecting the entities.'], 400);
        }

        return response()->json(['success' => true, 'message' => 'Entities have been collected successfully.'], 200);
    }

    public function getEntitiesByCategory(Request $request, $categoryName): JsonResponse 
    {
        $category = Category::where('category', ucwords($categoryName))->first();

        if (!$category) {
            return response()->json(['success' => false,'message' => 'Category not found.'], 404);
        }

        $entities = $this->entryService->getEntityByCategory($category);
        if(isset($entities) && !$entities->isEmpty()){
            return response()->json(['success' => true, 'data' => EntityResource::collection($entities)], 200);
        }
        if(isset($entities)) {
            return response()->json(['success' => false,'message' => 'No results found.'], 404);
        }
        return response()->json(['success' => false,'message' => 'An error occurred while searching for entities.'], 400);
    }
}
