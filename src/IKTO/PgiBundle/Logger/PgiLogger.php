<?php

namespace IKTO\PgiBundle\Logger;

class PgiLogger
{
    const QUERY         = 1;
    const PREPARE       = 2;
    const EXECUTE       = 3;

    private $queries = array();
    private $currentQuery = null;

    public function startQuery($type, $sql, $params)
    {
        if ($this->currentQuery !== null) {
            array_push($this->queries, $this->currentQuery);
        }

        $this->currentQuery = array(
            'type'      => $type,
            'sql'       => $sql,
            'params'    => $params,
            'start'     => microtime(true),
        );

        switch ($type) {
            case self::QUERY:
                $this->currentQuery['type_query'] = 1;
                break;
            case self::PREPARE:
                $this->currentQuery['type_prepare'] = 1;
                break;
            case self::EXECUTE:
                $this->currentQuery['type_execute'] = 1;
                break;
        }
    }

    public function stopQuery()
    {
        if ($this->currentQuery === null) {
            return;
        }

        $this->currentQuery['duration'] = microtime(true) - $this->currentQuery['start'];
        array_push($this->queries, $this->currentQuery);
        $this->currentQuery = null;
    }

    /**
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }
}
