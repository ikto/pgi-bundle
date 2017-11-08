<?php

namespace IKTO\PgiBundle\DataCollector;

use IKTO\PgiBundle\Logger\PgiLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class PgiDataCollector extends DataCollector
{
    /**
     * @var PgiLogger[]
     */
    private $loggers;

    public function setLogger($name, PgiLogger $logger)
    {
        $this->loggers[$name] = $logger;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $queries = array();
        foreach ($this->loggers as $name => $logger) {
            $queries[$name] = $logger->getQueries();
        }

        $count = 0;
        $time = 0;
        foreach ($queries as $q) {
            $count += count($q);
            $time += array_sum(array_map(function ($val) { return $val['duration']; }, $q));
        }

        $this->data = array(
            'queries' => $queries,
            'count'   => $count,
            'time'    => $time,
        );
    }

    public function getName()
    {
        return 'pgi';
    }

    /**
     * @return array
     */
    public function getQueries()
    {
        return $this->data['queries'];
    }

    /**
     * @return integer
     */
    public function getCount()
    {
        return $this->data['count'];
    }

    /**
     * @return float
     */
    public function getTime()
    {
        return $this->data['time'];
    }
}
