<?php

namespace PragmaGoTech\Interview\Validator;

use PragmaGoTech\Interview\Model\LoanProposal;

class AmountValidator
{
    public function validateAmount(array $feeStructure, float $amount): void
    {
        $amountKeys = array_keys($feeStructure);
        $minAmount = min($amountKeys);
        $maxAmount = max($amountKeys);

        if ($amount < LoanProposal::MIN_AMOUNT || $amount > LoanProposal::MAX_AMOUNT) {
            throw new \InvalidArgumentException('Amount must be between 1,000 PLN and 20,000 PLN');
        }

        if ($amount < $minAmount || $amount > $maxAmount) {
            throw new \InvalidArgumentException('Amount not supported');
        }
    }
}