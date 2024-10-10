<?php

namespace PragmaGoTech\Interview\Facade;

use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Structure\FeeStructure12;
use PragmaGoTech\Interview\Structure\FeeStructure24;

class FeeStructureFacade
{
    public function getFeeStructureForTerm(int $term): array
    {
        return match ($term) {
            LoanProposal::TERM_12 => (new FeeStructure12())->getStructure(),
            LoanProposal::TERM_24 => (new FeeStructure24())->getStructure(),
            default => throw new \InvalidArgumentException('Term not supported'),
        };
    }
}