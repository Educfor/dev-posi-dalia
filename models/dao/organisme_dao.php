<?php

/*
$stmt = $mysqli->prepare("INSERT INTO table2 (f1, f2) VALUES (?, ?)");
$stmt->bind_param('ss', $field1, $field2);

$field1 = "String Value";
$field2 = null;

$stmt->execute();
*/



/**
 * Description of OrganismeDAO
 *
 * @author Nicolas Beurion
 */


require_once(ROOT.'models/dao/main_dao.php');

// Inclusion du fichier de la classe Organisme
require_once(ROOT.'models/organisme.php');



class OrganismeDAO extends MainDAO
{

    private $resultset = array();
   
    
    
    public function __construct()
    {
         $this->resultset['response'] = array();
         $this->resultset['response']['errors'] = array();
    }
    
    /**
     * Identification du code organisme
     * 
     * @param $codeOrganisme: le code organisme à authentifier
     * 
     * @return true si le $codeOrganisme est correct
     */
    public function authenticate($codeOrganisme)
    {
        $this->resultset['authentification'] = false;

        try
        {
            // Connection à la base de données
            $this->connectDB();

            // Création de l'appel à la procédures stockées
            $this->prepareStatement("CALL identifierOrganisme(?, @retour, @foundrows)");
            
            // Définition des paramètres
            $bindParams[] = array('id' => 1, 'value' => $codeOrganisme, 'type' => "string");
            
            $this->bindStatementParams($bindParams);
            
            // Execution de la requête préparée
            $this->executeStatement();
            
            if ($this->getRowCount() > 0)
            {                
                // Selection du code organisme correspondant
                $tabChamps = $this->getStatementFetch();

                $this->resultset['ref_code_organisme'] = $tabChamps['id_code_organ'];
                $this->resultset['code_organisme'] = $tabChamps['code_organ'];
            }

            // Fermeture de la requête préparée
            $this->closeStatement();
            
            // Requete pour les valeurs de retour de la proc�dure stock�e
            $this->queryStatement("SELECT @retour, @foundrows");

            $retour_statmt = $this->getStatementFetch();
            $code_retour = $retour_statmt[0];
            $row_count = $retour_statmt[1];

            
            // Gestion du code de retour de la procédure stockées et création des messages d'erreurs en cas d'echec	
            switch ($code_retour)
            {
                case 0 :
                    if ($row_count == 0)
                    {
                        $this->resultset['errors'][] = array('type' => "no_resultset", 'message' => "Il n'existe aucun code organisme correspondant.");
                        $this->resultset['authentification'] = false;
                    }
                    else 
                    {
                        $this->resultset['authentification'] = true;
                    }
                    break;
                    
                case 1317 :
                    $this->resultset['errors'][] = array('type' => "sql_exception", 'message' => "La requête a été interrompue");
                    $this->resultset['authentification'] = false;
                    break;
                
                case 10000 :
                    $this->resultset['errors'][] = array('type' => "sql_exception", 'message' => "Exception SQL non gérée");
                    $this->resultset['authentification'] = false;
                    break;
                
                default :
                        break;
            }

            
            // Fermeture de la requête préparée et fermeture de la connection
            $this->closeStatement();
            $this->disconnectDB();
        } 
        catch (PDOException $e)
        {
            // Erreur de connection ou probleme avec la création de la requête préparée
            $this->resultset['errors'][] = array('type' => "pdo_exception", 'message' => $e->getMessage().".");
        }

        // Retourne un tableau indexé avec pour chaque ligne un objetCodeOrganisme
        // Retourne null si aucun code organisme n'a été trouvée
        // Renvoi egalement les erreurs qui se sont produites
        return $this->resultset;
    }
    
    

    
    
