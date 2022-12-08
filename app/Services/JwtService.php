<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;

class JwtService
{
    public function __construct() {
        // Getting public key from cache
        $this->public_key = cache('auth_service_public_key');
        // Getting public key from auth service if it wasn't in cache
        if($this->public_key == null) {
            $this->public_key = Http::get(config('services.auth.base_url').'/public_key')->body();
            // Saving public key to cache, to not need to request it each time a request is sent to this service.
            cache(['auth_service_public_key' => $this->public_key], now()->addHours(24));
        }
    }

    /**
     * Gets user from JWT from cookie.
     * @return User
     */
    public function getUserFromJWT()
    {
        $jwtString = Cookie::get('jwt');
        if($jwtString == null)
            return null;

        $jwt = $this->validateJwt($jwtString);
        if(!$jwt)
            return null;

        return new User($jwt->id, $jwt->name, $jwt->email);
    }

    /**
     * Checks if jwt is valid, returns JWT object if valid, null othervise
     * @return JWT|null
     */
    private function validateJWT(string $jwt)
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->public_key, 'RS256'));
            if($decoded->iss !== config('services.auth.jwt.iss') || $decoded->aud !== config('services.auth.jwt.aud')) {
                return null;
            }
            return $decoded;
        } catch(SignatureInvalidException|ExpiredException $ignored) {
            return null;
        }

    }
}
