<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    private $password = "password";

    public function testUserCreation()
    {
       	
       	$name = $this->faker->name();
       	$email = $this->faker->email();

        $response = $this->postJson('/api/auth/signup', [
            'name' => $name, 
            'email' => $email,
            'password' => $this->password, 
            'password_confirmation' => $this->password
        ]); 


        $response
            ->assertStatus(201)
            ->assertExactJson([
                'message' => "Successfully created user!",
            ]);
    }//testUserCreation

    public function testUserLogin()
    {
        $this->artisan('passport:install');

        $name = $this->faker->name();
        $email = $this->faker->email();

        $user = new User([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($this->password)
        ]);        
        
        $user->save(); 
        
        $response = $this->postJson('/api/auth/login', [
            'email' => $email,
            'password' => $this->password
        ]);

        // $response->assertSee("success");
        $response->assertStatus(200);
        $this->assertAuthenticated();
    }
}
