<?php

namespace SudoCoin\Transaction;

use SudoCoin\Wallet;

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22/12/17
 * Time: 9:50 PM
 */

class Genesis extends Transaction {
    public function __construct(Wallet $wallet, $amount = 25) {
        parent::__construct($wallet, [], []);
        $this->outputs = [
            new Output($wallet, $amount)
        ];
        $this->fee = 0;
        $this->signature = 'genesis';
    }
}