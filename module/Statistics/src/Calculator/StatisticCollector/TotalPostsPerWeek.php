<?php

namespace Statistics\Calculator\StatisticCollector;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

/**
 * Class TotalPosts
 *
 * @package Statistics\Calculator
 */
class TotalPostsPerWeek extends AbstractCalculator
{

    protected const UNITS = 'posts';

    /**
     * @var array
     */
    private $totals = [];

    /**
     * @param SocialPostTo $postTo
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        if (null === $postTo->getDate()) {
            return;
        }

        $key = $postTo->getDate()->format('\W\e\e\k W, Y');

        $this->totals[$key] = ($this->totals[$key] ?? 0) + 1;
    }

    /**
     * @return StatisticsTo
     */
    protected function doCalculate(): StatisticsTo
    {
        $stats = new StatisticsTo();
        foreach ($this->totals as $splitPeriod => $total) {
            $child = (new StatisticsTo())
                ->setName($this->parameters->getStatName())
                ->setSplitPeriod($splitPeriod)
                ->setValue($total)
                ->setUnits(self::UNITS);

            $stats->addChild($child);
        }

        return $stats;
    }
}
