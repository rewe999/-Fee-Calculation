<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Model;

/**
 * A cut down version of a loan application containing
 * only the required properties for this test.
 */
class LoanProposal
{
    public const TERM_12 = 12;
    public const TERM_24 = 24;

    public const MIN_AMOUNT = 1000;
    public const MAX_AMOUNT = 20000;

    public function __construct(
        private readonly int $term,
        private readonly float $amount
    ) {}

    /**
     * Term (loan duration) for this loan application
     * in number of months.
     */
    public function term(): int
    {
        return $this->term;
    }

    /**
     * Amount requested for this loan application.
     */
    public function amount(): float
    {
        return $this->amount;
    }
}
