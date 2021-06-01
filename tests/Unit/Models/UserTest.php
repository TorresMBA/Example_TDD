<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;//Se cambio por lo que tra laravel

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_has_many_repositories()
    {   
        //Un repositorio pertenece a un usuario
        //Creo un usuario
        $user = new User;

        //esta configuracion es realmente una instancia de las coleciones,
        //tengo muchoss repositoris ahi
        $this->assertInstanceOf(Collection::class, $user->repositories);
    }
}
