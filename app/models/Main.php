<?php 

class Model_Main extends Model {
    
    protected static $tablename = "main";

    public function add_row(array $args)
    {
        $sql = $this->db->prepare(
                                  'INSERT INTO ' . static::$tablename . '(year, site, description) 
                                   VALUES(:year, :site, :description)'
                                 );
        $sql->execute(array(
        	                'year'        => $args['year'], 
        	                'site'        => $args['site'], 
        	                'description' => $args['description'])
                           );

        if ($sql->rowCount() === 0) {
            return FALSE;
        }
        return TRUE;
    }

    public function edit_row(array $args) 
    {
        $sql = $this->db->prepare(
                                  'UPDATE LOW_PRIORITY IGNORE ' . static::$tablename . 
                                  ' SET year=:year, site=:site, description=:description
                                   WHERE id=:id'
                                 );
        $sql->execute(array(
                          'id'          => $args['id'],
                          'year'        => $args['year'], 
                          'site'        => $args['site'], 
                          'description' => $args['description'])
                           );

        if ($sql->rowCount() === 0) {
            return FALSE;
        }
        return TRUE;
    }
}