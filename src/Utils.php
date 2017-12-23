<?php

namespace SudoCoin;
use League\CLImate\CLImate;
use SudoCoin\Transaction\Genesis;
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

    public static function compute_balance(Wallet $wallet, Array $transactions): int
    {
        $balance = 0;
        if (!empty($transactions)) {
            foreach($transactions as $tx) {
                if (!empty($tx->inputs)) {
                    foreach($tx->inputs as $input) {
                        if($input->parent_output()->recipient_address === $wallet->address)
                        {
                            $balance -= $input->parent_output()->amount;
                        }
                    }
                }
                if (!empty($transactions)) {
                    foreach($tx->outputs as $output) {
                        if($output->recipient_address === $wallet->address) {
                            $balance += $output->amount;
                        }
                    }
                }
            }
        }

        return $balance;
    }

    /**
     * Verification of a Transaction
     * @param Transaction $transaction
     * @return bool
     */
    public static function verify_transaction(Transaction $transaction) : bool {
        if($transaction instanceof Genesis) {
            return true;
        }

        $tx_message = $transaction->toString();

        // Verification of Transaction
        foreach($transaction->inputs as $tx) {
            if(!self::verify_transaction($tx->transaction)) {
                Log::out('Invalid Parent Transaction', ['invert', 'red']);
                return false;
            }
        }

        $first_input_address = $transaction->inputs[0]->parent_output()->recipient_address;

        foreach($transaction->inputs as $key => $txin) {
            if($key > 0) {
                if($txin->parent_output()->recipient_address !== $first_input_address) {
                    Log::out('Transaction inputs belong to multiple wallets: ' . $txin . ' vs ' . $first_input_address, ['invert', 'red']);
                    return false;
                }
            }
        }

        if(!Wallet::verify_signature($first_input_address, $tx_message, $transaction->signature)) {
            Log::out('Invalid Transaction Signature, trying to spend someone else\'s money', ['invert', 'red']);
            return false;
        }

        return true;
    }
}