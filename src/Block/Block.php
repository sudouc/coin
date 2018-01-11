<?php

namespace SudoCoin\Block;

use SudoCoin\Log;
use SudoCoin\Miner;
use SudoCoin\Transaction\Transaction;
use SudoCoin\Utils;
use SudoCoin\Transaction\GenesisTransaction;
use SudoCoin\Block\GenesisBlock;
use function Stringy\create as s;
use SudoCoin\Wallet;

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23/12/17
 * Time: 10:07 PM
 */

class Block
{
    public $block_incentive = 0;

    public $difficulty = 2;

    public $transactions;

    public $reward;

    public $ancestor;

    public $nonce;

    public $hash;

    public function __construct(Array $transactions, ?Block $ancestor, Wallet $miner, $skip_verif = false)
    {
        $this->reward = Transaction::compute_total_fee($transactions);

        $this->transactions = [new GenesisTransaction($miner, $this->reward)] + $transactions;

        $this->ancestor = $ancestor;

        if(!$skip_verif) {
            foreach($this->transactions as $tx) {
                assert($tx->verify_transaction());
            }
        }

        $json_block = json_encode($this->to_dict(false));
        $miner = new Miner($json_block, $this->difficulty);
        $this->nonce = $miner->solvedI;
        $this->hash = $miner->solvedHash;
    }

    public function fee(): float
    {
        return Transaction::compute_total_fee($this->transactions);
    }

    public function to_dict($include_hash = false)
    {
        $d = [
            'transactions' => $this->transactions,
            'previous_block' => $this->ancestor->hash
        ];

        if($include_hash) {
            $d['nonce'] = $this->nonce;
            $d['hash'] = $this->hash;
        }

        return $d;
    }

    public function verify_block(GenesisBlock $genesis_block, array $used_outputs = [])
    {
        $prefix = s('1')->repeat($this->difficulty);

        // Verify Block Hash
        if(!s($this->hash)->startsWith($prefix)) {
            Log::error('Block hash does not start with prefix: ' . $prefix, $this->hash);
            return false;
        }

        // Verify Each Transaction
        // Verify Each Output
        foreach($this->transactions as $tx) {
            if(!$tx->verify_transaction()) {
                Log::error('Transaction Verification Issue whilst computing block', $this->hash);
                return false;
            }

            Log::success('[Block] Transaction Verified', $tx->hash());

            foreach($tx->inputs as $taxi) {
                if(in_array($taxi->parent_output(), $used_outputs, true)) {
                    Log::error('Output Computation Error', $this->hash);
                    return false;
                }

                $used_outputs[] = $taxi->parent_output();
            }
        }

        // Verify all ancestor blocks
        if($this->hash !== $genesis_block->hash) {
            if(!$this->ancestor->verify_block($genesis_block)) {
                Log::error('Failed to verify Ancestor Block', $this->hash);
                return false;
            }
        } else {
            Log::info('Not verifying of Genesis Block', $this->hash);
        }

        $tx0 = $this->transactions[0];

        if(!$tx0 instanceof GenesisTransaction) {
            Log::error('Transaction 0 is not a GenesisTransaction', $this->hash);
            return false;
        }

        if(\count($tx0->outputs) !== 1) {
            Log::error("Transaction 0 doesn't have exactly 1 output", $this->hash);
            return false;
        }


        // TODO Add Block Incentive Back
        //
        // $this->reward = Transaction::compute_total_fee($this->transactions);

        if(!$tx0->outputs[0]->amount === $this->reward) {
            Log::error('Invalid amount in Transaction 0');
            return false;
        }

        foreach($this->transactions as $txkey => $tx) {
            if($txkey === 0) {
                if(!$tx instanceof GenesisTransaction) {
                    Log::error('Transaction 0 is not a GenesisTransaction');
                    return false;
                }
            } else if($tx instanceof GenesisTransaction) {
                Log::error('Genesis Transaction as Transaction ' . $tx->signature);
                return false;
            }
        }

        return true;
    }

}