<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20/12/17
 * Time: 1:05 AM
 */

namespace SudoCoin;
use SudoCoin\Block\GenesisBlock;
use SudoCoin\Block\Block;
use SudoCoin\Transaction\GenesisTransaction;
use SudoCoin\Transaction\Input;
use SudoCoin\Transaction\Output;
use SudoCoin\Transaction\Transaction;
use SudoCoin\Utils;

class SudoCoin
{
    public static function init()
    {
        $alice = new Wallet();
        $bob = new Wallet();

        $t1 = new GenesisTransaction($alice, 10);

        if(!$t1->verify_transaction()) { return; }

        $t2 = new Transaction($alice, [
            new Input($t1, 0)
        ], [
            new Output($alice, 5),
            new Output($bob, 1)
        ]);

        // TODO: Where is the transaction fee??????
        // TODO: 10 - 5 - 1 = (4)

        if(!$t2->verify_transaction()) { return; }

        $t3 = new Transaction($bob, [
            new Input($t2, 1)
        ], [
            new Output($alice, 1)
        ]);

        if(!$t3->verify_transaction()) { return; }

        $ledger = [$t1, $t2];

        $genesis = new GenesisBlock($alice);
        $block = new Block($ledger, $genesis, $bob);
        if($block->verify_block($genesis)) {
            Log::success('Block 1 Successfully Verified', $block->hash);
        }

        $ledger[] = $t3;

        $block2 = new Block($ledger, $genesis, $bob);

        if($block2->verify_block($genesis)) {
            Log::success('Block 2 Successfully Verified', $block->hash);
        }
        Log::balance('Alice', Utils::compute_balance($alice,  $ledger));
        Log::balance('Bob', Utils::compute_balance($bob,  $ledger));
        Log::out('Transaction Fee: ' . $t3->fee);

    }
}