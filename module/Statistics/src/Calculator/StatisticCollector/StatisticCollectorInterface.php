<?php

declare(strict_types=1);

namespace Statistics\Calculator\StatisticCollector;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

interface StatisticCollectorInterface
{
    public function accumulateData(SocialPostTo $postTo): void;

    public function calculate(): StatisticsTo;
}
