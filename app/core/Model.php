<?php 

abstract class Model {

    protected $db;

    public function __construct()
    {
        $this->db = (DB::getInstance())->getConnection();
    }
 
    /**
    * Selects all data from DB
    * 
    * @return array
    */
    public function getList() 
    {
        $sql = 'SELECT * 
                FROM ' . static::$tablename;
        $result = $this->db->query($sql);

        if($result->rowCount() === 0){
            Router::errorPage404();
        }

        $list = array();

        while($row = $result->fetch()) {
            $list[] = $row;
        }
        return $list;
    }

    /**
     * Selects one record by id from DB
     *
     * @return array
     */
    public function getRecordById($id) 
    {
        $id = intval($id);
        $sql = $this->db->prepare(
                                'SELECT * 
                                FROM ' . static::$tablename . 
                                ' WHERE id = ?'
                                 );
        $sql->execute([$id]);

        if($sql->rowCount() !== 1){
            Router::errorPage404();
        }
        return $sql->fetch();
    }

    /**
     * Selects one record by column name from DB
     *
     * @return array
     */
    public function getRecordByName($columnName) 
    {
        $columnName = strval($columnName);

        $sql = $this->db->prepare(
                                'SELECT * 
                                FROM ' . static::$tablename . 
                                ' WHERE name = :columnName'
                                 );
        $sql->execute(array(
                            'columnName' => $columnName,
                           )
        );

        if($sql->rowCount() !== 1){
            Router::errorPage404();
        }
        return $sql->fetch();
    }
  
    /**
    * Adds record to DB
    * 
    * @return boolean 
    */   
    abstract public function addRecord(array $args);
 
    /**
    * Edits record to DB
    * 
    * @return boolean
    */    
    abstract public function editRecord(array $args);
 
    /**
     * Deletes record from DB
     * 
     * @return boolean
     */
    public function deleteRecord($id)
    {
        $sql = $this->db->prepare(
                                'DELETE FROM ' . static::$tablename .
                                ' WHERE id = ?'
                                 );
        $sql->execute([$id]);

        if ($sql->rowCount() !== 1) {
            return FALSE;
        }
        return TRUE;
    }
}