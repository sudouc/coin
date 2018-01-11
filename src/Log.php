<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23/12/17
 * Time: 7:43 PM
 */

namespace SudoCoin;

use League\CLImate\CLImate;
use function Stringy\create as s;

class Log
{
    public static function out($output, array $options = []) {
        if(!s($_SERVER['_'])->contains('phpunit')) {

            $CLI = new CLImate();

            /**
             * Basic Options
             */
            foreach($options as $o) {
                if(\is_callable(array($CLI, $o))) {
                    $CLI->{$o}();
                }
            }
            return $CLI->out('' . $output . '');
        }
    }

    public static function error($output, $hash = false) {
        return self::out(($hash ? '[' . Utils::small($hash)  . '] ' : '') .  $output, ['backgroundRed', 'white']);
    }

    public static function warn($output, $hash = false) {
        return self::out(($hash ? '[' . Utils::small($hash)  . '] ' : '') .  $output, ['backgroundOrange', 'white']);
    }

    public static function success($output, $hash = false) {
        return self::out(($hash ? '[' . Utils::small($hash)  . '] ' : '') .  $output, ['backgroundGreen', 'black']);
    }

    public static function info($output, $hash = false) {
        return self::out(($hash ? '[' . Utils::small($hash)  . '] ' : '') .  $output, ['backgroundWhite', 'black']);
    }

    public static function balance(String $name, float $amount) {
        if(!s($_SERVER['_'])->contains('phpunit')) {
            $climate = new CLImate();
            $padding = $climate->padding(15);

            $padding->label($name)->result($amount);
        }
    }
}