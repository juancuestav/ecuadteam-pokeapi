<?php
  class Model {

    private $db;
    private $conexion;

    function __construct() {
      /* $this->db = new Database();
      $this->conexion = $this->db->conexion; */
    }

    private function convertirUTF8($array){
      array_walk_recursive($array,function(&$item,$key){
          if(!mb_detect_encoding($item,'utf-8',true)){
              $item = utf8_encode($item);
          }
      });
      return $array;
  }

    // SELECT
    public function executeSelectQuery($sqlstr){
        $results = $this->conexion->query($sqlstr);
        $resultArray = array();
        foreach ($results as $key) {
            $resultArray[] = $key;
        }
        return $this->convertirUTF8($resultArray);
    }

    protected function getLastIdFromTable($table) {
      $query = "SELECT MAX(id) AS id FROM " . $table;
      $result = $this->executeSelectQuery($query);
      return intval($result[0]["id"]);
    }

    // UPDATE OR DELETE
    public function executeUpdateDeleteQueryAndGetAmountOfAffectedRows($sqlstr){
        $results = $this->conexion->query($sqlstr);
        return $this->conexion->affected_rows;
    }

    //INSERT 
    public function executeInsertQueryAndGetNewId($sqlstr) {
        $results = $this->conexion->query($sqlstr);
        $filas = $this->conexion->affected_rows;
        if($filas >= 1) {
            return $this->conexion->insert_id;
        } else {
            return 0;
        }
    }
  }
?>