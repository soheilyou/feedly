<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @return mixed
     */
    public function createUser(
        string $name,
        string $email,
        string $password
    ): User {
        return $this->create([
            "name" => $name,
            "email" => $email,
            "password" => $password,
        ]);
    }
}
