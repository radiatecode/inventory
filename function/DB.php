<?php
/**
 * Created by PhpStorm.
 * User: Radiate Noor
 * Date: 6/17/2019
 * Time: 10:00 AM
 */
require "../config/db_config.php";
class DB
{
    private $connection;

    private $errno = '';
    private $error = '';

    private $SQL="";
    private $WHERE_SQL = "";

    /* Chaining Method Count */
    private $whereCount = 0;

    private static $_instance; //The single instance

    public static function getInstance() {
        if(!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    // Constructor
    private function __construct() {
        $this->connection = new mysqli( DB_HOST , DB_USER , DB_PASS , DB_NAME );
        // Error handling
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    // Magic method clone is empty to prevent duplication of connection
    private function __clone() { }
    // Get mysqli connection

    public function getConnection() {
        return $this->connection;
    }

    // Executes a database query
    public function query( $query )
    {
        return $this->connection->query( $query );
    }

    public function escapeString( $string )
    {
        return $this->connection->real_escape_string( $string );
    }

    // Get the data return int result
    public function numRows( $result )
    {
        return $result->num_rows;
    }

    public function lastInsertedID()
    {
        return $this->connection->insert_id;
    }

    // Get query using assoc method
    public function fetchAssoc( $result )
    {
        return $result->fetch_assoc();
    }

    // Gets array of query results
    public function fetchArray( $result , $resultType = MYSQLI_ASSOC )
    {
        return $result->fetch_array( $resultType );
    }

    // Fetches all result rows as an associative array, a numeric array, or both
    public function fetchAll( $result , $resultType = MYSQLI_ASSOC )
    {
        return $result->fetch_all( $resultType );
    }

    // Get a result row as an enumerated array
    public function fetchRow( $result )
    {
        return $result->fetch_row();
    }

    // Free all MySQL result memory
    public function freeResult( $result )
    {
        $this->connection->free_result( $result );
    }

    public function insert($table,array $data){
        $columns=[];$values=[];
        foreach ($data as $key=>$value) {
            $columns[]=$key;
            $values[]="'".$value."'";
        }
        $sql_columns=implode(',',$columns);
        $sql_values=implode(',',$values);

        $SQL="INSERT INTO ".$table."(".$sql_columns.") VALUES(".$sql_values.")";
        $result=$this->query($SQL);
        if(!$result){
            return false;
        }
        return true;
    }

    public function update($table,array $update_data){
        $this->emptySql();
        $set_columns_and_values=[];
        foreach ($update_data as $key=>$value) {
            $set_columns_and_values[] = $key."='".$value."'";
        }
        $sql_columns_values=implode(',',$set_columns_and_values);

        $this->SQL .="UPDATE ".$table." SET ".$sql_columns_values;
        return $this;
    }

    public function all($table){
        $SQL = "SELECT * FROM ".$table." Order By `id` DESC";
        $result=$this->query($SQL);
        if(!$result){
            return false;
        }
        return $result;
    }

    public function find($table,$value){
        $SQL = "SELECT * FROM ".$table." WHERE `id`='".$value."' ".
            "ORDER BY `id` DESC LIMIT 1";
        $result = $this->connection->query($SQL);
        if(!$result){
            return false;
        }
        return $result;
    }

    public function delete($table){
        $this->emptySql();
        $this->SQL .= "DELETE FROM ".$table;
        return $this;
    }

    public function joinAndGet(array $tables,
                               array $joinTableCondition,
                               array $selects,
                               $orderBy=null,
                               array $whereConditions=null){
        $SQL="";$where_columns=[];

        $sql_select_columns =implode(',',$selects);
        $sql_from_tables=implode(',',$tables);
        $join_conditions=implode(' AND ',$joinTableCondition);

        $SQL .="SELECT ".$sql_select_columns." FROM ".$sql_from_tables;
        $SQL .=" WHERE ".$join_conditions;
        if ($whereConditions != null){
            foreach ($whereConditions as $key=>$value) {
                $where_columns[] = $key."='".$value."'";
            }
            $sql_where_columns=implode(' AND ',$where_columns);
            $SQL .= ' AND '.$sql_where_columns;
        }
        if ($orderBy != null){
            $SQL .= " ORDER BY ".$orderBy." DESC";
        }
        $result = $this->connection->query($SQL);
        if(!$result){
            return false;
        }

        return $result;
    }

    /* Try to like laravel*/
    public function emptySql(){
        $this->SQL = "";
        $this->WHERE_SQL = "";
        $this->whereCount=0;
    }
    public function select(array $selects){
        $this->emptySql();
        $sql_select_columns =implode(',',$selects);
        $this->SQL .= "SELECT ".$sql_select_columns." FROM ";
        return $this;
    }

    public function table($table){
        $this->SQL .= $table;
        return $this;
    }

    public function join($table,$leftTable,$rightTable){
        $this->SQL .= " INNER JOIN ".$table." ON ".$leftTable."=".$rightTable;
        return $this;
    }

    public function leftJoin($table,$leftTable,$rightTable){
        $this->SQL .= " LEFT JOIN ".$table." ON ".$leftTable."=".$rightTable;
        return $this;
    }

    public function where($column,$operation,$value){
        if ($this->whereCount>0){
            $this->SQL .= " AND ".$column." ".$operation." '".$value."'";
        }else{
            $this->SQL .= " WHERE ".$column." ".$operation." '".$value."'";
        }
        $this->whereCount++;
        return $this;
    }

    public function whereNotIn($column,$values){
        $not_in_values = implode(',',$values);
        if ($this->whereCount>0){
            $this->SQL .= " AND ".$column." NOT IN (".$not_in_values.")";
        }else{
            $this->SQL .= " WHERE ".$column." NOT IN (".$not_in_values.")";
        }
        $this->whereCount++;
        return $this;
    }

    public function groupBy($column = []){
        $columns = implode(',',$column);
       $this->SQL .= " GROUP BY ".$columns;
       return $this;
    }

    public function orderBy($column,$by){
        $this->SQL .= " ORDER BY ".$column." ".$by;
        return $this;
    }

    public function get(){
        $result = $this->connection->query($this->SQL);
        if(!$result){
            return false;
        }
        return $result;
    }
    public function seeGenerateSQLString(){
        return $this->SQL;
    }
    /* Ending to Try to like laravel*/

    //Closes the database connection
    public function close()
    {
        $this->connection->close();
    }

    public function sql_error()
    {
        $this->errno = $this->connection->errno;
        $this->error = $this->connection->error;
        return $this->errno . ' : ' . $this->error;
    }

    public function sql_errorno(){
        if( empty( $this->error ) )
        {
            $this->errno = $this->connection->errno;
            $this->error = $this->connection->error;
        }
        return $this->errno;
    }
}