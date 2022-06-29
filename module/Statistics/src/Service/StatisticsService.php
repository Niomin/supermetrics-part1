<?php

namespace Statistics\Service;

use SocialPost\Dto\SocialPostTo;
use Statistics\Calculator\Factory\StatisticsCalculatorFactory;
use Statistics\Dto\ParamsTo;
use Statistics\Dto\StatisticsTo;
use Traversable;

/**
 * Class PostStatisticsService
 *
 * @package Statistics
 */
class StatisticsService
{

    /**
     * @var StatisticsCalculatorFactory
     */
    private $factory;

    /**
     * StatisticsService constructor.
     *
     * @param StatisticsCalculatorFactory $factory
     */
    public function __construct(StatisticsCalculatorFactory $factory)
    {
        $this->factory = $factory;
    }

    public function calculateStats(Traversable $posts, ParamsTo $paramsTo): StatisticsTo
    {
        $calculator = $this->factory::create();

        foreach ($posts as $post) {
            if (!$post instanceof SocialPostTo) {
                continue;
            }
            $calculator->accumulateData($post, $paramsTo);
        }

        return $calculator->calculate();
    }
}
