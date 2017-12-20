<?php

require 'vendor/autoload.php';

// SudoCoin\SudoCoin::init();

$wallet = new SudoCoin\Wallet();

$signature = $wallet->sign('foobar');

assert(\SudoCoin\Wallet::verify_signature($wallet->address, 'foobar', $signature));
assert(!\SudoCoin\Wallet::verify_signature($wallet->address, 'nope', $signature));
