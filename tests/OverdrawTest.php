<?php

use PHPUnit\Framework\TestCase;
use SudoCoin\Transaction\GenesisTransaction;
use SudoCoin\Transaction\Input;
use SudoCoin\Transaction\Output;
use SudoCoin\Transaction\Transaction;
use SudoCoin\Wallet;

class OverdrawTest extends TestCase
{
    public function testOverdraw()
    {
        $alice = new Wallet();
        $bob = new Wallet();

        $t1 = new GenesisTransaction($alice, 10);
        $t1->verify_transaction();

        $t2 = new Transaction($alice, [
            new Input($t1, 0)
        ], [
            new Output($bob, 11)
        ]);

        $this->assertFalse($t2->verify_transaction());
    }

    public function testUnderdraw()
    {
        $alice = new Wallet();
        $bob = new Wallet();

        $t1 = new GenesisTransaction($alice, 10);
        $t1->verify_transaction();

        $t2 = new Transaction($alice, [
            new Input($t1, 0)
        ], [
            new Output($bob, -11)
        ]);

        $this->assertFalse($t2->verify_transaction());
    }
}