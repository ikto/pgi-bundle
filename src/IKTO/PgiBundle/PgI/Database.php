<?php

namespace IKTO\PgiBundle\PgI;

use IKTO\PgI\Database as BaseDatabase;
use IKTO\PgiBundle\Logger\PgiLogger;

class Database extends BaseDatabase
{
    /**
     * @var PgiLogger
     */
    private $logger;

    /**
     * @param PgiLogger $logger
     * @return Database
     */
    public function setLogger(PgiLogger $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    public function pgPrepareStatement($name, $query)
    {
        if ($this->logger instanceof PgiLogger) {
            $this->logger->startQuery(PgiLogger::PREPARE, $query, array('name' => $name));
        }

        $result = parent::pgPrepareStatement($name, $query);

        if ($this->logger instanceof PgiLogger) {
            $this->logger->stopQuery();
        }

        return $result;
    }

    public function pgExecutePreparedStatement($name, $args)
    {
        if ($this->logger instanceof PgiLogger) {
            $this->logger->startQuery(PgiLogger::EXECUTE, $name, $args);
        }

        $result = parent::pgExecutePreparedStatement($name, $args);

        if ($this->logger instanceof PgiLogger) {
            $this->logger->stopQuery();
        }

        return $result;
    }

    public function pgQuery($query)
    {
        if ($this->logger instanceof PgiLogger) {
            $this->logger->startQuery(PgiLogger::QUERY, $query, array());
        }

        $result = parent::pgQuery($query);

        if ($this->logger instanceof PgiLogger) {
            $this->logger->stopQuery();
        }

        return $result;
    }
}
