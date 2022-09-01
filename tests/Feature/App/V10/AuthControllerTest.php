<?php


namespace Tests\Feature\App\V10;


use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testRegister()
    {
        $name = 'testName';
        $email = 'testEmail@gmail.com';
        $password = 'testPassword';
        $response = $this->postJson('api/v1.0/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);
        $response->assertSuccessful();
        $this->assertEquals($name, $response['name']);
        $this->assertEquals(strtolower($email), $response['email']);
        $this->assertTrue(!empty($response['token']));
    }

    public function testRegisterByDuplicateEmail()
    {
        $name = 'testName';
        $email = 'testEmail@gmail.com';
        $password = 'testPassword';
        $response = $this->postJson('api/v1.0/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);
        $response->assertSuccessful();
        // duplicate register request with same email address must be failed
        $response = $this->postJson('api/v1.0/register', [
            'name' => "anotherName",
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}