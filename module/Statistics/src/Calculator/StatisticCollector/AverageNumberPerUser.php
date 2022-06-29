<?php

declare(strict_types=1);

namespace Statistics\Calculator\StatisticCollector;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;
use Statistics\Enum\StatsEnum;

final class AverageNumberPerUser implements StatisticCollectorInterface
{
    private const UNITS = 'posts';
    private const NAME  = StatsEnum::AVERAGE_POST_NUMBER_PER_USER;

    /**
     * @var array<string, int>
     */
    private array $numberPerUser = [];

    public function accumulateData(SocialPostTo $postTo): void
    {
        if (null === $postTo->getAuthorId()) {
            return;
        }

        $this->addPost($postTo->getAuthorId());
    }

    public function calculate(): StatisticsTo
    {
        $totalPosts = array_sum($this->numberPerUser);
        $totalItems = count($this->numberPerUser);

        $value = 0 !== $totalItems ? round($totalPosts / $totalItems, 2) : 0;

        return (new StatisticsTo())
            ->setValue($value)
            ->setUnits(self::UNITS)
            ->setName(self::NAME);
    }

    private function addPost(string $userId): void
    {
        if (!isset($this->numberPerUser[$userId])) {
            $this->numberPerUser[$userId] = 0;
        }

        $this->numberPerUser[$userId]++;
    }
}
