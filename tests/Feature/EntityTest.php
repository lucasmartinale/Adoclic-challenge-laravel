<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Entity;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EntityTest extends TestCase
{
    private $category;
    private $entity;

    public function setUp(): void
    {
        parent::setUp();
        DB::table('entities')->delete();
        DB::table('categories')->delete();

        $this->category = Category::factory()->create();

        $this->entity = Entity::factory()->create([
            'category_id' => $this->category->id,
        ]);
    }

    public function tearDown(): void
    {
        DB::table('entities')->delete();
        DB::table('categories')->delete();
        parent::tearDown();
    }


    public function testEntityCreation()
    {
        $retrievedEntity = Entity::find($this->entity->id);

        $this->assertNotNull($retrievedEntity);
        $this->assertEquals($this->entity->api, $retrievedEntity->api);
        $this->assertEquals($this->entity->description, $retrievedEntity->description);
        $this->assertEquals($this->entity->link, $retrievedEntity->link);
        $this->assertEquals($this->category->id, $retrievedEntity->category_id);
    }

    public function testEntityRelations()
    {
        $retrievedEntity = Entity::find($this->entity->id);

        $this->assertInstanceOf(Category::class, $retrievedEntity->category);
        $this->assertEquals($this->category->id, $retrievedEntity->category->id);
    }

    public function testEntityUpdate()
    {
        $newApiValue = 'New API Value';
        $this->entity->update(['api' => $newApiValue]);

        $updatedEntity = Entity::find($this->entity->id);

        $this->assertEquals($newApiValue, $updatedEntity->api);
    }
}
