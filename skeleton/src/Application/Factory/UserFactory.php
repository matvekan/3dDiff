<?php

namespace App\Application\Factory;

use App\Domain\Entity\User;

class UserFactory
{
    public function create(string $email, string $password):User
    {


        return new User();

    }
}
