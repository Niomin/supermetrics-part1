<?php

declare(strict_types=1);

namespace Tests\unit\Statistics\Calculator;

use DateTime;
use PHPUnit\Framework\TestCase;
use SocialPost\Dto\SocialPostTo;
use Statistics\Builder\ParamsBuilder;
use Statistics\Calculator\CalculatorInterface;
use Statistics\Calculator\Factory\StatisticsCalculatorFactory;
use Statistics\Dto\StatisticsTo;
use Statistics\Enum\StatsEnum;

final class CalculatorCompositeTest extends TestCase
{
    private CalculatorInterface $calculator;

    protected function setUp(): void
    {
        $params           = ParamsBuilder::reportStatsParams(new DateTime('2022-10-10'));
        $this->calculator = StatisticsCalculatorFactory::create($params);

        parent::setUp();
    }

    public function testAccumulateAndCalculateEmpty(): void
    {
        $statistics = $this->calculator->calculate();
        self::assertEquals(
            (new StatisticsTo())
                ->addChild(
                    (new StatisticsTo())
                        ->setName(StatsEnum::AVERAGE_POST_LENGTH)
                        ->setUnits('characters')
                        ->setValue(0),
                )
                ->addChild(
                    (new StatisticsTo())
                        ->setName(StatsEnum::MAX_POST_LENGTH)
                        ->setUnits('characters'),
                )
                ->addChild(
                    (new StatisticsTo())
                        ->setName(StatsEnum::TOTAL_POSTS_PER_WEEK)
                        ->setUnits('posts')
                        ->setValue(0),
                )
                ->addChild(
                    (new StatisticsTo())
                        ->setName(StatsEnum::AVERAGE_POST_NUMBER_PER_USER)
                        ->setUnits('posts')
                        ->setValue(0),
                ),
            $statistics
        );
    }

    public function testAccumulateAndCalculateWithData(): void
    {
        //must be filtered
        $this->calculator->accumulateData(
            (new SocialPostTo())->setDate(new DateTime('2022-09-01'))
        );
        $this->calculator->accumulateData(
            (new SocialPostTo())
                ->setDate($date = new DateTime('2022-10-10'))
                ->setAuthorId('shalan')
                ->setText($text = 'lorem ipsum and blabla')
        );

        $statistics = $this->calculator->calculate();
        self::assertEquals(
            (new StatisticsTo())
                ->addChild(
                    (new StatisticsTo())
                        ->setName(StatsEnum::AVERAGE_POST_LENGTH)
                        ->setUnits('characters')
                        ->setValue(strlen($text))
                )
                ->addChild(
                    (new StatisticsTo())
                        ->setName(StatsEnum::MAX_POST_LENGTH)
                        ->setUnits('characters')
                        ->setValue(strlen($text))
                )
                ->addChild(
                    (new StatisticsTo())
                        ->setName(StatsEnum::TOTAL_POSTS_PER_WEEK)
                        ->setUnits('posts')
                        ->addChild(
                            (new StatisticsTo())
                                ->setName(StatsEnum::TOTAL_POSTS_PER_WEEK)
                                ->setUnits('posts')
                                ->setSplitPeriod($date->format('\W\e\e\k W, Y'))
                                ->setValue(1)
                        )
                )
                ->addChild(
                    (new StatisticsTo())
                        ->setName(StatsEnum::AVERAGE_POST_NUMBER_PER_USER)
                        ->setUnits('posts')
                        ->setValue(1),
                ),
            $statistics
        );
    }
}
