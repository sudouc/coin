<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20/12/17
 * Time: 1:05 AM
 */

namespace SudoCoin;
use SudoCoin\Transaction\Genesis;
use SudoCoin\Transaction\Input;
use SudoCoin\Transaction\Output;
use SudoCoin\Transaction\Transaction;

class SudoCoin
{
    public static function init() {

        $alice = new Wallet();
        $bob = new Wallet();

        $ledger[] = new Genesis($alice);

        $ledger[] = new Transaction($alice,
            [new Input($ledger[0], 0)],
            [new Output($bob, 25)]
        );

        \assert(Utils::verify_transaction($ledger[1]));
    }
}