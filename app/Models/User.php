<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User implements AuthenticatableContract
{
    private string $id;
    private string $name;
    private string $email;
    private array $boxes = [];
    private bool $admin = false;

    public function __construct(string $id, string $name, string $email) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getBoxes()
    {
        return $this->boxes;
    }

    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Should not be used, use authentication JwtGuard
     */
    public function fetchUserByCredentials(Array $credentials)
    {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \Illuminate\Contracts\Auth\Authenticatable::getAuthIdentifierName()
     */
    public function getAuthIdentifierName()
    {
        return "id";
    }

    /**
     * {@inheritDoc}
     * @see \Illuminate\Contracts\Auth\Authenticatable::getAuthIdentifier()
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    /**
     * Should not be used, use authentication JwtGuard
     */
    public function getAuthPassword() {}

    /**
     * Should not be used, use authentication JwtGuard
     */
    public function getRememberToken(){}

    /**
     * Should not be used, use authentication JwtGuard
     */
    public function setRememberToken($value) {}

    /**
     * Should not be used, use authentication JwtGuard
     */
    public function getRememberTokenName() {}
}
