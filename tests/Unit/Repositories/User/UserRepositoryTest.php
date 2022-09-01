<?php


namespace Tests\Unit\Repositories\User;


use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private UserRepository $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = resolve(UserRepository::class);
    }

    public function testCreateUser()
    {
        $name = 'testName';
        $email = 'testEmail@gmail.com';
        $password = 'testPassword';
        $user = $this->userRepository->createUser($name, $email, $password);
        $this->assertEquals($name, $user->name);
        $this->assertEquals($email, $user->email);
        $this->assertTrue(Hash::check($password, $user->password));
        $this->assertTrue($user instanceof User);
    }

    public function testCreateUserCheckDatabase()
    {
        $name = 'testName';
        $email = 'testEmail@gmail.com';
        $password = 'testPassword';
        $user = $this->userRepository->createUser($name, $email, $password);
        // check database
        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
            'password' => $user->password
        ]);
    }
}