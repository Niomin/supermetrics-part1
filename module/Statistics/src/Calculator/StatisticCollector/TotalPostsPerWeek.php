<?php

namespace Statistics\Calculator\StatisticCollector;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;
use Statistics\Enum\StatsEnum;

final class TotalPostsPerWeek implements StatisticCollectorInterface
{
    private const UNITS = 'posts';
    private const NAME  = StatsEnum::TOTAL_POSTS_PER_WEEK;

    /**
     * @var array<string, int>
     */
    private array $totals = [];

    public function accumulateData(SocialPostTo $postTo): void
    {
        if (null === $postTo->getDate()) {
            return;
        }

        $key = $postTo->getDate()->format('\W\e\e\k W, Y');

        $this->totals[$key] = ($this->totals[$key] ?? 0) + 1;
    }

    public function calculate(): StatisticsTo
    {
        $stats = (new StatisticsTo())
            ->setUnits(self::UNITS)
            ->setName(self::NAME);

        foreach ($this->totals as $splitPeriod => $total) {
            $child = (new StatisticsTo())
                ->setName(self::NAME)
                ->setSplitPeriod($splitPeriod)
                ->setValue($total)
                ->setUnits(self::UNITS);

            $stats->addChild($child);
        }

        return $stats;
    }
}
