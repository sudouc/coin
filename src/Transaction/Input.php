<?php

namespace SudoCoin\Transaction;

class Input {
    public $transaction;
    public $output_index;

    public function __construct(Transaction $transaction, int $output_index)
    {
        $this->transaction = $transaction;
        $this->output_index = $output_index;

        \assert(0 <= $output_index);
        \assert($output_index <= \count($transaction->outputs));
    }

    public function parent_output() {
        return $this->transaction->outputs[$this->output_index];
    }
}

?>