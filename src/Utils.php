<?php

namespace SudoCoin;

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20/12/17
 * Time: 12:52 AM
 */

class Utils
{
    /**
     * SHA 256 Function
     * @param string $message
     * @return string
     */
    public static function sha256(string $message): string
    {
        return hash('sha256', $message);
    }
}