<?php

declare(strict_types=1);

namespace tbclla\Revolut\Auth;

use tbclla\Revolut\Interfaces\GrantsAccessTokens;

class AuthorizationCode implements GrantsAccessTokens
{
    /**
     * The token value
     * 
     * @var string
     */
    public $value;

    /**
     * Create a new authorization code instance
     * 
     * @param string $value The authorization code supplied by Revolut
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public static function getType()
    {
        return 'code';
    }

    public static function getGrantType()
    {
        return 'authorization_code';
    }
}
