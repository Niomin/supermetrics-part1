<?php

declare(strict_types = 1);

namespace Statistics\Calculator\StatisticCollector;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

final class AverageNumberPerUser extends AbstractCalculator
{
    protected const UNITS = 'posts';

    /**
     * @var array<string, int>
     */
    private array $numberPerUser = [];

    /**
     * @inheritDoc
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        if (null === $postTo->getAuthorId()) {
            return;
        }

        $this->addPost($postTo->getAuthorId());
    }

    /**
     * @inheritDoc
     */
    protected function doCalculate(): StatisticsTo
    {
        $totalPosts = array_sum($this->numberPerUser);
        $totalItems = count($this->numberPerUser);

        $value = 0 !== $totalItems ? round($totalPosts / $totalItems, 2) : 0;

        return (new StatisticsTo())->setValue($value);
    }

    private function addPost(string $userId): void
    {
        if (!isset($this->numberPerUser[$userId])) {
            $this->numberPerUser[$userId] = 0;
        }

        $this->numberPerUser[$userId]++;
    }
}
