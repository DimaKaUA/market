<?php

/**
* Class Order
*/
class Order extends Model {

    protected static $tablename = "orders";

    /**
    * Adds record to DB
    * @param array $args <p>Array with column values</p>
    * @return boolean 
    */   
    public function addRecord(array $args)
    {
        $sql = $this->db->prepare(
                                'INSERT INTO ' . static::$tablename . '(
                                 user_name, user_email, user_phone, status, items, total_price
                                 ) 
                                 VALUES(:user_name, :user_email, :user_phone, :status, :items, :total_price)'
                                 );
        $sql->execute(array(
                            'user_name'   => $args['user_name'],
                            'user_email'  => $args['user_email'],
                            'user_phone'  => $args['user_phone'],
                            'status'      => $args['status'],
                            'items'       => $args['items'],
                            'total_price' => $args['total_price'],
                           )
        );

        if ($sql->rowCount() === 0) {
            return false;
        }
        return true;
    }
 
    /**
    * Edits status of record in DB
    * @param array $args <p>Array with column values</p>
    * @return boolean
    */    
    public function editRecord(array $args)
    {
        $sql = $this->db->prepare(
                                'UPDATE LOW_PRIORITY IGNORE ' . static::$tablename . 
                                ' SET status=:status
                                 WHERE id=:id'
                                 );
        $sql->execute(array(
                          'id'     => $args['id'],
                          'status' => $args['status'],
                           )
        );

        if ($sql->rowCount() === 0) {
            return false;
        }
        return true;
    }

    /**
     * Returns text explanation of status for order :<br/>
     * <i>1 - New oreder, 2 - In progress, 3 - Delivered, 4 - Closed</i>
     * @param integer $status <p>Ststus</p>
     * @return string <p>Text explanation</p>
     */
    public static function getStatusText($status)
    {
        switch ($status) {
            case '1':
                return 'New oreder';
                break;
            case '2':
                return 'In progress';
                break;
            case '3':
                return 'Delivered';
                break;
            case '4':
                return 'Closed';
                break;
        }
    }
}
