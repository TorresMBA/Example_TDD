<?php

namespace Database\Factories;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RepositoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Repository::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {   
        //Con este estaria diciendo que el usuario que voy a crear va a pertenecer de manera automatica
        //en otras palbras en el test estoy creando un repositorio y cuando lo creo se va a crear automaticamnet
        //un usuario que me va a servir para que exista esta relacion
        return [
            'user_id' => User::factory(),
            'url' => $this->faker->url,
            'description' => $this->faker->text,
        ];
    }
}
