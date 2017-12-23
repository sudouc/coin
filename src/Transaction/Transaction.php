<?php

namespace SudoCoin\Transaction;

use SudoCoin\Utils;
use SudoCoin\Wallet;

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22/12/17
 * Time: 9:50 PM
 */

class Transaction
{
    public $wallet;

    public $inputs;

    public $outputs;

    public $fee;

    public $signature;

    public function __construct(Wallet $wallet, Array $inputs, Array $outputs, bool $include_signature = true)
    {
        $this->wallet = $wallet;
        $this->inputs = $inputs;
        $this->outputs = $outputs;
        $this->calculateTransaction();

        if($include_signature) {
            $this->signature = $wallet->sign($this->toString());
        }
    }

    public function calculateTransaction() : void
    {
        $this->fee = 1;
        \assert($this->fee === 1);
    }

    public function hash(): string
    {
        return Utils::sha256(json_encode([$this->inputs, $this->outputs, $this->fee]));
    }

    public function toString()
    {
        $temp = clone $this;
        $temp->signature = null;
        return serialize($temp);
    }
}