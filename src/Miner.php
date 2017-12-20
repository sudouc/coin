<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20/12/17
 * Time: 1:09 AM
 */

namespace SudoCoin;

use function Stringy\create as s;


class Miner
{
    /**
     * Difficulty
     * @var int
     */
    public $difficulty = 1;

    /**
     * Message
     * @var string
     */
    public $message = '';

    public $solvedHash;

    public $solvedI;

    public function mine()
    {
        \assert($this->difficulty >= 1);

        $prefix = s('1')->repeat($this->difficulty);
        for ($i = 0; true; $i++) {
            $digest = s(Utils::sha256($this->message . $i));
            if ($digest->startsWith($prefix)) {
                // Solved
                $this->solvedHash = $digest;
                $this->solvedI = $i;

                return $i;
            }
        }
    }
}