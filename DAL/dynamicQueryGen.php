<?php
require_once("dbConn.php");
require_once("queryBase.php");
require_once ("dbContains.php");

//TODO: Fix duplicate code

class dynamicQueryGen extends queryBase
{
    private $class;
    protected bool $insertPrimary;

    public function __construct($class)
    {
        parent::__construct(dbConn::getInstance()->getConn());

        $this->insertPrimary = !$class::sqlPrimaryIncrement();
        $this->class = $class;
    }

    private $query;
    private $args;

    private function selectFormat(array $fields, string $tableName){
        $query = "";
        foreach ($fields as $field){
            $query .= $tableName . "." . $field . " AS " . $tableName . $field . ",";
        }
        return $query;
    }


    protected function selectFromClass($class){
        $query = $this->selectFormat($class::sqlFields(), $class::sqlTableName());

        foreach ($class::sqlLinks() as $k => $v){
            $query .= $this->selectFromClass($v);
        }

        return $query;
    }


    protected function select(array $fields, array $links){
        $query = "SELECT ";

        $query .= $this->selectFormat($fields, $this->class::sqlTableName());

        foreach ($links as $k => $v){
            $query .= $this->selectFromClass($v);
        }

        $query[strlen($query) - 1] = " ";
        $this->query .= $query;
    }

    protected function from(string $tableName){
        $query = "FROM " . $tableName . " ";
        $this->query .= $query;
    }

    private function genTableVar($var, $includeTable = null){
        if (is_null($includeTable)){
            $includeTable = (strpos($var, ".") === false);
        }

        if ($includeTable){
            return $this->class::sqlTableName() . "." . $var;
        }
        else {
            return $var;
        }
    }

    protected function orderBy(array $order){
        $query = " ORDER BY ";
        foreach ($order as $v){
            $query .= $this->genTableVar($v) . ", ";
        }
        $query = substr($query, 0, -2);
        $this->query .= $query;
    }

    protected function where(array $filter){
        if (array_key_exists("order", $filter)){
            $order = $filter["order"];
            unset($filter["order"]);
            if (gettype($order) != "array")
                $order = [$order];
        }

        if (!empty($filter)){
            $query = " WHERE ";

            foreach ($filter as $k => $v){
                if (gettype($v) == "array"){
                    $query .= $this->genTableVar($k) . " IN ( ";
                    $query .= join(", ", array_fill(0, count($v), "?"));
                    $query .= ") AND ";
                }
                else if (gettype($v) == "object" && get_class($v) == "dbContains"){
                    $query .= $v->genSql($this->genTableVar($k)) . " AND ";
                }
                else {
                    $query .= $this->genTableVar($k) . " = ? AND ";
                }
                $this->args[] = $v;
            }

            $query = substr($query, 0, -4);

            $this->query .= $query;
        }

        if (isset($order))
            $this->orderBy($order);
    }

    private array $joinedClasses;

    protected function joinClass($srcClass, $dstClass, $varName){
        $query = "";
        if (!in_array($dstClass::sqlTableName(), $this->joinedClasses)){
            $query = "INNER JOIN " . $dstClass::sqlTableName() . " ON " . $srcClass::sqlTableName() . "." . $varName . " = " . $dstClass::sqlTableName() . "." . $dstClass::sqlPrimaryKey() . " ";
            $this->joinedClasses[] = $dstClass::sqlTableName();
        }
        foreach ($dstClass::sqlLinks() as $k => $v){
            $query .= $this->joinClass($dstClass, $v, $k);
        }
        return $query;
    }

    protected function join(array $links){
        if (empty($links))
            return;

        $this->joinedClasses = [];
        $query = "";

        foreach ($links as $k => $v){
            $query .= $this->joinClass($this->class, $v, $k);
        }

        $this->query .= $query;
    }

    protected function deleteFrom(string $tableName) {
        $query = "DELETE FROM " . $tableName . " ";
        $this->query .= $query;
    }

    protected function updateFrom(array $fields, array $filter, array $keys){
        $newKeys = [];
        foreach ($fields as $field => $fieldval){
            if (in_array($field, $keys) && !in_array($field, $filter)){
                $newKeys[] = $field;
                $this->args[] = $fieldval;
            }
        }

        $query = "UPDATE " . $this->class::sqlTableName() . " SET ";

        foreach ($newKeys as $key){
            $query .= $key . " = ?,";
        }

        $query[strlen($query) - 1] = " ";
        $this->query .= $query;
    }

    public function get(array $filter = []){
        $this->query = "";
        $this->args = [];
        $this->select($this->class::sqlFields(), $this->class::sqlLinks());
        $this->from($this->class::sqlTableName());
        $this->join($this->class::sqlLinks());
        $this->where($filter);

        //echo $this->query;

        $this->prepareQuery($this->query);
        $this->bindParams($this->args);
        return $this->execQueryResult($this->class::sqlParseFunc());
    }

    /**
     * Expects an k,v array
     * @param array $fields
     * @return int|false
     */
    public function insert(array $fields){
        $keys = $this->class::sqlFields();
        $values = [];

        if (!$this->insertPrimary){
            if (($key = array_search($this->class::sqlPrimaryKey(), $keys)) !== false) {
                unset($keys[$key]);
            }
        }

        $newKeys = [];
        foreach ($keys as $k){
            if (array_key_exists($k, $fields)){
                $newKeys[] = $k;
            }
        }

        if (empty($newKeys))
            throw new appException("No keys were given!");

        $keys = $newKeys;

        $query = "INSERT INTO " . $this->class::sqlTableName() . " (";

        foreach ($keys as $key){
            $values[] = $fields[$key];
            $query .= $key . ",";
        }

        $query[strlen($query) - 1] = ")";

        $query .= " VALUES (";

        for ($i = 0; $i < count($values); $i++){
            $query .= "?,";
        }

        $query[strlen($query) - 1] = ")";

        //echo $query;

        $this->prepareQuery($query);
        $this->bindParams($values);
        $ret = $this->execQuery();
        $id = $this->stmt->insert_id;
        $this->closeQuery();
        if ($ret){
            return $id;
        }
        return false;
    }

    public function delete(array $filter){
        if (empty($filter))
            return false;

        $this->query = "";
        $this->args = [];

        $this->deleteFrom($this->class::sqlTableName());
        $this->where($filter);

        if (count($this->args) <= 0)
            throw new appException("You likely don't want to delete the entire table");

        $this->prepareQuery($this->query);
        $this->bindParams($this->args);
        return $this->execAndCloseQuery();
    }

    public function update(array $fields, array $filter = []){
        $keys = $this->class::sqlFields();

        if (empty($filter)){
            $filter = [$this->class::sqlPrimaryKey() => $fields[$this->class::sqlPrimaryKey()]];
            unset($fields[$this->class::sqlPrimaryKey()]);
        }

        $this->query = "";
        $this->args = [];

        $this->updateFrom($fields, $filter, $keys);
        $this->where($filter);

        $this->prepareQuery($this->query);
        $this->bindParams($this->args);
        return $this->execAndCloseQuery();
    }
}