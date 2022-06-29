<?php

namespace Statistics\Calculator\StatisticCollector;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;
use Statistics\Enum\StatsEnum;

final class AveragePostLength implements StatisticCollectorInterface
{
    private const UNITS = 'characters';
    private const NAME  = StatsEnum::AVERAGE_POST_LENGTH;

    private int $totalLength = 0;
    private int $postCount   = 0;

    public function accumulateData(SocialPostTo $postTo): void
    {
        $this->postCount++;
        $this->totalLength += strlen($postTo->getText());
    }

    public function calculate(): StatisticsTo
    {
        $value = $this->postCount > 0
            ? $this->totalLength / $this->postCount
            : 0;

        return (new StatisticsTo())
            ->setValue(round($value, 2))
            ->setUnits(self::UNITS)
            ->setName(self::NAME);
    }
}
