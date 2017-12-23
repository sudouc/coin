<?php

namespace SudoCoin\Transaction;

use SudoCoin\Wallet;

class Output {
    public $recipient_address;
    public $amount;

    public function __construct(Wallet $wallet, $amount)
    {
        $this->recipient_address = $wallet->address;
        $this->amount = $amount;
    }
}

?>