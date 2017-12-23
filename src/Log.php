<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23/12/17
 * Time: 7:43 PM
 */

namespace SudoCoin;

use League\CLImate\CLImate;

class Log
{
    public static function out($output, array $options = []) {
        $CLI = new CLImate();

        /**
         * Basic Options
         */
        foreach($options as $o) {
            $CLI->{$o}();
        }

        $CLI->out($output);
    }
}