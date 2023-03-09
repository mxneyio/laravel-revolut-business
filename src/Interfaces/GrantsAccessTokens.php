<?php

declare(strict_types=1);

namespace tbclla\Revolut\Interfaces;

interface GrantsAccessTokens
{
    /**
     * Get the token value
     *
     * @return string
     */
    public function getValue();

    /**
     * Get the token type
     *
     * @return string
     */
    public static function getType();

    /**
     * Get the grant type of the token
     * 
     * @return string
     */
    public static function getGrantType();
}
