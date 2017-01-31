<?php

/**
* 
*/
class Item extends Model
{
    protected static $tablename = "items";

    /**
    * Adds record to DB
    * 
    * @return boolean 
    */   
    public function addRecord(array $args)
    {
        $sql = $this->db->prepare(
                                'INSERT INTO ' . static::$tablename . '(
                                 name, category_id, description, price
                                 ) 
                                 VALUES(:name, :category_id, :description, :price)'
                                 );
        $sql->execute(array(
                            'name'        => $args['name'],
                            'category_id' => $args['category_id'],
                            'description' => $args['description'],
                            'price'       => $args['price'],
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
                                ' SET name=:name, category_id=:category_id, 
                                  description=:description, price=:price
                                 WHERE id=:id'
                                 );
        $sql->execute(array(
                          'id'      => $args['id'],
                          'name'    => $args['name'], 
                          'category_id' => $args['category_id'],
                          'description' => $args['description'],
                          'price'       => $args['price'],
                           )
        );

        if ($sql->rowCount() === 0) {
            return false;
        }
        return true;
    }

    /**
    * Selects list of items with category name
    * 
    * @return array 
    */   
    public function getListWithCategoryName()
    {
        $sql = 'SELECT i.id, i.name, c.name AS "category_name", i.description, i.price 
                FROM ' . static::$tablename . ' i LEFT JOIN categories c ON i.category_id = c.id';
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
    * Selects list of items by category id
    * 
    * @return array 
    */   
    public function getRecordByCategoryId($category_id) 
    {
        $sql = $this->db->prepare(
                                'SELECT * 
                                 FROM ' . static::$tablename . 
                                ' WHERE category_id = ?'
                                 );
        $sql->execute([$category_id]);

        $list = array();

        while($row = $sql->fetch()) {
            $list[] = $row;
        }
        return $list;
    }   
}
