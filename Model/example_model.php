<?php
require_once ("sqlModel.php");

class exampleModel extends sqlModel 
{
    private $id;
    private $name;
    private $email;

    protected const sqlTableName = "examplemodel"; // The name of the table
    protected const sqlPrimaryKey = "id"; // The name of the primary key
    protected const sqlFields = ["id", "name", "email"]; // The fields the database has
    protected const sqlLinks = []; // Foreign key references, presented as "field" => otherClass::class

    public function constructFull($id, $name, $email){
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        return $this;
    }

    public static function sqlParse(array $sqlRes): exampleModel { // Needed to parse the sql output into an object. Handled by queryBase.php
        return (new exampleModel)->constructFull($sqlRes[self::sqlTableName . "id"], $sqlRes[self::sqlTableName . "name"], $sqlRes[self::sqlTableName . "email"]);
        // The SQL generator adds the name of the table to the field, so joins can happen seamlessly
    }

    public function sqlGetFields(){
        return [ // To return a filled field array, useful for insert or update
            "id" => $this->id;
            "name" => $this->name;
            "email" => $this->email;
        ];
    }
}