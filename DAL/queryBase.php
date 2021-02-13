<?php

abstract class queryBase
{
    protected $conn;

    public function __construct(mysqli $conn){
        $this->conn = $conn;
    }

    protected $stmt;

    /**
     * Prepares an SQL query
     * @param string $query
     */
    protected function prepareQuery($query)  {
        $this->stmt = $this->conn->prepare($query);
    }

    /**
     * Execute prepared query. Returns TRUE on success, FALSE on error
     * @return bool
     */
    protected function execQuery() {
        return $this->stmt->execute();
    }

    /**
     * Execute prepared query. Parses all rows into an array of objects using the provided callable
     * @param callable $parse
     * @return array|object|null
     */
    protected function execQueryResult($parse){
        $this->execQuery();

        $array = array();
        $queryResult = $this->stmt->get_result();
        while ($row = $queryResult->fetch_array()){
            if (gettype($row) == "array"){
                $array[] = $parse($row);
            }
        }

        $this->closeQuery();

        if (count($array) == 1)
            return $array[0];

        if ($array == [])
            return null;

        return $array;
    }

    /**
     * Close a query
     * @return bool
     */
    protected function closeQuery() {
        return $this->stmt->close();
    }

    /**
     * Execute prepared query and after closes it. Returns TRUE on success, FALSE on error
     * @return bool
     */
    protected function execAndCloseQuery(){
        $ret = $this->execQuery();
        $this->closeQuery();
        return $ret;
    }

    protected function bindParams(array $vars){
        if (empty($vars))
            return;

        $types = "";
        $localVars = array();

        foreach ($vars as $var){
            switch (gettype($var)){
                case "integer":
                    $types .= "i";
                    $localVars[] = $var;
                    break;
                case "string":
                    $types .= "s";
                    $localVars[] = $var;
                    break;
                case "boolean":
                    $types .= "i";
                    $localVars[] = intval($var);
                    break;
                case "object":
                    switch (get_class($var)){
                        case "DateTime":
                            $types .= "s";
                            $localVars[] = $var->format("Y-m-d");
                            break;
                }
            }
        }

        $this->stmt->bind_param($types, ...$localVars);
    }
}