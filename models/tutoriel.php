
<?php

class Tutoriel extends Model
{
    
    var $table = 'tutoriels';

    // va chercher tous les tutos de la table 'tutoriels'
    function getAll($num = 5)
    {
        $data = array();
                
        $request = "SELECT * FROM ".$this->table;
        $resultset = mysql_query($request);    
        //$result = mysql_fetch_array($resultset);
        
        while (list($id, $nom) = mysql_fetch_array($resultset))
        {
            //echo $id.' => '.$nom;
            $data[] = array('id' => $id, 'nom' => $nom);
        }
        
        return $data;
        /*
        return $this->find(array(
            'limit'=> $num,
            'order' => 'id DESC'
        ));
        */
    }
    
    function find($id)
    {
        $data = array();
        $request = 'SELECT * FROM '.$this->table
                .' WHERE id='.$id;
        $resultset = mysql_query($request);
        
        list($id, $nom, $descript) = mysql_fetch_array($resultset);
        $data = array('id' => $id, 'nom' => $nom, 'descript' => $descript);
        
        return $data;
    }
}
?>
