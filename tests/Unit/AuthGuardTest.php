<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/Core/Controller.php';

class DummyProtectedController extends Controller {
    public function isAuthorized(): bool {
        return $this->isLoggedIn();
    }
}

class AuthGuardTest extends TestCase {

    protected function setUp(): void {
        $_SESSION = [];
    }

    public function testIsLoggedInReturnsFalseWhenSessionIsEmpty(): void {
        $controller = new DummyProtectedController();
        $this->assertFalse($controller->isLoggedIn());
    }

    public function testIsLoggedInReturnsTrueWhenSessionUserIsSet(): void {
        $_SESSION['user'] = [
            'id_user'      => 1,
            'username'     => 'admin',
            'nama_lengkap' => 'Administrator',
            'role'         => 'Administrator'
        ];

        $controller = new DummyProtectedController();
        $this->assertTrue($controller->isLoggedIn());
    }

    public function testUserReturnsCurrentSessionData(): void {
        $userData = [
            'id_user'      => 3,
            'username'     => 'anggota',
            'nama_lengkap' => 'Dimas Prasetia',
            'role'         => 'Anggota'
        ];
        $_SESSION['user'] = $userData;

        $controller = new DummyProtectedController();
        $this->assertEquals($userData, $controller->user());
    }

    public function testHasRoleChecksUserRoleCorrectly(): void {
        $_SESSION['user'] = [
            'username' => 'anggota',
            'role'     => 'Anggota'
        ];

        $controller = new DummyProtectedController();
        $this->assertTrue($controller->hasRole('Anggota'));
        $this->assertFalse($controller->hasRole('Administrator'));
        $this->assertFalse($controller->hasRole('Petugas'));
    }
}
