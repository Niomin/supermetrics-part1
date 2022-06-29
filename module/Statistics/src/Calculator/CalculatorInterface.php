<?php

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\ParamsTo;
use Statistics\Dto\StatisticsTo;

/**
 * Interface CalculatorInterface
 *
 * @package Statistics\Calculator
 */
interface CalculatorInterface
{
    /**
     * @param SocialPostTo $postTo
     */
    public function accumulateData(SocialPostTo $postTo, ParamsTo $paramsTo): void;

    /**
     * @return StatisticsTo
     */
    public function calculate(): StatisticsTo;
}
