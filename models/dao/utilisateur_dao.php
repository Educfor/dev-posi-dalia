<?php


/**
 * Description of IntervenantDAO
 *
 * @author Nicolas Beurion
 */

require_once(ROOT.'models/dao/main_dao.php');

// Inclusion du fichier de la classe Utilisateur
require_once(ROOT.'models/utilisateur.php');




class UtilisateurDAO extends MainDAO
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
     * @return liste d'objets "Utilisateur"
     */
    public function selectAll() 
    {
        $this->resultset['response']['utilisateurs'] = array();

        try
        {
            // Connection à la base de données
            $this->connectDB();

            // Création de l'appel à la requête préparée et exécution
            $this->prepareStatement("SELECT * FROM utilisateur");
            
            // Execution de la requête préparée
            $this->executeStatement();
            
            // resultat des organismes trouvées
            if ($this->getRowCount() > 0)
            {
                // Inclusion du fichier de la classe Utilisateur
                //require_once(ROOT.'models/utilisateur.php');
                
                // Pour chaque ligne trouvées, ajout d'un utilisateur dans la liste
                while ($tabChamps = $this->getStatementFetch())
                {
                    $utilisateur = new Utilisateur(
                            $tabChamps['id_user'], 
                            $tabChamps['ref_niveau'],
                            $tabChamps['nom_user'],
                            $tabChamps['prenom_user'],
                            $tabChamps['date_naiss_user'],
                            $tabChamps['adresse_user'],
                            $tabChamps['code_postal_user'],
                            $tabChamps['ville_user'],
                            $tabChamps['email_user'],
                            $tabChamps['nbre_essais_questionnaires'],
                            $tabChamps['nbre_questionnaires_finis']);
                    
                    array_push($this->resultset['response']['utilisateurs'], $utilisateur);
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
    
    
    
    
    
    public function select($nomUser = "", $prenomUser = "", $dateNaissUser = "") 
    {
        $this->resultset['response']['utilisateur'] = array();
        
        if(!empty($nomUser) && !empty($prenomUser) && !empty($dateNaissUser))
        {   
            $nomUser = strtoupper(preg_replace("`(\s|-|_|\/)*`", "", $nomUser));
            $prenomUser = strtoupper(preg_replace("`(\s|-|_|\/)*`", "", $prenomUser));
            
            
            try
            {
                // Connection à la base de données
                $this->connectDB();
                
                 // Création de l'appel à la requête préparée et exécution
                $this->prepareStatement("SELECT * FROM utilisateur 
                                        WHERE REPLACE(UPPER(nom_user),' ','') LIKE '".$nomUser."' 
                                        AND REPLACE(UPPER(prenom_user),' ','') LIKE '".$prenomUser."' 
                                        AND date_naiss_user = '".$dateNaissUser."'");

                // Execution de la requête préparée
                $this->executeStatement();

                // resultat si un utilisateur a été trouvé
                if ($this->getRowCount() > 0)
                {
                    // Inclusion du fichier de la classe Utilisateur
                    //require_once(ROOT.'models/utilisateur.php');
                    
                    // Instanciation de l'utilisateur recherché s'il existe
                    $tabChamps = $this->getStatementFetch();

                    $utilisateur = new Utilisateur(
                        $tabChamps['id_user'], 
                        $tabChamps['ref_niveau'],
                        $tabChamps['nom_user'],
                        $tabChamps['prenom_user'],
                        $tabChamps['date_naiss_user'],
                        $tabChamps['adresse_user'],
                        $tabChamps['code_postal_user'],
                        $tabChamps['ville_user'],
                        $tabChamps['email_user'],
                        $tabChamps['nbre_essais_questionnaires'],
                        $tabChamps['nbre_questionnaires_finis']);

                    $this->resultset['response']['utilisateur'] = $utilisateur;
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
    
    
    
    
    
    public function selectById($idUtilisateur) 
    {
        $this->resultset['response']['utilisateur'] = array();
        
        if(!empty($idUtilisateur))
        {   
            try
            {
                // Connection à la base de données
                $this->connectDB();
                
                 // Création de l'appel à la requête préparée et exécution
                $this->prepareStatement("SELECT * FROM utilisateur WHERE id_user = '".$idUtilisateur."' ");

                // Execution de la requête préparée
                $this->executeStatement();

                // resultat si un utilisateur a été trouvé
                if ($this->getRowCount() > 0)
                {
                    // Inclusion du fichier de la classe Utilisateur
                    //require_once(ROOT.'models/utilisateur.php');
                    
                    // Instanciation de l'utilisateur recherché s'il existe
                    $tabChamps = $this->getStatementFetch();

                    $utilisateur = new Utilisateur(
                        $tabChamps['id_user'], 
                        $tabChamps['ref_niveau'],
                        $tabChamps['nom_user'],
                        $tabChamps['prenom_user'],
                        $tabChamps['date_naiss_user'],
                        $tabChamps['adresse_user'],
                        $tabChamps['code_postal_user'],
                        $tabChamps['ville_user'],
                        $tabChamps['email_user'],
                        $tabChamps['nbre_essais_questionnaires'],
                        $tabChamps['nbre_questionnaires_finis']);

                    $this->resultset['response']['utilisateur'] = $utilisateur;
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
    
    
    
    
    
    public function selectDuplicateUser($nomUtilisateur, $prenomUtilisateur, $dateNaissUtilisateur) 
    {
        $this->resultset['response']['utilisateur'] = array();
        
        if(!empty($nomUtilisateur) && !empty($prenomUtilisateur) && !empty($dateNaissUtilisateur))
        {   
            try
            {
                // Connection à la base de données
                $this->connectDB();
                
                 // Création de l'appel à la requête préparée et exécution
                $this->prepareStatement("SELECT * FROM utilisateur WHERE LOWER(nom_user) = '".strtolower($nomUtilisateur)."' AND LOWER(prenom_user) = '".$dateNaissUtilisateur."' ");

                // Execution de la requête préparée
                $this->executeStatement();

                // resultat si un utilisateur a été trouvé
                if ($this->getRowCount() > 0)
                {
                    // Inclusion du fichier de la classe Utilisateur
                    //require_once(ROOT.'models/utilisateur.php');
                    
                    // Instanciation de l'utilisateur recherché s'il existe
                    $tabChamps = $this->getStatementFetch();

                    $utilisateur = new Utilisateur(
                        $tabChamps['id_user'], 
                        $tabChamps['ref_niveau'],
                        $tabChamps['nom_user'],
                        $tabChamps['prenom_user'],
                        $tabChamps['date_naiss_user'],
                        $tabChamps['adresse_user'],
                        $tabChamps['code_postal_user'],
                        $tabChamps['ville_user'],
                        $tabChamps['email_user'],
                        $tabChamps['nbre_essais_questionnaires'],
                        $tabChamps['nbre_questionnaires_finis']);

                    $this->resultset['response']['utilisateur'] = $utilisateur;
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
        $this->resultset['response']['utilisateur'] = array();

        if (!empty($values))
        {
            try
            {
                // Connection à la base de données
                $this->connectDB();

                // Création de l'appel à la requête préparée
                $this->prepareStatement(
                    "INSERT INTO utilisateur (ref_niveau, nom_user, prenom_user, date_naiss_user, adresse_user, code_postal_user, ville_user, email_user) "
                    ."VALUES ('".$values['ref_niveau']."', '".$values['nom_user']."', '".$values['prenom_user']."', '".$values['date_naiss_user']."', '".$values['adresse_user']."', '".$values['code_postal_user']."', '".$values['ville_user']."', '".$values['email_user']."')");

                // Execution de la requête préparée
                $this->executeStatement();

                $this->resultset['response']['utilisateur']['last_insert_id'] = $this->getLastInsertId();

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
        
        // Retourne l'id générée par l'insertion si elle a fonctionnée
        return $this->resultset;
    }
*/
    
    
    public function insert($values) 
    {
        echo "insert";
        $this->resultset['response']['utilisateur'] = array();
        
        if (!empty($values))
        {
            try
            {
                // Connection à la base de données
                $this->connectDB();
                /*
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

                $request = "INSERT INTO utilisateur (".$fields.") VALUES (".$insertValues.") ";
                */ 
                $request = $this->createRequestString("insert", $values, "utilisateur");
                
                echo $request;
                // Création de l'appel à la requête préparée
                $this->prepareStatement($request);
                    /*
                    "INSERT INTO intervenant (ref_organ, nom_intervenant, tel_intervenant, email_intervenant) "
                    ."VALUES ('".$values['ref_organ']."', '".$values['nom_intervenant']."', '".$values['tel_intervenant']."', '".$values['email_intervenant']."')");
                    */
                
                // Execution de la requête préparée
                $this->executeStatement();

                // On récupère l'id généré par l'insertion
                $this->resultset['response']['utilisateur']['last_insert_id'] = $this->getLastInsertId();

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
        $this->resultset['response']['utilisateur'] = array();
        
        if (!empty($values))
        {
            try
            {
                // Connection à la base de données
                $this->connectDB();

                /*
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

                $request = "UPDATE organisme SET ".$updateString." ";
                */
                $request = $this->createRequestString("update", $values, "utilisateur");
                 
                // Création de l'appel à la requête préparée
                $this->prepareStatement($request);
                    //"UPDATE intervenant "
                    //."SET ref_organ = '".$values['ref_organ']."', nom_intervenant = '".$values['nom_intervenant']."', email_intervenant = '".$values['email_intervenant']."', tel_intervenant = '".$values['tel_intervenant']."' "
                    //."WHERE email_intervenant = '".$values['email_intervenant']."' ");
                 
                // Execution de la requête préparée
                $this->executeStatement();

                // On récupère le nombre de lignes affectées par la mise  à jour
                $this->resultset['response']['utilisateur']['row_count'] = $this->getRowCount();

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
