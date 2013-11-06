<?php


/**
 * Description of NiveauEtudesDAO
 *
 * @author Nicolas Beurion
 */

require_once('models/dao/main_dao.php');


class NiveauEtudesDAO extends MainDAO
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
     * @return liste d'objets "niveaux d'études"
     */
    public function selectAll() 
    {
       $this->resultset['response']['niveaux'] = array();

        try
        {  
            // Connection à la base de données
            $this->connectDB();
            
            // Création de l'appel à la requête préparée
            $this->prepareStatement("SELECT * FROM niveau_etudes");

            // Execution de la requête préparée
            $this->executeStatement();
            
            // resultat des organismes trouvées
            if ($this->getRowCount() > 0)
            {
                // Inclusion du fichier de la classe Organisme
                require_once(ROOT.'models/niveau_etudes.php');

                // Pour chaque ligne trouvées, ajout d'un organisme dans la liste
                while ($tabChamps = $this->getStatementFetch())
                {
                    $niveau = new NiveauEtudes(
                            $tabChamps['id_niveau'], 
                            $tabChamps['nom_niveau'],
                            $tabChamps['descript_niveau']);
                    
                    array_push($this->resultset['response']['niveaux'], $niveau);
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
    
}

?>
