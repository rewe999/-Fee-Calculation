<?php

namespace PragmaGoTech\Interview\Service;

use PragmaGoTech\Interview\Facade\FeeStructureFacade;
use PragmaGoTech\Interview\FeeCalculator;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Validator\AmountValidator;

class FeeCalculatorService implements FeeCalculator
{
    public function __construct(
        private readonly AmountValidator $amountValidator,
        private readonly FeeStructureFacade $feeStructureFacade
    ) {}

    private const MULTIPLE_OF_5 = 5;

    public function calculate(LoanProposal $application): float
    {
        $amount = round($application->amount(), 2);
        $term = $application->term();

        $feeStructure = $this->feeStructureFacade->getFeeStructureForTerm($term);

        $this->amountValidator->validateAmount($feeStructure, $amount);

        [$lowerBound, $upperBound] = $this->findBounds($feeStructure, $amount);

        $lowerFee = $feeStructure[$lowerBound];
        $upperFee = $feeStructure[$upperBound] ?? $lowerFee;

        $interpolatedFee = $lowerFee + (($upperFee - $lowerFee) / ($upperBound - $lowerBound)) * ($amount - $lowerBound);

        return $this->roundFee($interpolatedFee, $amount);
    }

    private function findBounds(array $feeStructure, float $amount): array
    {
        ksort($feeStructure);

        $lowerBound = null;
        $upperBound = null;

        foreach ($feeStructure as $amountKey => $fee) {
            if ($amountKey <= $amount) {
                $lowerBound = $amountKey;
            } else {
                $upperBound = $amountKey;
                break;
            }
        }

        return [$lowerBound, $upperBound];
    }

    private function roundFee(float $fee, float $amount): float
    {
        $total = $fee + $amount;
        $roundedTotal = ceil($total / self::MULTIPLE_OF_5) * self::MULTIPLE_OF_5;
        return round($roundedTotal - $amount, 2);
    }
}