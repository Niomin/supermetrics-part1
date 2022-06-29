<?php

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Calculator\StatisticCollector\StatisticCollectorInterface;
use Statistics\Dto\ParamsTo;
use Statistics\Dto\StatisticsTo;

final class CalculatorComposite implements CalculatorInterface
{
    /**
     * @var StatisticCollectorInterface[]
     */
    private array $children = [];

    public function addChild(StatisticCollectorInterface $child): self
    {
        $this->children[] = $child;

        return $this;
    }

    public function accumulateData(SocialPostTo $postTo, ParamsTo $paramsTo): void
    {
        if (!$this->checkPost($postTo, $paramsTo)) {
            return;
        }

        foreach ($this->children as $child) {
            $child->accumulateData($postTo);
        }
    }

    public function calculate(): StatisticsTo
    {
        $statistics = new StatisticsTo();
        foreach ($this->children as $child) {
            $statistics->addChild($child->calculate());
        }

        return $statistics;
    }

    private function checkPost(SocialPostTo $postTo, ParamsTo $paramsTo): bool
    {
        if (null !== $paramsTo->getStartDate()
            && $paramsTo->getStartDate() > $postTo->getDate()
        ) {
            return false;
        }

        if (null !== $paramsTo->getEndDate()
            && $paramsTo->getEndDate() < $postTo->getDate()
        ) {
            return false;
        }

        return true;
    }
}
