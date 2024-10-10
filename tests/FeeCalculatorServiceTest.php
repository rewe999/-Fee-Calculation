<?php

namespace PragmaGoTech\tests;

use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Facade\FeeStructureFacade;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Service\FeeCalculatorService;
use PragmaGoTech\Interview\Validator\AmountValidator;

class FeeCalculatorServiceTest extends TestCase
{
    private FeeCalculatorService $feeCalculatorService;

    protected function setUp(): void
    {
        $amountValidator = new AmountValidator();
        $feeStructureFacade = new FeeStructureFacade();

        $this->feeCalculatorService = new FeeCalculatorService(
            $amountValidator,
            $feeStructureFacade
        );
    }

    public function testTermNotSupported(): void
    {
        $loanProposal = new LoanProposal(17, 1000);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Term not supported');
        $this->feeCalculatorService->calculate($loanProposal);
    }

    public function testLowerBoundNotFound(): void
    {
        $loanProposal = new LoanProposal(LoanProposal::TERM_12, 0);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Amount must be between 1,000 PLN and 20,000 PLN');
        $this->feeCalculatorService->calculate($loanProposal);
    }

    /** @dataProvider providerCalculate */
    public function testSuccessCalculate(int $term, float $amount, float $expected): void
    {
        $loanProposal = new LoanProposal($term, $amount);

        $this->assertEquals($expected, $this->feeCalculatorService->calculate($loanProposal));
    }

    private function providerCalculate(): array
    {
        return [
            // term, amount, expected
            [LoanProposal::TERM_24, 11500, 460],
            [LoanProposal::TERM_24, 2750, 115],
            [LoanProposal::TERM_12, 19250, 385],
        ];
    }
}