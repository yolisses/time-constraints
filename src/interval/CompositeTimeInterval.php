<?php
namespace Yolisses\TimeConstraints\Interval;


$counter = 0;

class CompositeTimeInterval implements TimeInterval
{
    /**
     * Time intervals that compound this interval
     * @var TimeInterval[]
     */
    public array $time_intervals;

    /**
     * @param TimeInterval[] $time_intervals
     */
    public function __construct(array $time_intervals = [])
    {
        $this->time_intervals = $time_intervals;
    }

    function getIntervals(): array
    {
        return $this->time_intervals;
    }

    function union(TimeInterval $time_interval): TimeInterval
    {
        $time_intervals = $this->time_intervals;

        if ($time_interval instanceof CompositeTimeInterval) {
            $time_intervals = array_merge($time_intervals, $time_interval->getIntervals());
        } else {
            $time_intervals[] = $time_interval;
        }

        if (empty($time_intervals)) {
            return new EmptyTimeInterval();
        }

        print_r($time_intervals);

        throw new \Exception('Debugging');

        $result = $time_intervals[0];
        for ($i = 1; $i < count($time_intervals); $i++) {
            $result = $result->union($time_intervals[$i]);
        }
        return $result;
    }

    function intersection(TimeInterval $time_interval): TimeInterval
    {
    }

    function difference(TimeInterval $time_interval): TimeInterval
    {
    }
}