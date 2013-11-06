<?php

/**
 * Description of main_dao
 *
 * @author Nicolas Beurion
 */

require_once('models/dao/pbo_connect_db.php');


class MainDAO 
{
    
    private $dbConnect = null;
    private $callStatement = null;
    
    
    public function connectDB()
    {
        $this->dbConnect = new PDOConnectDB();
        $this->dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function disconnectDB()
    {
        $this->dbConnect = null;
    }
    
    
    
    public function prepareStatement($requestString)
    {
        $this->callStatement = $this->dbConnect->prepare($requestString);
    }
    
    public function bindStatementParams($params = array())
    {
        if (!empty($params))
        {
            foreach ($params as $param)
            {
                if (!empty($param['id']) && !empty($param['value']) && !empty($param['type']))
                {
                    $id = $param['id'];
                    $value = $param['value'];
                    $type = PDO::PARAM_STR;
                    
                    switch($param['type'])
                    {
                        case "string":
                            $type = PDO::PARAM_STR;
                            break;

                        default :
                            break;
                    }
                    /*
                    echo "id = ".$id."<br />";
                    echo "value = ".$value."<br />";
                    echo "type= ".$type."<br />";
                    */
                    $this->callStatement->bindParam($id, $value, $type);
                }
            }
        }
        
    }
    
    public function executeStatement()
    {
        $this->callStatement->execute();
    }
    
    public function queryStatement($queryString)
    {
        $this->callStatement = $this->dbConnect->query($queryString);
    }
    
    
    
    public function getStatementFetch()
    {
        return $this->callStatement->fetch();
    }
    
    public function getLastInsertId()
    {
        return $this->dbConnect->lastInsertId();
    }
    
    public function getRowCount()
    {
        return $this->callStatement->rowCount();
    }
    
    
    
    public function closeStatement()
    {
        $this->callStatement->closeCursor();
        $this->callStatement = null;
    }

    
    public function createRequestString($mode, $values, $subject)
    {
        if (!empty($mode) && !empty($values)  && !empty($subject))
        {
            $requestString = "";
                    
            if ($mode == "insert")
            {
                $fields = "";
                $insertValues = "";
                $i = 0;
                foreach ($values as $field => $value)
                {
                    if ($i == 0)
                    {
                        $fields .= $field;
                        $insertValues .= "'".$value."'"; 
                    }
                    else 
                    {
                        $fields .= ", ".$field;
                        $insertValues .= ", '".$value."'";
                    }
                    $i++;
                }
                
                $requestString = "INSERT INTO ".$subject." (".$fields.") VALUES (".$insertValues.") ";
                
            }
            else if ($mode == "update")
            {
                $updateString = "";

                $i = 0;
                foreach ($values as $field => $value)
                {
                    if ($i == 0)
                    {
                        $updateString .= $field." = '".$value."'";  
                    }
                    else 
                    {
                        $updateString .= ", ".$field." = '".$value."'";
                    }
                    $i++;
                }

                $requestString = "UPDATE ".$subject." SET ".$updateString." ";
            } 
        }
        
        return $requestString;
    }
    
    
    /*
    public function populateDataObject($stringObject)
    {
        
        // Pour chaque ligne trouvées, ajout d'un organisme dans la liste
        while ($tabChamps = $this->callStatement->fetch())
        {
            $dataObject = new $stringObject();
            
            $organisme = new Organisme(
                    $tabChamps['id_organ'], 
                    $tabChamps['ref_code_organ'], 
                    $tabChamps['nom_organ'],
                    $tabChamps['code_postal_organ'], 
                    $tabChamps['adresse_organ'], 
                    $tabChamps['ville_organ'], 
                    $tabChamps['tel_organ'],
                    $tabChamps['fax_organ'],
                    $tabChamps['email_organ'],
                    $tabChamps['numero_interne']);

            array_push($this->resultset['response']['organismes'], $organisme);
        }
    }
    */
    
    /*
    public function executeRequest($mode, $request, $filename = "", $modelName = "")
    {
        try
        {
            $result = null;
            
            // Connection à la base de données
            $this->connectDB();        

            // Création de l'appel à la requête préparée
            $this->prepareStatement($request);
            
            // Execution de la requête préparée
            $this->executeStatement();
            
            
            switch ($mode) {
                
                case "select":
                    
                    break;

                case "insert":
                    
                    // On récupère l'id généré par l'insertion
                    $result['last_insert_id'] = $this->getLastInsertId();
                    break;

                case "update":


                    break;

                case "delete":


                    break;

                default:
                    break;
            }
            // On récupère l'id généré par l'insertion
            //$result['last_insert_id'] = $this->getLastInsertId();
            
            // Fermeture de la requête préparée et fermeture de la connection
            $this->closeStatement();
            $this->disconnectDB();
        } 
        catch (PDOException $e)
        {
            // Erreur de connection ou probleme avec la création de la requête préparée
            $result['response']['errors'][] = array('type' => "pdo_exception", 'message' => $e->getMessage().".");
        }
        
        return $result;
    }
    */
}

?>
