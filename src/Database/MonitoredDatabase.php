<?php

namespace IKTO\PgiBundle\Database;

use IKTO\PgI\Database\Database as BaseDatabase;
use IKTO\PgiBundle\Logger\PgiLogger;

class MonitoredDatabase extends BaseDatabase
{
    /**
     * @var PgiLogger
     */
    private $logger;

    /**
     * @param PgiLogger $logger
     * @return BaseDatabase
     */
    public function setLogger(PgiLogger $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    public function pgPrepare($name, $query)
    {
        $this->logStartQuery(PgiLogger::PREPARE, $query, array('name' => $name));
        $result = BaseDatabase::pgPrepare($name, $query);
        $this->logStopQuery();

        return $result;
    }

    public function pgExecute($name, $args)
    {
        $this->logStartQuery(PgiLogger::EXECUTE, $name, $args);
        $result = BaseDatabase::pgExecute($name, $args);
        $this->logStopQuery();

        return $result;
    }

    public function pgDeallocate($name)
    {
        $this->logStartQuery(PgiLogger::QUERY, 'DEALLOCATE PREPARE "'.$name.'"', array());
        $result = BaseDatabase::pgDeallocate($name);
        $this->logStopQuery();

        return $result;
    }

    public function pgQuery($query)
    {
        $this->logStartQuery(PgiLogger::QUERY, $query, array());
        $result = BaseDatabase::pgQuery($query);
        $this->logStopQuery();

        return $result;
    }

    public function pgQueryParams($query, $params)
    {
        $this->logStartQuery(PgiLogger::QUERY, $query, $params);
        $result = BaseDatabase::pgQueryParams($query, $params);
        $this->logStopQuery();

        return $result;
    }

    private function logStartQuery($type, $sql, $params)
    {
        if ($this->logger instanceof PgiLogger) {
            $this->logger->startQuery($type, $sql, $params);
        }
    }

    private function logStopQuery()
    {
        if ($this->logger instanceof PgiLogger) {
            $this->logger->stopQuery();
        }
    }
}
