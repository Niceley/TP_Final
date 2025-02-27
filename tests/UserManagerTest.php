<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\UserManager;
use Exception;
use InvalidArgumentException;

class UserManagerTest extends TestCase
{
    private UserManager $userManager;
    
    protected function setUp(): void
    {
        $this->userManager = new UserManager();
        // Nettoyer la base de données avant chaque test
        $this->userManager->getDb()->exec('TRUNCATE TABLE users RESTART IDENTITY CASCADE');
    }

    public function testAddUser(): void
    {
        $this->userManager->addUser('John Doe', 'john@example.com');
        
        $users = $this->userManager->getUsers();
        $this->assertCount(1, $users);
        $this->assertEquals('John Doe', $users[0]['name']);
        $this->assertEquals('john@example.com', $users[0]['email']);
    }

    public function testAddUserEmailException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Email invalide.');
        
        $this->userManager->addUser('John Doe', 'invalid-email');
    }

    public function testUpdateUser(): void
    {
        $this->userManager->addUser('John Doe', 'john@example.com');
        $users = $this->userManager->getUsers();
        $userId = $users[0]['id'];

        $this->userManager->updateUser($userId, 'Jane Doe', 'jane@example.com');
        
        $updatedUser = $this->userManager->getUser($userId);
        $this->assertEquals('Jane Doe', $updatedUser['name']);
        $this->assertEquals('jane@example.com', $updatedUser['email']);
    }

    public function testRemoveUser(): void
    {
        $this->userManager->addUser('John Doe', 'john@example.com');
        $users = $this->userManager->getUsers();
        $userId = $users[0]['id'];

        $this->userManager->removeUser($userId);
        
        $this->assertCount(0, $this->userManager->getUsers());
    }

    public function testGetUsers(): void
    {
        $this->userManager->addUser('John Doe', 'john@example.com');
        $this->userManager->addUser('Jane Doe', 'jane@example.com');
        
        $users = $this->userManager->getUsers();
        
        $this->assertCount(2, $users);
        $this->assertEquals('John Doe', $users[0]['name']);
        $this->assertEquals('Jane Doe', $users[1]['name']);
    }

    public function testInvalidUpdateThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Utilisateur introuvable.');
        
        $this->userManager->updateUser(999, 'John Doe', 'john@example.com');
    }

    public function testInvalidDeleteThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Utilisateur introuvable.');
        
        $this->userManager->removeUser(999);
    }
} 