<?php

namespace Tests\Feature;

use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PropertiesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $data = Property::limit(Property::PAGINATION_COUNT)->get();
        foreach ($data as $item){
            $response->assertSee($item->description);
            $response->assertSee($item->title);
        }
    }
}
