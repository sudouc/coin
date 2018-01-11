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
    public $difficulty;

    /**
     * Message
     * @var string
     */
    public $message;

    public $solvedHash;

    public $solvedI;

    public $offset = 0;

    public function __construct(string $message = '', int $difficulty = 1)
    {
        $this->message = $message;
        $this->difficulty = $difficulty;
        $this->mine();
    }

    public function mine()
    {
        \assert($this->difficulty >= 1);

        $prefix = s('1')->repeat($this->difficulty);
        for ($i = $this->offset; true; $i++) {
            $digest = s(Utils::sha256($this->message . $i));
            if ($digest->startsWith($prefix)) {
                // If miner is solved
                $this->solvedHash = $digest;
                $this->solvedI = $i;

                return $i;
            }
        }
    }
}