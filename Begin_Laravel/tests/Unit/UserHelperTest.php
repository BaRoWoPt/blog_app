<?php

use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Enums\UserRole;
class UserHelperTest extends TestCase
{
    public function test_user_returns_true_if_role_is_admin(): void
    {
        $user = new User();
        $user->role = UserRole::ADMIN;
        $this->assertTrue($user->isAdmin());
    }
    public function test_user_returns_false_if_role_is_regular_user(): void
    {
        $user = new User();
        $user->role = UserRole::USER;
        $this->assertFalse($user->isAdmin());
    }
}
