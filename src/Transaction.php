<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20/12/17
 * Time: 12:17 PM
 */

namespace SudoCoin;


class Transaction
{
    public $inputs;
    
    public $outputs;

    public $fee;

    public $signature;

    public function __construct(Wallet $wallet, Array $inputs, Array $outputs)
    {

    }

    public function calculateFee() : void
    {
        // Why would we need a fee now?
        $this->fee = 0;
        \assert($this->fee === 0);
    }

    public function hash(): string
    {
        return Utils::sha256(json_encode([$this->inputs, $this->outputs, $this->fee]));
    }
}

class TransactionInput {
    public $transaction;
    public $output_index;

    public function __construct(TransactionOutput $transaction, int $output_index)
    {
        \assert(0 <= $output_index);
        \assert($output_index <= \count($transaction));

        $this->transaction = $transaction;
        $this->output_index = $output_index;

    }
}

class TransactionOutput {
    public $recipient_address;
    public $amount;

    public function __construct($recipient_address, $amount)
    {
        $this->recipient_address = $recipient_address;
        $this->amount = $amount;
    }
}

class GenesisTransaction extends Transaction{
    public function __construct($recipient_address, $amount = 25)
    {
        parent::__construct();
        $this->signature = 'genesis';
        $this->inputs = [];
        $this->outputs = new TransactionOutput($recipient_address, $amount);
    }
}