<?php


/**
 * Description of IntervenantDAO
 *
 * @author Nicolas Beurion
 */

require_once(ROOT.'models/dao/main_dao.php');

// Inclusion du fichier de la classe Intervenant
require_once(ROOT.'models/intervenant.php');



class IntervenantDAO extends MainDAO
{

    private $resultset = array();
    
    
    public function __construct()
    {
         $this->resultset['response'] = array();
         $this->resultset['response']['errors'] = array();
    }
    
    
    
    /**
     * selectAll()
     * 
     * @return liste d'objets "Intervenant"
     */
    public function selectAll() 
    {
        $this->resultset['response']['intervenants'] = array();
        
        try
        {
            // Connection à la base de données
            $this->connectDB();

            // Création de l'appel à la procédures stockées
            $this->prepareStatement("SELECT * FROM intervenant");
            
            // Execution de la requête préparée
            $this->executeStatement();
            
            // resultat des intervenants trouvées
            if ($this->getRowCount() > 0)
            {
                // Inclusion du fichier de la classe Organisme
                //require_once(ROOT.'models/intervenant.php');
                
                // Pour chaque ligne trouvées, ajout d'un organisme dans la liste
                while ($tabChamps = $this->getStatementFetch())
                {
                    $intervenant = new Intervenant(
                            $tabChamps['id_intervenant'], 
                            $tabChamps['ref_organ'],
                            $tabChamps['email_intervenant'],
                            $tabChamps['nom_intervenant'],
                            $tabChamps['tel_intervenant']);
                    
                    array_push($this->resultset['response']['intervenants'], $intervenant);
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
    
   
    
    public function selectByEmail($emailIntervenant) 
    {
        $this->resultset['response']['intervenant'] = array();
        
        if (isset($emailIntervenant) && !empty($emailIntervenant))
        {            
            try
            {
                // Connection à la base de données
                $this->connectDB();

                // Création de l'appel à la requête préparée
                $this->prepareStatement("SELECT * FROM intervenant WHERE LOWER(email_intervenant) = '".strtolower($emailIntervenant)."'");

                // Execution de la requête préparée
                $this->executeStatement();

                // resultat des organisme trouvé
                if ($this->getRowCount() > 0)
                {
                    // Inclusion du fichier de la classe Organisme
                    //require_once(ROOT.'models/intervenant.php');
                    
                    // Instanciation de l'organisme recherché
                    $tabChamps = $this->getStatementFetch();
                    
                    $intervenant = new Intervenant(
                            $tabChamps['id_intervenant'], 
                            $tabChamps['ref_organ'],
                            $tabChamps['email_intervenant'],
                            $tabChamps['nom_intervenant'],
                            $tabChamps['tel_intervenant']);

                    $this->resultset['response']['intervenant'] = $intervenant;
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
        $this->resultset['response']['intervenant'] = array();
        
        if (!empty($values))
        {
            try
            {
                // Connection à la base de données
                $this->connectDB();

                $request = $this->createRequestString("insert", $values, "intervenant");
                
                // Création de l'appel à la requête préparée
                $this->prepareStatement($request);
                    
                // Execution de la requête préparée
                $this->executeStatement();

                // On récupère l'id généré par l'insertion
                $this->resultset['response']['intervenant']['last_insert_id'] = $this->getLastInsertId();

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
        
        // Retourne l'id générée par l'insertion d'un intervenant si elle a fonctionnée
        return $this->resultset;
    }
    
    
    
    
    
    public function update($values)
    {
        $this->resultset['response']['intervenant'] = array();
        
        if (!empty($values))
        {
            try
            {
                // Connection à la base de données
                $this->connectDB();
                
                $request = $this->createRequestString("update", $values, "intervenant");
                
                // Création de l'appel à la requête préparée
                $this->prepareStatement($request);

                // Execution de la requête préparée
                $this->executeStatement();

                // On récupère le nombre de lignes affectées par la mise  à jour
                $this->resultset['response']['intervenant']['row_count'] = $this->getRowCount();

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
    
    
    
}

?>
