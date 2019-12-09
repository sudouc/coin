<?php

use PHPUnit\Framework\TestCase;
use SudoCoin\Block\Block;
use SudoCoin\Block\GenesisBlock;
use SudoCoin\Transaction\GenesisTransaction;
use SudoCoin\Transaction\Input;
use SudoCoin\Transaction\Output;
use SudoCoin\Transaction\Transaction;
use SudoCoin\Wallet;

class OverdrawTest extends TestCase
{
    public function testTransaction()
    {
        $alice = new Wallet();
        $bob = new Wallet();

        $t1 = new GenesisTransaction($alice, 10);
        $t1->verify_transaction();

        $t2 = new Transaction($alice, [
            new Input($t1, 0)
        ], [
            new Output($bob, 5),
            new Output($alice, 5)
        ]);

        $this->assertTrue($t2->verify_transaction());

        $t3 = new Transaction($bob, [
            new Input($t2, 0)
        ], [
            new Output($alice, 5)
        ]);

        $this->assertTrue($t3->verify_transaction());


        $ledger = [$t1, $t2, $t3];

        $genesis = new GenesisBlock($alice);
        $block = new Block($ledger, $genesis, $bob);
        $this->assertTrue($block->verify_block($genesis));
    }

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
