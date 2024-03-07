<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Entity;
use App\Services\EntryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EntryServiceTest extends TestCase
{

    private $entryService;

    public function setUp(): void
    {
        parent::setUp();
        $this->entryService = new EntryService();
    }

    public function tearDown(): void
    {
        DB::table('categories')->delete();
        DB::table('entities')->delete();
        parent::tearDown();
    }

    public function testCollectEntriesReturnTrue()
    {       
        $return = $this->entryService->collectEntries();

        $this->assertTrue($return);
    }

    public function testCollectEntries()
    {
        // Simula la respuesta HTTP
        Http::fake([
            'https://api.publicapis.org/entries' => Http::response(['entries' => [
                ['API' => 'Test API', 'Description' => 'Test Description', 'Link' => 'http://test.com', 'Category' => 'Test Category'],
            ]], 200),
        ]);

        $result = $this->entryService->collectEntries();


        $this->assertTrue($result);

        $this->assertDatabaseHas('entities', [
            'api' => 'Test API',
            'description' => 'Test Description',
            'link' => 'http://test.com',
            'category_id' => Category::where('category', 'Test Category')->first()->id,
        ]);
    }

    public function testGetEntityByCategory()
    {
        $category = Category::factory()->create();

        // Creamos 3 entidades
        Entity::factory()->count(3)->create(['category_id' => $category->id]);

        $result = $this->entryService->getEntityByCategory($category);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
        $this->assertCount(3, $result);
    }
}
