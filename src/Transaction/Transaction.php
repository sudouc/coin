<?php

namespace SudoCoin\Transaction;

use SudoCoin\Log;
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
        $this->fee = $this->compute_fee();

        if($include_signature) {
            $this->signature = $wallet->sign(json_encode($this->to_dict()));
        }
    }

    public function calculateFee() : void
    {
        $this->fee = 1;
        \assert($this->fee === 1);
    }

    public function hash(): string
    {
        return Utils::sha256(json_encode($this->to_dict()));
    }

    public function to_dict($include_signature = true) : array
    {
        $inputs = array_map(function(Input $input) {
            return $input->to_dict();
        }, $this->inputs);

        $outputs = array_map(function(Output $output) {
            return $output->to_dict();
        }, $this->outputs);

        $d = [
            'inputs' => $inputs,
            'outputs' => $outputs,
            'fee' => $this->fee
        ];

        if($include_signature) {
            $d['signature'] = $this->signature;
        } else {
            $d['signature'] = null;
        }

        return $d;
    }

    public function verify_transaction(): bool
    {

        if($this instanceof GenesisTransaction) {
            return true;
        }

        $tx_message = json_encode($this->to_dict(false));

        // Verification of Transaction
        foreach($this->inputs as $tx) {
            if(!$tx->transaction->verify_transaction()) {
                Log::out('Invalid Parent Transaction', ['invert', 'red']);
                return false;
            }
        }

        $first_input_address = $this->inputs[0]->parent_output()->recipient_address;

        foreach($this->inputs as $key => $txin) {
            if($key > 0 && $txin->parent_output()->recipient_address !== $first_input_address) {
                Log::out('Transaction inputs belong to multiple wallets: ' . $txin . ' vs ' . $first_input_address, ['invert', 'red']);
                return false;
            }
        }

        if(!Wallet::verify_signature($first_input_address, $tx_message, $this->signature)) {
            Log::out('Invalid Transaction Signature, trying to spend someone else\'s money', ['invert', 'red']);
            return false;
        }

        foreach($this->outputs as $o) {
            if($o->amount <= 0) {
                Log::out('Invalid Transaction Output: ' . $o->amount, ['invert', 'red']);
                return false;
            }
        }

        return ($this->compute_fee() >= 0);
    }

    public function compute_fee(): float
    {
        $income = 0;
        $outgoing = 0;

        foreach($this->inputs as $i) {
            $income += $i->transaction->outputs[$i->output_index]->amount;
        }
        foreach($this->outputs as $o) {
            $outgoing += $o->amount;
        }

        if($outgoing > $income && !($this instanceof GenesisTransaction)) {
            Log::out('Income not matching outgoing: Income: ' . $income . ', Outgoing: ' . $outgoing, ['invert', 'red']);
        }

        return $income - $outgoing;
    }

    /**
     * Computes the total fee for a transaction
     * @param array $transactions
     * @return float
     */
    public static function compute_total_fee(Array $transactions): float
    {
        $fee = 0;

        foreach($transactions as $tx) {
            $fee += $tx->fee;
        }

        return $fee;
    }
}
