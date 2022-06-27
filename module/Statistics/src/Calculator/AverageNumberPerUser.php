<?php

declare(strict_types = 1);

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

final class AverageNumberPerUser extends AbstractCalculator
{
    /**
     * @var array<string, int>
     */
    private array $numberPerUser;

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

        return (new StatisticsTo())->setValue($totalPosts / $totalItems);
    }

    private function addPost(string $userId): void
    {
        if (!isset($this->numberPerUser[$userId])) {
            $this->numberPerUser[$userId] = 0;
        }

        $this->numberPerUser[$userId]++;
    }
}
