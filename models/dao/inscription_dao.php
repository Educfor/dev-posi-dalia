<?php


/**
 * Description of IntervenantDAO
 *
 * @author Nicolas Beurion
 */

require_once(ROOT.'models/dao/main_dao.php');

// Inclusion du fichier de la classe Inscription
require_once(ROOT.'models/inscription.php');


class InscriptionDAO extends MainDAO
{

    private $resultset = array();
   
    
    
    public function __construct()
    {
         $this->resultset['response'] = array();
         $this->resultset['response']['errors'] = array();
    }
    
    
    
    
    public function selectByReferences($refUtilisateur, $refIntervenant) 
    {
        $this->resultset['response']['intervenant'] = array();
        
        if (!empty($refUtilisateur) && !empty($refIntervenant))
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
                    
                    // Instanciation de l'organisme recherché
                    $tabChamps = $this->getStatementFetch();
                    
                    $inscription = new Inscription(
                            $tabChamps['id_intervenant'], 
                            $tabChamps['ref_organ'],
                            $tabChamps['email_intervenant'],
                            $tabChamps['nom_intervenant'],
                            $tabChamps['tel_intervenant']);

                    $this->resultset['response']['inscription'] = $inscription;
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
    
    
    
    
    /*
    public function insert($values) 
    {
        $this->resultset['response']['inscription'] = array();
        
        $request = "INSERT INTO inscription (ref_user, ref_intervenant, date_inscription) "
                ."VALUES ('".$values['ref_user']."', '".$values['ref_intervenant']."', '".$values['date_inscription']."')";
        
        $this->resultset['response']['inscription'] = $this->executeRequest("insert", $request, "inscription", "Inscription");
         
        // Retourne l'id générée par l'insertion si elle a fonctionnée
        return $this->resultset;
    }
    */
    
    public function insert($values) 
    {
        $this->resultset['response']['inscription'] = array();
        
        if (!empty($values))
        {
            try
            {
                // Connection à la base de données
                $this->connectDB();
                
                $request = $this->createRequestString("insert", $values, "inscription");
                
                // Création de l'appel à la requête préparée
                $this->prepareStatement($request);

                // Execution de la requête préparée
                $this->executeStatement();

                // On récupère l'id généré par l'insertion
                $this->resultset['response']['inscription']['last_insert_id'] = $this->getLastInsertId();

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
    
}

?>
