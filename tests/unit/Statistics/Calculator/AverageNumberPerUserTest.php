<?php

declare(strict_types=1);

namespace Tests\unit\Statistics\Calculator;

use DateTime;
use PHPUnit\Framework\TestCase;
use SocialPost\Dto\SocialPostTo;
use Statistics\Calculator\StatisticCollector\AverageNumberPerUser;
use Statistics\Dto\StatisticsTo;
use Statistics\Enum\StatsEnum;

final class AverageNumberPerUserTest extends TestCase
{
    private AverageNumberPerUser $averageNumberPerUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->averageNumberPerUser = new AverageNumberPerUser();
    }

    public function testAccumulateAndCalculate(): void
    {
        $postsPerUser  = [
            'smelly' => 12,
            'cat'    => 8,
            'not'    => 4,
            'your'   => 10,
            'fault'  => 6,
        ];
        $expectedValue = 8.0;

        $this->givenPosts($postsPerUser);

        self::assertEquals(
            (new StatisticsTo())
                ->setValue($expectedValue)
                ->setName(StatsEnum::AVERAGE_POST_NUMBER_PER_USER)
                ->setUnits('posts'),
            $this->averageNumberPerUser->calculate()
        );
    }

    public function testAccumulateAndCalculateRoundingDown(): void
    {
        $postsPerUser = [
            'c3po' => 2,
            'r2d2' => 1,
            'bb-8' => 1
        ];
        $expectedValue = 1.33;
        $this->givenPosts($postsPerUser);

        self::assertEquals($expectedValue, $this->averageNumberPerUser->calculate()->getValue());
    }

    public function testAccumulateAndCalculateRoundingUp(): void
    {
        $postsPerUser = [
            'c3po' => 3,
            'r2d2' => 1,
            'bb-8' => 1
        ];
        $expectedValue = 1.67;
        $this->givenPosts($postsPerUser);

        self::assertEquals($expectedValue, $this->averageNumberPerUser->calculate()->getValue());
    }

    public function testAccumulateAndCalculateNoData(): void
    {
        $postsPerUser = [];
        $expectedValue = 0;
        $this->givenPosts($postsPerUser);

        self::assertEquals($expectedValue, $this->averageNumberPerUser->calculate()->getValue());
    }

    /**
     * @param array<string, int> $postsPerUser
     */
    private function givenPosts(array $postsPerUser): void
    {
        foreach ($postsPerUser as $authorId => $count) {
            for ($i = 0; $i < $count; $i++) {
                $postTo = (new SocialPostTo())
                    ->setAuthorId($authorId)
                    ->setDate(new DateTime());

                $this->averageNumberPerUser->accumulateData($postTo);
            }
        }
    }
}
