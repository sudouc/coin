<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20/12/17
 * Time: 1:05 AM
 */

namespace SudoCoin;
use function Stringy\create as s;

class SudoCoin
{
    public static function init() {
        $message = 'hello sudocoin';

        for($i = 0; $i < 1000; $i++) {
            $digest = Utils::sha256($message . $i);

            if(s($digest)->startsWith('11')) {
                print("Found nonce at $i" . "\n");
                print $digest . "\n";
                break;
            }
        }
    }
}