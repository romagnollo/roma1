<?php

class Database {

    private $conn;

    function __construct() 
    {
        $this->conn = new mysqli("localhost","magento1",'gT89Da#DashE2',"brilhantefolheados_com_br");
        // $this->conn = new mysqli("127.0.0.1:3306","cotacaofrete",'qwe123',"cotacao_frete");
        $this->conn->query("SET NAMES 'utf8'");
        $this->conn->query('SET character_set_connection=utf8');
        $this->conn->query('SET character_set_client=utf8');
        $this->conn->query('SET character_set_results=utf8');
    }

    public function select($query) 
    {
        $exe = $this->conn->query($query);
        return $exe;
    }

    public function insert($query) 
    {
        $exe = $this->conn->query($query);
        return $exe;
    }

    public function update($query) 
    {
        $exe = $this->conn->query($query);
        return $exe;
    }

    public function delete($query) 
    {
        $exe = $this->conn->query($query);
        return $exe;
    }

    public function array($exe) {
        $arr = [];
        $res = $exe->fetch_object();

        foreach ($res as $key => $value) {
            $arr[$key] = $value;
        }

        return $arr;
    }

    public function get_last_id() {
        return mysqli_insert_id($this->conn);
    }
}

    $mysql = new Database;
    $query = 'SHOW TABLES';
    $exe = $mysql->select($query);

    $term_search = 'adrien';
    $term_search2 = 'Romagnollo';
    $term_search3 = 'Romagnollo';
    $term_replace = 'brilhante';
    $term_replace2 = 'Brilhante';
    $term_replace3 = 'Brilhante';

    while ($res = $exe->fetch_array()) {
        $keys = array_keys($res);
        $key_field = $keys[0];
        $table = $res[$key_field];

        $query = 'SHOW COLUMNS FROM '.$table;
        $exe2 = $mysql->select($query);

        echo "Processing Table: $table\n";
        echo "-----------------------------------------------------------------------------------------\n";

        while ($res2 = $exe2->fetch_array()) {
            $field_name = $res2[0];

            $query = 'SELECT '.$field_name.' FROM '.$table.' WHERE '.$field_name.' LIKE "%'.$term_search.'%"';            
            $exe3 = $mysql->select($query);

            if ($exe3) {
                while ($res3 = $exe3->fetch_array()) {
                    $query2 = 'UPDATE '.$table.' SET '.$field_name.' = REPLACE('.$field_name.',"'.$term_search.'","'.$term_replace.'") WHERE '.$field_name.' LIKE "%'.$term_search.'%"';
                    $query2a = 'UPDATE '.$table.' SET '.$field_name.' = REPLACE('.$field_name.',"'.$term_search2.'","'.$term_replace2.'") WHERE '.$field_name.' LIKE "%'.$term_search.'%"';                    
                    $query2b = 'UPDATE '.$table.' SET '.$field_name.' = REPLACE('.$field_name.',"'.$term_search3.'","'.$term_replace3.'") WHERE '.$field_name.' LIKE "%'.$term_search.'%"';                    

//                    $mysql->update($query2);
//                    $mysql->update($query2a);
//                    $mysql->update($query2b);

                    echo $query."\n";
//                    echo $query2."\n";
//                    echo $query2a."\n\n";
                    break;
                }
            }
        }
    }

?>
