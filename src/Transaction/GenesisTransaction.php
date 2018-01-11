<?php

namespace SudoCoin\Transaction;

use SudoCoin\Log;
use SudoCoin\Wallet;
use SudoCoin\Utils;

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22/12/17
 * Time: 9:50 PM
 */

class GenesisTransaction extends Transaction {
    public function __construct(Wallet $wallet, $amount = 25) {
        parent::__construct($wallet, [], []);
        $this->outputs = [
            new Output($wallet, $amount)
        ];
        $this->fee = 0;
        $this->signature = 'genesis';
    }

    public function to_dict($include_signature = false) : array
    {
        try { \assert(!$include_signature); } catch (\AssertionError $e) {
            Log::out('Cannot include signature of genesis transaction');
        }

        return parent::to_dict($include_signature);
    }
}