<?php

/**
* 
*/
class Category extends Model
{
	
    protected static $tablename = "categories";

    /**
    * Adds record to DB
    * 
    * @return boolean 
    */   
    public function addRecord(array $args)
    {
        $sql = $this->db->prepare(
                                'INSERT INTO ' . static::$tablename . '(name) 
                                 VALUES(:name)'
                                 );
        $sql->execute(array(
                            'name' => $args['name'],
                           )
        );

        if ($sql->rowCount() === 0) {
            return false;
        }
        return true;
    }
 
    /**
    * Edits record to DB
    * 
    * @return boolean
    */    
    public function editRecord(array $args)
    {
        $sql = $this->db->prepare(
                                'UPDATE LOW_PRIORITY IGNORE ' . static::$tablename . 
                                ' SET name=:name
                                WHERE id=:id'
                                 );
        $sql->execute(array(
                          'id'      => $args['id'],
                          'name'    => $args['name'], 
                           )
        );

        if ($sql->rowCount() === 0) {
            return false;
        }
        return true;
    }

    
}