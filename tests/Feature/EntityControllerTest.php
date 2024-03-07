<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Entity;
use App\Services\EntryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EntityControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    private $category;
    private $entity;
    
    public function setUp(): void
    {
        parent::setUp();
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

    public function testCanCollectEntries()
    {
        $response = $this->get('api/collect-entries');

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'message' => 'Entities have been collected successfully.']);
    }

    public function testReturnsErrorOnFailedCollection()
    {
        $this->mock(EntryService::class, function ($mock) {
            $mock->shouldReceive('collectEntries')->andReturn(false);
        });

        $response = $this->get('api/collect-entries');

        $response->assertStatus(400)
            ->assertJson(['success' => false, 'message' => 'An error occurred while collecting the entities.']);
    }

    public function testCanGetEntitiesByCategory()
    {

        $response = $this->get('api/' . $this->category->category);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function testReturnsNotFoundForInvalidCategory()
    {
        $response = $this->get('api/invalid-category');

        $response->assertStatus(404)
            ->assertJson(['success' => false, 'message' => 'Category not found.']);
    }

    public function testReturnsNotFoundIfNoResultsFound()
    {
        $otherCategory = Category::factory()->create();
        $response = $this->get('api/' . $otherCategory->category);

        $response->assertStatus(404)
            ->assertJson(['success' => false, 'message' => 'No results found.']);
    }

}
