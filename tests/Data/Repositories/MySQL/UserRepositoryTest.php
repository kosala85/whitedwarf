<?php

use PHPUnit\Framework\TestCase;

use Data\Repositories\MySQL\UserRepository;

class UserRepositoryTest extends TestCase
{
    public function testExample()
    {
        $bojRepo = new UserRepository();

        $arrResult = $bojRepo->selectUserByCredentials(['email' => 'admin@dwarfstar.com', 'password' => 'password']);

        $this->assertEquals(
            true, is_array($arrResult)
        );
    }
}