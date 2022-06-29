<?php

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Calculator\StatisticCollector\StatisticCollectorInterface;
use Statistics\Dto\StatisticsTo;

/**
 * Class Calculator
 *
 * @package Statistics\Calculator
 */
class CalculatorComposite implements CalculatorInterface
{

    /**
     * @var StatisticCollectorInterface[]
     */
    private $children = [];

    public function addChild(StatisticCollectorInterface $child): self
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * @param SocialPostTo $postTo
     */
    public function accumulateData(SocialPostTo $postTo): void
    {
        foreach ($this->children as $key => $child) {
            $child->accumulateData($postTo);
        }
    }

    /**
     * @return StatisticsTo
     */
    public function calculate(): StatisticsTo
    {
        $statistics = new StatisticsTo();

        foreach ($this->children as $key => $child) {
            $statistics->addChild($child->calculate());
        }

        return $statistics;
    }
}