    /**
     * selectAll()
     * 
     * @return liste d'objets "Organisme"
     */
    public function selectAll() 
    {
        $this->resultset['response']['organismes'] = array();

        try
        {  
            // Connection à la base de données
            $this->connectDB();
            
            // Création de l'appel à la requête préparée
            $this->prepareStatement("SELECT * FROM organisme");

            // Execution de la requête préparée
            $this->executeStatement();
            
            // resultat des organismes trouvées
            if ($this->getRowCount() > 0)
            {
                // Inclusion du fichier de la classe Organisme
                //require_once(ROOT.'models/organisme.php');

                // Pour chaque ligne trouvées, ajout d'un organisme dans la liste
                while ($tabChamps = $this->getStatementFetch())
                {
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

            // Fermeture de la requête préparée et fermeture de la connection
            $this->closeStatement();
            $this->disconnectDB();
        } 
        catch (PDOException $e)
        {
            // Erreur de connection ou probleme avec la requête préparée
            $this->resultset['response']['errors'][] = array('type' => "pdo_exception", 'message' => $e->getMessage().".");
        }
        
        // Retourne un tableau indexé avec pour chaque ligne un tableau de champs d'un organisme
        // Retourne null si aucun organisme n'a été trouvée
        // Renvoi egalement les erreurs qui se sont produites
        return $this->resultset;
    }
    
    
    
    
    
    public function selectById($id_organisme) 
    {
        $this->resultset['response']['organisme'] = array();
        
        if(isset($id_organisme) && !empty($id_organisme))
        {           
            try
            {
                // Connection à la base de données
                $this->connectDB();

                // Création de l'appel à la requête préparée
                $this->prepareStatement("SELECT * FROM organisme WHERE id_organ = '".$id_organisme."'");

                // Execution de la requête préparée
                $this->executeStatement();

                // resultat de l'organisme trouvé
                if ($this->getRowCount() > 0)
                {
                    // Inclusion du fichier de la classe Organisme
                    //require_once(ROOT.'models/organisme.php');
                    
                    // Instanciation de l'organisme recherché
                    $tabChamps = $this->getStatementFetch();

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

                    $this->resultset['response']['organisme'] = $organisme;
                }
                
                // Fermeture de la requête préparée et fermeture de la connection
                $this->closeStatement();
                $this->disconnectDB();
            } 
            catch (PDOException $e)
            {
                // Erreur de connection ou probleme avec la requête préparée
                $this->resultset['response']['errors'][] = array('type' => "pdo_exception", 'message' => $e->getMessage().".");
            }
        }
        
        return $this->resultset;
    }
   
    
    
    
    
    public function selectByName($nameOrganisme) 
    {
        $this->resultset['response']['organisme'] = array();
        
        if(isset($nameOrganisme) && !empty($nameOrganisme))
        {     
            $nameOrganisme = strtoupper(preg_replace("`(\s|-|_|\/)*`", "", $nameOrganisme));
                    
            try
            {
                // Connection à la base de données
                $this->connectDB();

                // Création de l'appel à la requête préparée
                $this->prepareStatement("SELECT * FROM organisme 
                                        WHERE REPLACE(UPPER(nom_organ),' ','') = '".$nameOrganisme."'
                                        OR REPLACE(UPPER(nom_organ),'-','') = '".$nameOrganisme."'
                                        OR REPLACE(UPPER(nom_organ),'_','') = '".$nameOrganisme."'");

                // Execution de la requête préparée
                $this->executeStatement();

                // resultat des organisme trouvé
                if ($this->getRowCount() > 0)
                {
                    // Inclusion du fichier de la classe Organisme
                    //require_once(ROOT.'models/organisme.php');
                    
                    // Instanciation de l'organisme recherché
                    $tabChamps = $this->getStatementFetch();
                    
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

                    $this->resultset['response']['organisme'] = $organisme;
                }
                
                // Fermeture de la requête préparée et fermeture de la connection
                $this->closeStatement();
                $this->disconnectDB();
            } 
            catch (PDOException $e)
            {
                // Erreur de connection ou probleme avec la requête préparée
                $this->resultset['response']['errors'][] = array('type' => "pdo_exception", 'message' => $e->getMessage().".");
            }
            
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    public function insert($values) 
    {
        $this->resultset['response']['organisme'] = array();
       
        if (!empty($values))
        {
            try
            {
                // Connection à la base de données
                $this->connectDB();
                        
                $request = $this->createRequestString("insert", $values, "organisme");
                        
                // Création de l'appel à la requête préparée
                $this->prepareStatement($request);
                
                // Execution de la requête préparée
                $this->executeStatement();

                // On récupère l'id généré par l'insertion
                $this->resultset['response']['organisme']['last_insert_id'] = $this->getLastInsertId();

                // Fermeture de la requête préparée et fermeture de la connection
                $this->closeStatement();
                $this->disconnectDB();
            } 
            catch (PDOException $e)
            {
                // Erreur de connection ou probleme avec la création de la requête préparée
                $this->resultset['response']['errors'][] = array('type' => "pdo_exception", 'message' => $e->getMessage().".");
            }
        }
        
        // Retourne l'id générée par l'insertion d'un organisme si elle a fonctionnée
        return $this->resultset;
    }
    
    
    
    
    
    public function update($values)
    {
        $this->resultset['response']['organisme'] = array();

        if (!empty($values))
        {
            try
            {
                // Connection à la base de données
                $this->connectDB();
                
                //$request = "UPDATE organisme SET ".$updateString." ";
                $request = $this->createRequestString("update", $values, "organisme");

                // Création de l'appel à la requête préparée
                $this->prepareStatement($request);

                // Execution de la requête préparée
                $this->executeStatement();

                // On récupère le nombre de lignes affectées par la mise  à jour
                $this->resultset['response']['organisme']['row_count'] = $this->getRowCount();

                // Fermeture de la requête préparée et fermeture de la connection
                $this->closeStatement();
                $this->disconnectDB();
            } 
            catch (PDOException $e)
            {
                // Erreur de connection ou probleme avec la création de la requête préparée
                $this->resultset['response']['errors'][] = array('type' => "pdo_exception", 'message' => $e->getMessage().".");
            }
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    public function delete($id_organisme, $nomOrganisme = "") 
    {
        
        
    }

}

?>
