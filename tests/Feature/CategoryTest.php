<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Entity;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CategoryTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        DB::table('entities')->delete();
        DB::table('categories')->delete();
    }

    public function tearDown(): void
    {
        DB::table('entities')->delete();
        DB::table('categories')->delete();
        parent::tearDown();
    }

    public function testEntityModel()
    {
        $category = Category::factory()->create();

        $entity = Entity::factory()->create([
            'category_id' => $category->id,
        ]);

        $this->assertEquals($category->id, $entity->category_id);

    }
}

