<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RepositoryControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_guest()
    {
        $this->get('repositories')->assertRedirect('login');        // index
        $this->get('repositories/1')->assertRedirect('login');      // show
        $this->get('repositories/1/edit')->assertRedirect('login'); // edit
        $this->put('repositories/1')->assertRedirect('login');      // update
        $this->delete('repositories/1')->assertRedirect('login');   // destroy
        $this->get('repositories/create')->assertRedirect('login'); // create
        $this->post('repositories', [])->assertRedirect('login');   // store
    }

    public function test_index_empty()  
    {
        Repository::factory()->create(); // user_id = 1

         $user = User::factory()->create(); // id = 2

        $this
            ->actingAs($user)
            ->get('repositories')
            ->assertStatus(200)
            ->assertSee('No hay repositorios creados');
    }

    public function test_index_with_data()  
    {
        //crearmos un usuario falso
        $user = User::factory()->create();
        $repository = Repository::factory()->create(['user_id' => $user->id]);

        $this
            ->actingAs($user)
            ->get('repositories')
            ->assertStatus(200)
            ->assertSee($repository->id)
            ->assertSee($repository->url);
    }

    public function test_create()
    {
        //crearmos un usuario falso
        $user = User::factory()->create();
        
        $this->actingAs($user)
            ->get('repositories/create')
            ->assertStatus(200);
    }

    public function test_store()
    {
        //Simula la data que enviariamos de un formulario
        $data = [
            'url' => $this->faker->url,
            'description' => $this->faker->text,
        ];

        //crearmos un usuario falso
        $user = User::factory()->create();

        //y iniciamos sesion desde el testing
        //actingAs -> Actua como. actua como el usuario que acabo de crear
        $this->actingAs($user)
            ->post('repositories', $data)
            ->assertRedirect('repositories');

        //Y verificar que esta informacion se salvo en una db        
        $this->assertDatabaseHas('repositories', $data);
    }

    public function test_validation_store()
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->post('repositories', [])
            ->assertStatus(302)
            ->assertSessionHasErrors(['url', 'description']);
    }

    public function test_update()
    {
        //crearmos un usuario falso
        $user = User::factory()->create();

        //Crear un repositorio con el id del usuario creado anteriormente
        $repository = Repository::factory()->create(['user_id' => $user->id]);

        //Simula la data que enviariamos de un formulario para actualizar
        $data = [
            'url' => $this->faker->url,
            'description' => $this->faker->text,
        ];

        //y iniciamos sesion desde el testing
        //actingAs -> Actua como. actua como el usuario que acabo de crear
        $this->actingAs($user)
            ->put("repositories/$repository->id", $data)
            ->assertRedirect("repositories/$repository->id/edit");

        //Y verificar que esta informacion exista en la db        
        $this->assertDatabaseHas('repositories', $data);
    }

    public function test_update_policy()
    {
        $user = User::factory()->create(); // id = 1
      
        $repository = Repository::factory()->create(); // user_id = 2

        $data = [
            'url' => $this->faker->url,
            'description' => $this->faker->text,
        ];     

        $this->actingAs($user)
            ->put("repositories/$repository->id", $data)
            ->assertStatus(403);
    }

    public function test_validation_update()
    {
        $repository = Repository::factory()->create();

        $user = User::factory()->create();

        $this->actingAs($user)
            ->put("repositories/$repository->id", [])
            ->assertStatus(302)
            ->assertSessionHasErrors(['url', 'description']);
    }

    public function test_destroy()
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create([ 'user_id' => $user->id ]);  

        $this->actingAs($user)
            ->delete("repositories/$repository->id")
            ->assertRedirect("repositories");
        
        $this->assertDatabaseMissing('repositories', [
            'id' => $repository->id
        ]);
    }

    public function test_destroy_policy()
    {
        $user = User::factory()->create(); // id = 1
        $repository = Repository::factory()->create(); // user_id = 2 
        
        $this->actingAs($user)
            ->delete("repositories/$repository->id")
            ->assertStatus(403);
    }

    public function test_show()
    {
        //crearmos un usuario falso
        $user = User::factory()->create();

        //Crear un repositorio con el id del usuario creado anteriormente
        $repository = Repository::factory()->create(['user_id' => $user->id]);
        
        $this->actingAs($user)
            ->get("repositories/$repository->id")
            ->assertStatus(200);
    }

    public function test_show_policy()
    {
        $user = User::factory()->create();

        $repository = Repository::factory()->create();

        $this->actingAs($user)
            ->get("repositories/$repository->id")
            ->assertStatus(403);
    }

    public function test_edit()
    {
        //crearmos un usuario falso
        $user = User::factory()->create();

        //Crear un repositorio con el id del usuario creado anteriormente
        $repository = Repository::factory()->create(['user_id' => $user->id]);
        
        $this->actingAs($user)
            ->get("repositories/$repository->id/edit")
            ->assertStatus(200)
            ->assertSee($repository->url)
            ->assertSee($repository->description);
    }

    public function test_edit_policy()
    {
        $user = User::factory()->create();

        $repository = Repository::factory()->create();

        $this->actingAs($user)
            ->get("repositories/$repository->id/edit")
            ->assertStatus(403);
    }
}
