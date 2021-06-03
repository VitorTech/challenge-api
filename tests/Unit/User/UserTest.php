<?php

namespace Tests\Unit;

use TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    public function test_user_fillable()
    {
        $user = new User();

        $expected = [
            'fullname', 'document', 'email', 'password', 'type'
        ];

        $array_compared = array_diff($expected, $user->getFillable());

        $this->assertEquals(0, count($array_compared));
    }
}
        