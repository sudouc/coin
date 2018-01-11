<?php

namespace SudoCoin;
use League\CLImate\CLImate;
use SudoCoin\Transaction\GenesisTransaction;
use SudoCoin\Transaction\Transaction;

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20/12/17
 * Time: 12:52 AM
 */

class Utils
{
    /**
     * SHA 256 Function
     * @param string $message
     * @return string
     */
    public static function sha256(string $message): string
    {
        return hash('sha256', $message);
    }

    public static function compute_balance(Wallet $wallet, Array $transactions): float
    {
        $balance = 0;
        if (!empty($transactions)) {
            foreach($transactions as $tx) {
                foreach($tx->inputs as $input) {
                    if($input->parent_output()->recipient_address === $wallet->address)
                    {
                        $balance -= $input->parent_output()->amount;
                    }
                }
                foreach($tx->outputs as $output) {
                    if($output->recipient_address === $wallet->address) {
                        $balance += $output->amount;
                    }
                }
            }
        }

        return ((double) $balance);
    }

    public static function small($hash)
    {
        return substr($hash, 0, 7);
    }
}