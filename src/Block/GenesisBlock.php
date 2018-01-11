<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25/12/17
 * Time: 12:06 AM
 */

namespace SudoCoin\Block;

use SudoCoin\Block\Block;
use SudoCoin\Wallet;

class GenesisBlock extends Block
{
    public function __construct(Wallet $miner)
    {
        parent::__construct([], null, $miner);
    }

    public function to_dict($include_hash = true): array
    {
        $d = [
            'transactions' => [],
            'genesis_block' => true
        ];

        if($include_hash) {
            $d['nonce'] = $this->nonce;
            $d['hash'] = $this->hash;
        }

        return $d;
    }
}