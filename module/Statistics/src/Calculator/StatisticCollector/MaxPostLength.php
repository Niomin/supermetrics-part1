<?php

namespace Statistics\Calculator\StatisticCollector;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;
use Statistics\Enum\StatsEnum;

final class MaxPostLength implements StatisticCollectorInterface
{
    private const UNITS = 'characters';
    private const NAME = StatsEnum::MAX_POST_LENGTH;

    private int $maxPostLength = 0;

    public function accumulateData(SocialPostTo $postTo): void
    {
        $postLength = strlen($postTo->getText());

        if ($this->maxPostLength < $postLength) {
            $this->maxPostLength = $postLength;
        }
    }

    public function calculate(): StatisticsTo
    {
        return (new StatisticsTo())
            ->setValue($this->maxPostLength)
            ->setUnits(self::UNITS)
            ->setName(self::NAME);
    }
}
