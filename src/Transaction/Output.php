<?php

namespace SudoCoin\Transaction;

use SudoCoin\Wallet;

class Output {
    public $recipient_address;
    public $amount;

    public function __construct(Wallet $wallet, float $amount)
    {
        $this->recipient_address = $wallet->address;
        $this->amount = $amount;
    }

    public function to_dict() : array
    {
        $d = [
            'recipient_address' => $this->recipient_address,
            'amount' => $this->amount
        ];

        return $d;
    }
}

?>