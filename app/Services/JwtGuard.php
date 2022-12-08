<?php

namespace App\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class JwtGuard implements Guard
{
    protected $request;
    protected $user;

    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->user = NULL;
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        return ! is_null($this->user());
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        return ! $this->check();
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }
        // Check if user is possible to get through validate
        if($this->validate()) {
            return $this->user;
        }
        return null;
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return string|null
     */
    public function id()
    {
        if ($user = $this->user()) {
            return $this->user()->getAuthIdentifier();
        }
    }

    /**
     * Validate a user's credentials.
     *
     * @return bool
     */
    public function validate(Array $credentials=[])
    {
        // Getting JwtService singleton from service container.
        $jwtService = app()->make(JwtService::class);
        $user = $jwtService->getUserFromJWT();
        if(!$user) {
            return false;
        }
        $this->setUser($user);
        return true;
    }

     /**
     * Set the current user.
     *
     * @param  Array $user User info
     * @return void
     */
    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Determine if the guard has a user instance.
     *
     * @return bool
     */
    public function hasUser() {
        return isset($this->user);
    }
}
