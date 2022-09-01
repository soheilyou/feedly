<?php


namespace Tests\Feature\App\V10;


use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    private UserRepositoryInterface $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = resolve(UserRepositoryInterface::class);
    }

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

    public function testLogin()
    {
        $name = 'testName';
        $email = 'testEmail@gmail.com';
        $password = 'testPassword';
        $this->userRepository->createUser($name, $email, $password);
        $response = $this->postJson('api/v1.0/login', [
            'email' => $email,
            'password' => $password,
        ]);
        $response->assertSuccessful();
        $this->assertEquals($name, $response['name']);
        $this->assertEquals(strtolower($email), $response['email']);
        $this->assertTrue(!empty($response['token']));
    }
    public function testLoginWrongCredentials()
    {
        $name = 'testName';
        $email = 'testEmail@gmail.com';
        $password = 'testPassword';
        $this->userRepository->createUser($name, $email, $password);
        $response = $this->postJson('api/v1.0/login', [
            'email' => $email,
            'password' => 'wrongPassword',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}