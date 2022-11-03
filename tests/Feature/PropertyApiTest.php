<?php

namespace Tests\Feature;

use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_get_all_properties()
    {

        $properties = Property::factory(10)->create();

        //dd(route('properties.index'));

        $this->getJson(route('properties.index'))
        ->assertJsonFragment([
            'code' => $properties[0]->code
        ]);
    }

    /** @test */
    function can_get_one_property()
    {

        $property = Property::factory()->create();

        $this->getJson(\route('properties.show', $property))
        ->assertJsonFragment([
            'code' => $property->code
        ]);

    }

    /** @test */
    function can_create_properties()
    {
        $this->postJson(route('properties.store'), [])
        ->assertJsonValidationErrorFor('code');

        $this->postJson(route('properties.store'), [
            'code' => 'New property'
        ]);

        $this->assertDatabaseHas('properties', [
            'code' => 'New property'
        ]);
    }

    /** @test */
    function can_update_properties()
    {
        $property = Property::factory()->create();

        $this->patchJson(route('properties.update', $property), [])
        ->assertJsonValidationErrorFor('code');

        $this->patchJson(route('properties.update', $property), [
            'code' => 'Property edited'
        ])->assertJasonFragment([
            'code' => 'Property edited'
        ]);
    }

    /** @test */
    function can_delete_properties()
    {

        $property = Property::factory()->create();

        $this->deleteJson(route('properties.destroy', $property))
        ->assertNoContent();

        $this->assertDatabaseCount('properties', 0);
    }
}
