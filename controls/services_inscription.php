<?php

/**
 * Description of serviceInscriptionOrganisme
 *
 * @author Nicolas Beurion
 */

// Fichiers requis pour le formulaire organisme
require_once(ROOT.'models/dao/organisme_dao.php');
require_once(ROOT.'models/dao/intervenant_dao.php');

// Fichiers requis pour le formulaire utilisateur
require_once(ROOT.'models/dao/niveau_etudes_dao.php');
require_once(ROOT.'models/dao/utilisateur_dao.php');
require_once(ROOT.'models/dao/inscription_dao.php');

require_once(ROOT.'utils/tools.php');
        
        

class ServicesInscription extends Main
{
   
    private $organismeDAO = NULL;
    private $intervenantDAO = NULL;
    private $utilisateurDAO = NULL;
    private $niveauEtudesDAO = NULL;
    private $inscriptionDAO = NULL;
    

    public function __construct()
    {
        $this->errors = array();
        $this->controllerName = "inscription";

        $this->organismeDAO = new OrganismeDAO();
        $this->intervenantDAO = new IntervenantDAO();
        $this->utilisateurDAO = new UtilisateurDAO();
        $this->niveauEtudesDAO = new NiveauEtudesDAO();
        $this->inscriptionDAO = new InscriptionDAO();
    }
    
    
    /**
     * Fournit les données et affiche les formulaires organisme, intervenant et utilisateur.
     * 
     * @param array $requestParams Contient le type de formulaire
     * @param array $returnData Retour des données après validation
     * 
     */
    public function formulaire($requestParams = array(), $returnData = array())
    {
        if (!isset($requestParams) || empty($requestParams))
        {
            $requestParams = array("organisme");
        }
        
        if (empty($returnData))
        {
            $returnData['response'] = array();
            $returnData['response']['errors'] = array();
        }
        
        if (!empty($this->errors) && count($this->errors) > 0)
        {
            foreach($this->errors as $error)
            {
                $returnData['response']['errors'][] = $error;
            }
        }
        
        
        /*--- Aiguillage vers le formulaire organisme ou utilisateur ---*/
        
        if ($requestParams[0] == "organisme")
        {

            // Requete pour obtenir la liste des organismes
            $listeOrganismes = $this->findOrganismes();

            // On récupère les erreurs de la requête s'il y en a
            if (isset($listeOrganismes['response']['errors']) && !empty($listeOrganismes['response']['errors']) && count($this->errors) > 0)
            {
                foreach($listeOrganismes['response']['errors'] as $error)
                {
                    $returnData['response']['errors'][] = $error;
                }
            }
            
            // On récupère toutes les données
            $returnData['response'] = array_merge($listeOrganismes['response'], $returnData['response']);
            
            $this->setResponse($returnData);
            $this->render("form_organisme"); 
        }
        else if ($requestParams[0] == "utilisateur")
        {
            // Requete pour obtenir la liste des niveaux d'études
            $listeNiveauxEtudes = $this->findNiveauxEtudes();

            // On récupère les erreurs de la requête s'il y en a
            if (isset($listeNiveauxEtudes['response']['errors']) && !empty($listeNiveauxEtudes['response']['errors']) && count($this->errors) > 0)
            {
                foreach($listeNiveauxEtudes['response']['errors'] as $error)
                {
                    $returnData['response']['errors'][] = $error;
                }
            }
            
            // On récupère toutes les données
            $returnData['response'] = array_merge($listeNiveauxEtudes['response'], $returnData['response']);

            $this->setResponse($returnData);  
            $this->render('form_utilisateur');
        }
        
    }
    
    
    
    
    
    /** 
     * Valide les formulaires organisme et utilisateur et affiche les formulaires correspondants.
     * 
     * @param array $requestParams Un tableau contenant le type de formulaire à valider(ex : "organisme" ou "utilisateur") et des paramètres.
     * 
     * @return array $returnData Les données validées
     * 
     */
    public function validation($requestParams = array())
    {
        /* Initialisation des variables de validation */
 
        // Données du formulaire en cours de traitement.
        $formData = array();
        
        // Données retournées après validation.
        $returnData = array();
        $returnData['response'] = array();
        $returnData['response']['errors'] = array();
        
        // Toutes les erreurs sont empilées dans une propriété de la superclasse Main.
        $this->errors = array();
        
        /* $requestParams[0] contient le nom du formulaire à valider */
        
        // Dispatch de la validation selon le formulaire à valider
        if (!isset($requestParams) && (empty($requestParams)))
        {
            $requestParams = array("organisme");
        }

        

        
        if ($requestParams[0] == "organisme")
        {

            
            /*------------------------------------------------------------------*/
            /*    Traitements des données reçues par le formulaire organisme    */
            /*------------------------------------------------------------------*/

            
            // Initialisation des tableaux des données à inserer dans la base
            $dataOrganisme = array();
            $dataIntervenant = array();
            
            // Modes de requête du formulaire courant par défaut
            $modeOrganisme = "select";
            $modeIntervenant = "select";
            
            // Initialisation des données qui vont être validées et renvoyées
            $formData['ref_code_organ'] = null;
            $formData['ref_organ'] = null;
            $formData['ref_intervenant'] = null;
            $formData['date_inscription'] = null;
            $formData['code_identification'] = null;
            
            $formData['ref_organ_cbox'] = null;
            $formData['nom_organ'] = null;
            $formData['numero_interne'] = null;
            $formData['adresse_organ'] = null;
            $formData['code_postal_organ'] = null;
            $formData['ville_organ'] = null;
            $formData['tel_organ'] = null;
            $formData['fax_organ'] = null;
            $formData['email_organ'] = null;
            
            $formData['nom_intervenant'] = null;
            $formData['tel_intervenant'] = null;
            $formData['email_intervenant'] = null;
            
            
            /*--- Récupération des champs cachés ---*/
            
            // Récupération du champ "ref_organ" si il existe
            if (isset($_POST['ref_organ']) && !empty($_POST['ref_organ']))
            {
                $formData['ref_organ'] = $_POST['ref_organ'];
            }
            
            // Récupération du champ "ref_intervenant" si il existe
            if (isset($_POST['ref_intervenant']) && !empty($_POST['ref_intervenant']))
            {
                $formData['ref_intervenant'] = $_POST['ref_intervenant'];
            }
            
            // Récupération du champ "date_inscription" si il existe
            if (isset($_POST['date_inscription']) && !empty($_POST['date_inscription']))
            {
                $formData['date_inscription'] = $_POST['date_inscription'];
            }

            
            
            /*--- Authentification du code organisme ---*/
            
            // Récupération du code s'il a été saisi
            if (!isset($_POST['code_identification']) || empty($_POST['code_identification']))
            {
                $this->registerError("form_empty", "Aucun code organisme n'a été saisi");
            }
            else 
            {
                $formData['code_identification'] = $_POST['code_identification'];
                
                // Vérification du code organisme
                $codeOrganisme = $this->authenticateOrganisme($formData['code_identification']);
                
                if (!empty($codeOrganisme))
                {
                    if ($codeOrganisme['authentification'] && !empty($codeOrganisme['code_organisme'])  && !empty($codeOrganisme['ref_code_organisme'])) 
                    {
                        $formData['ref_code_organ'] = $codeOrganisme['ref_code_organisme'];
                    }
                    else 
                    {
                        $this->registerError("form_data", "Le code organisme n'est pas valide");
                    }
                    
                }

                // On supprime le code organisme par sécurité
                unset($codeOrganisme);
            }
            
            
            
            /*--- Prétraitement de la valeur de la liste(combo-box) et de la saisie du nom de l'organisme ---*/
            
            // Récupération du nom de l'organisme s'il a été correctement sélectionné ou saisi
            if (!empty($_POST['ref_organ_cbox']))
            {
                $formData['ref_organ_cbox'] = $_POST['ref_organ_cbox'];
                        
                if ($_POST['ref_organ_cbox'] == "select_cbox")
                {
                    // Aucun nom n'a été sélectionné ou saisi : erreur
                    $this->registerError("form_empty", "Aucun nom d'organisme n'a été sélectionné");
                }
                else if ($_POST['ref_organ_cbox'] == "new")
                {
                    // Un nom a été saisi, il faut donc inserer les données de l'organisme
                    $formData['nom_organ'] = $_POST['nom_organ'];
                    $modeOrganisme = "insert";
                }
                else 
                {
                    // Un nom a été sélectionné dans la liste
                    $formData['ref_organ'] = $_POST['ref_organ_cbox'];
                    $modeOrganisme = "select";
                }
            }
            
            
            
            /*--- Validation globale de tous les champs de l'organisme pour l'insertion dans la base ---*/
            
            if ($modeOrganisme == "insert")
            {
                // Initialisation de toutes les valeurs par defaut (sauf le nom)
                
                // Tableau des champs a traiter et leurs propriétés
                $fields = array(
                    array('type' => "string", 'value' => $_POST['nom_organ'], 'message' => "le nom de l'organisme", 'name' => "nom_organ",'required' => TRUE,'min_length' => 0,'max_length' => 0),
                    array('type' => "integer", 'value' => $_POST['code_postal_organ'], 'message' => "le code postal de l'organisme", 'name' => "code_postal_organ",'required' => TRUE,'min_length' => 5,'max_length' => 5),
                    array('type' => "integer", 'value' => $_POST['tel_organ'], 'message' => "le telephone de l'organisme", 'name' => "tel_organ",'required' => TRUE,'min_length' => 10,'max_length' => 10));
                
                $validateFieldsList = $this->validateFormValues($fields);

                foreach ($validateFieldsList as $validateValue)
                {
                    if (!empty($validateValue['value']) && !empty($validateValue['name']))
                    {
                        $formData[$validateValue['name']] = $validateValue['value'];
                    }
                    else 
                    {
                         $this->registerError("global", "Erreur interne");
                    }
                }
                
                
                /*--- Traitement particulier du nom de l'organisme pour l'insertion ---*/

                // Si le nom de l'organisme n'est pas vide, il a été saisi et il doit être comparé aux autres noms d'organisme
                if (!empty($formData['nom_organ']) && empty($formData['ref_organ']))
                {
                    // Selection du nom de l'organisme dans la base
                    $nomOrganisme = $this->findOrganisme('nom_organ', $formData['nom_organ']);
                    
                    // Si la requête trouve un nom d'organisme correspondant, c'est un doublon !
                    if (!empty($nomOrganisme['response']['organisme']))
                    {
                        $this->registerError("form_data", "Le nom de l'organisme existe déjà");
                    }
                }
                else 
                {
                    $this->registerError("form_empty", "Aucun nom n'a été saisi");
                }

                
                /*--- Valeurs finales des champs qui seront inserés dans la table organisme ---*/
                
                // Valeurs impératives
                $dataOrganisme['ref_code_organ'] = $formData['ref_code_organ'];
                $dataOrganisme['nom_organ'] = $formData['nom_organ']; 
                $dataOrganisme['code_postal_organ'] = $formData['code_postal_organ'];
                $dataOrganisme['tel_organ'] = $formData['tel_organ'];
                
                // Valeurs optionnelles (non-implémenté)
                // (à mettre en commentaire si on ne s'en sert pas !)
                //$dataOrganisme['numero_interne'] = $formData['numero_interne'];
                //$dataOrganisme['adresse_organ'] = $formData['adresse_organ'];
                //$dataOrganisme['ville_organ'] = $formData['ville_organ'];
                //$dataOrganisme['fax_organ'] = $formData['fax_organ'];
                //$dataOrganisme['email_organ'] = $formData['email_organ'];
            }
            
            
            
            /*--- Validation globale de tous les champs de l'intervenant et de la date d'inscription ---*/
            

            /*--- Traitement des champs de l'intervenant ---*/

            $fields = array(
                //array('type' => "string", 'value' => $_POST['nom_intervenant'], 'message' => "le nom de l'intervenant", 'name' => "nom_intervenant", 'required' => false, 'min_length' => 0, 'max_length' => 0),
                //array('type' => "integer", 'value' => $_POST['tel_intervenant'], 'message' => "le numero de téléphone de l'intervenant", 'name' => "tel_intervenant", 'required' => false, 'min_length' => 10, 'max_length' => 10), 
                array('type' => "email", 'value' => $_POST['email_intervenant'], 'message' => "l'email de l'intervenant", 'name' => "email_intervenant", 'required' => TRUE, 'min_length' => 0, 'max_length' => 0),
                array('type' => "date", 'value' => $_POST['date_inscription'], 'message' => "la date d'inscription", 'name' => "date_inscription", 'required' => TRUE, 'min_length' => 6, 'max_length' => 10));

            $validateFieldsList = $this->validateFormValues($fields);

            foreach($validateFieldsList as $validateValue)
            {
                if (!empty($validateValue['value']) && !empty($validateValue['name']))
                {
                    $formData[$validateValue['name']] = $validateValue['value'];
                }
                else 
                {
                     $this->registerError("global", "Erreur interne");
                }
            }
            
            
            
            /*--- Traitement de doublon de l'email de l'intervenant ---*/
             
            // Si l'email de l'intervenant existe déja pour cet organisme, on change de mode pour une mise à jour
            $request = $this->findIntervenant("email_intervenant", $formData['email_intervenant']);
            if (isset($request['response']['intervenant']) && !empty($request['response']['intervenant']))
            {
                $modeIntervenant = "update";
            }
            else 
            {
                $modeIntervenant = "insert";
            }

            
            /*--- Valeurs finales des champs qui seront inserés dans la table intervenant ---*/
            
            $dataIntervenant['email_intervenant'] = $formData['email_intervenant'];
            //$dataIntervenant['nom_intervenant'] = $formData['nom_intervenant'];
            //$dataIntervenant['tel_intervenant'] = $formData['tel_intervenant'];
            

            /*--- Insertion de l'organisme ---*/
            
            if ($modeOrganisme == "insert")
            {
                // Tous les champs obligatoires de l'organisme doivent être remplis
                if (!empty($formData['code_identification']) && (!empty($formData['nom_organ']) && empty($formData['ref_organ'])) && !empty($formData['code_postal_organ']))
                {
                    // Et s'il n'y a aucune erreur
                    if (empty($this->errors)) 
                    {
                        // Requête d'insertion de l'organisme
                        $resultsetOrganisme = $this->addOrganisme($dataOrganisme, $modeOrganisme);

                        // si la requête d'insertion est correcte, on récupére l'id de l'organisme inseré
                        if (!empty($resultsetOrganisme['response']['organisme']['last_insert_id']))
                        { 
                            $formData['ref_organ'] = $resultsetOrganisme['response']['organisme']['last_insert_id'];
                            // On fusionne l'id de l'organisme aves les données traitées du formulaire
                            $returnData['response'] = array_merge($resultsetOrganisme['response'], $returnData['response']);
                        }
                    }
                }
                else 
                {
                    $this->registerError("form_valid", "Des données du formulaire sont absentes");
                }
            }
            

            /*--- Insertion ou mise à jour de l'intervenant ---*/

            // L'email de l'intervenant est obligatoire pour l'insertion de l'intervenant
            if (!empty($formData['email_intervenant']))
            {
                // Et s'il n'y a aucune erreur
                if (empty($this->errors))
                {
                    // On a besoin de la référence organisme liée à l'intervenant
                    if (isset($formData['ref_organ']) && !empty($formData['ref_organ']))
                    {
                        $dataIntervenant['ref_organ'] = $formData['ref_organ'];
                        
                        //var_dump($dataIntervenant);
                        
                        if ($modeIntervenant == "insert")
                        {
                            // Mise à jour ou insertion de l'intervenant dans la base selon le mode
                            $resultsetIntervenant = $this->addIntervenant($dataIntervenant);

                            // si la requête d'insertion est correcte, on récupére l'id de l'insertion
                            if (!empty($resultsetIntervenant['response']['intervenant']['last_insert_id']))
                            {
                                $formData['ref_intervenant'] = $resultsetIntervenant['response']['intervenant']['last_insert_id'];
                                // On fusionne le résultat de la requête aves les données traitées du formulaire
                                $returnData['response'] = array_merge($resultsetIntervenant['response'], $returnData['response']);
                            }
                        }
                        else if ($modeIntervenant == "update")
                        {
                            // Mise à jour ou insertion de l'intervenant dans la base selon le mode
                            $resultsetIntervenant = $this->changeIntervenant($dataIntervenant);

                            // si la requête d'insertion est correcte, on récupére l'id de l'insertion
                            if (!empty($resultsetIntervenant['response']['intervenant']['id_intervenant']))
                            {
                                $formData['ref_intervenant'] = $resultsetIntervenant['response']['intervenant']['id_intervenant'];
                                // On fusionne le résultat de la requête aves les données traitées du formulaire
                                $returnData['response'] = array_merge($resultsetIntervenant['response'], $returnData['response']);
                            }
                        }
                    }
                }
            }
            else 
            {
                $this->registerError("form_valid", "Des données du formulaire sont absentes");
            }

            
            
        }
        else if ($requestParams[0] == "utilisateur")
        {
            
            
            /*--------------------------------------------------------------------*/
            /*    Traitements des données reçues par le formulaire utilisateur    */
            /*--------------------------------------------------------------------*/
            
            // Initialisation des tableaux des données à inserer dans la base
            $dataUtilisateur = array();
            $dataInscription = array();
            
            
            // Modes de requête du formulaire courant par défaut
            $modeUtilisateur = "insert";
            $modeInscription = "insert";
            
            
            // Initialisation des données qui vont être validées
            $formData['ref_user'] = null;
            $formData['ref_intervenant'] = null;
            $formData['date_inscription'] = null;
            
            $formData['ref_niveau'] = null;
            $formData['ref_niveau_cbox'] = null;
            $formData['nom_user'] = null;
            $formData['prenom_user'] = null;
            $formData['date_naiss_user'] = null;
            $formData['adresse_user'] = null;
            $formData['code_postal_user'] = null;
            $formData['ville_user'] = null;
            $formData['email_user'] = null;
            
            
            
            // Récupération du champ caché "ref_intervenant" si il existe
            if (isset($_POST['ref_user']) && !empty($_POST['ref_user']))
            {
                $formData['ref_user'] = $_POST['ref_user'];
            }
            
            // Récupération du champ caché "ref_intervenant" si il existe
            if (isset($_POST['ref_intervenant']) && !empty($_POST['ref_intervenant']))
            {
                $formData['ref_intervenant'] = $_POST['ref_intervenant'];
            }
                    
            // Récupération du champ caché "date_inscription" si il existe
            if (isset($_POST['date_inscription']) && !empty($_POST['date_inscription']))
            {
                $formData['date_inscription'] = $_POST['date_inscription'];
            }
 
            
            
            /*--- Traitement de la valeur de la liste(combo-box) niveau d'études ---*/
            
            // Récupération de l'id du niveau d'études s'il a été correctement sélectionné ou saisi
            $formData['ref_niveau'] = "";

            if (!empty($_POST['ref_niveau_cbox']))
            {
                $formData['ref_niveau_cbox'] = $_POST['ref_niveau_cbox'];
                        
                if ($_POST['ref_niveau_cbox'] == "select_cbox")
                {
                    // Aucun niveau n'a été sélectionné ou saisi : erreur
                    $this->registerError("form_empty", "Aucun niveau d'études n'a été sélectionné");
                }
                else 
                {
                    // Un niveau a été sélectionné dans la liste
                    $formData['ref_niveau'] = $_POST['ref_niveau_cbox'];
                    //$modeUtilisateur = "select";
                }
            }
            
            
            /*--- Validation globale de tous les champs utilisateur ---*/
            
            // Tableau des champs a traiter et leurs propriétés
            $fields = array(
                array('type' => "string", 'value' => $_POST['nom_user'], 'message' => "le nom de l'utilisateur", 'name' => "nom_user", 'required' => TRUE, 'min_length' => 0, 'max_length' => 0),
                array('type' => "string", 'value' => $_POST['prenom_user'], 'message' => "le prénom de l'utilisateur", 'name' => "prenom_user",'required' => TRUE,'min_length' => 0,'max_length' => 0),
                array('type' => "date",'value' => $_POST['date_naiss_user'], 'message' => "la date de naissance de l'utilisateur", 'name' => "date_naiss_user",'required' => TRUE,'min_length' => 6,'max_length' => 10)
                //array('type' => "string", 'value' => $_POST['adresse_user'], 'message' => "l'adresse de l'utilisateur", 'name' => "adresse_user", 'required' => false, 'min_length' => 0, 'max_length' => 0),
                //array('type' => "integer", 'value' => $_POST['code_postal_user'], 'message' => "le code postal de l'utilisateur", 'name' => "code_postal_user", 'required' => false, 'min_length' => 5, 'max_length' => 5),
                //array('type' => "string",'value' => $_POST['ville_user'], 'message' => "la ville de l'utilisateur", 'name' => "ville_user", 'required' => false, 'min_length' => 0, 'max_length' => 0),
                //array('type' => "email", 'value' => $_POST['email_user'], 'message' => "l'email de l'utilisateur", 'name' => "email_user", 'required' => false, 'min_length' => 0, 'max_length' => 0)
                );

            $validateFieldsList = $this->validateFormValues($fields);

            foreach ($validateFieldsList as $validateValue)
            {
                if (!empty($validateValue['value']) && !empty($validateValue['name']))
                {
                    $formData[$validateValue['name']] = $validateValue['value'];
                }
                else 
                {
                     $this->registerError("global", "Erreur interne");
                }
            }
            
            
            /*--- Traitement d'un éventuel doublon de l'utilisateur ---*/
            
            $duplicateValues = array("nom_user" => $formData['nom_user'], "prenom_user" => $formData['prenom_user'], "date_naiss_user" => Tools::toggleDate($formData['date_naiss_user'], "us"));
            $duplicateUtilisateur = $this->findUtilisateur("duplicate_entry", $duplicateValues);
            
            var_dump($duplicateUtilisateur);
            
            if (empty($duplicateUtilisateur['response']['utilisateur']))
            {
                $modeUtilisateur = "insert";
            }
            else 
            {
                $modeUtilisateur = "update";
            }
            
            
            /* Valeurs par défaut des champs qui seront inserés dans la table organisme */
            
            $dataUtilisateur['ref_niveau'] = $formData['ref_niveau'];
            $dataUtilisateur['nom_user'] = $formData['nom_user'];
            $dataUtilisateur['prenom_user'] = $formData['prenom_user'];
            $dataUtilisateur['date_naiss_user'] = Tools::toggleDate($formData['date_naiss_user'], "us");
            //$dataUtilisateur['adresse_user'] = $formData['adresse_user'];
            //$dataUtilisateur['code_postal_user'] = $formData['code_postal_user'];
            //$dataUtilisateur['ville_user'] = $formData['ville_user'];
            //$dataUtilisateur['email_user'] = $formData['email_user'];
            
            // Tous les champs obligatoires de l'utilisateur doivent être remplis
            if (!empty($dataUtilisateur['ref_niveau']) && !empty($dataUtilisateur['nom_user']) && !empty($dataUtilisateur['prenom_user']) && !empty($dataUtilisateur['date_naiss_user']))
            {
                // S'il n'y a aucune erreur
                if (empty($this->errors)) 
                {
                    if ($modeUtilisateur == "insert")
                    {
                        /*--- Insertion de l'utilisateur dans la base ---*/
                        
                        $requestUtilisateur = $this->addUtilisateur($dataUtilisateur);

                        if (isset($requestUtilisateur['response']['utilisateur']['last_insert_id']) && !empty($requestUtilisateur['response']['utilisateur']['last_insert_id']))
                        {
                            $formData['ref_user'] = $requestUtilisateur['response']['utilisateur']['last_insert_id'];
                            $returnData['response'] = array_merge($requestUtilisateur['response'], $returnData['response']);
                        }
                        else 
                        {
                            $this->registerError("form_insert", "Insertion de l'utilisateur impossible");
                        }
                    }
                }
            }
            else 
            {
                $this->registerError("form_valid", "Des données du formulaire sont absentes");
            }

            
            
            /*--- Validation des infos d'inscription ---*/

            $dataInscription['ref_user'] = $formData['ref_user'];
            $dataInscription['ref_intervenant'] = $formData['ref_intervenant'];
            if (!empty($formData['date_inscription']))
            {
                $dataInscription['date_inscription'] = Tools::toggleDate($formData['date_inscription'], "us");
            }
            
            var_dump($dataInscription);
            
            // Tous les champs obligatoires de l'utilisateur doivent être remplis
            if (!empty($dataInscription['ref_user']) && !empty($dataInscription['ref_intervenant']) && !empty($dataInscription['date_inscription']))
            {
                 // S'il n'y a aucune erreur
                if (empty($this->errors)) 
                {
                    /*--- Insertion de l'inscription dans la base ---*/
                    $requestInscription = $this->addInscription($dataInscription);
                    
                    // Traitement des erreurs eventuelles de l'insertion de l'organisme
                    if (!empty($requestInscription['response']['errors']) && count($requestInscription['response']['errors']) > 0)
                    {
                        foreach($requestInscription['response']['errors'] as $error)
                        {
                            //$this->errors[] = array('type' => $error['type'], 'message' => $error['message']);
                            $this->registerError($error['type'], $error['message']);
                        }
                    }
                    
                    if (!isset($requestInscription['response']['inscription']['last_insert_id']) || empty($requestInscription['response']['inscription']['last_insert_id']))
                    {
                       $this->registerError("form_insert", "Insertion de l'inscription impossible");
                    }
                    else 
                    {
                        $formData['ref_inscription'] = $requestInscription['response']['inscription']['last_insert_id'];
                        $returnData['response'] = array_merge($requestInscription['response'], $returnData['response']);
                    }
                }
            }
            else 
            {
                $this->registerError("form_valid", "L'inscription a été intérrompue");
            }
            
        } 
        else 
        {
            echo "erreur 404????";
        }
        
        
        
        /*--- Retour des données traitées du formulaire ---*/

        $returnData['response']['form_data'] = $formData;

        
        
        
      
        /*-----------------------------------------------------------*/
        /*    Redirection selon le résultat du formulaire courant    */
        /*-----------------------------------------------------------*/


        // S'il y a des erreurs on appelle de nouveau le formulaire (la page n'est pas rechargée)
        if (!empty($this->errors))
        {
            $this->formulaire($requestParams, $returnData);
        }
        else
        {
            // Sinon redirection vers la page suivante (recharge la page)

           if ($requestParams[0] == "organisme")
           {
               // On doit conserver certaines informations pour le formulaire utilisateur
               $returnData['response'] = array();
               $returnData['response']['ref_intervenant'] = $formData['ref_intervenant'];
               $returnData['response']['date_inscription'] = $formData['date_inscription'];
                       
               // Redirection vers le formulaire utilisateur
               $this->formulaire(array("utilisateur"), $returnData);
           }
           else if ($requestParams[0] == "utilisateur")
           {
               // On doit conserver l'id de l'utilisateur pour le positionnement
               $returnData['response'] = array();
               
               // Redirection vers le formulaire utilisateur
               header("Location: ".SERVER_URL."positionnement/intro/");
               //exit;
           }
           else 
           {
               echo "erreur 404????";
           }
        }
  
    }
 
    
    
    
    
    /* requête identification de l'organisme */
    
    private function authenticateOrganisme($codeOrganisme)
    {
        $resultset = $this->organismeDAO->authenticate($codeOrganisme);
        
        // Traitement des erreurs de la requête
        if (!empty($resultset['errors']))
        {
            foreach ($resultset['errors'] as $error)
            {
                $this->registerError($error['type'], $error['message']);
            }
            
            $resultset = false;
        }

        return $resultset;
    }

    
    
    
    
    /* requêtes organisme */
    
    private function findOrganismes()
    {
        $resultset = $this->organismeDAO->selectAll();

        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        return $resultset;
    }
    
    
    private function findOrganisme($filterField, $value)
    {
        switch($filterField) 
        {
            case 'id_organ':
                $resultset = $this->organismeDAO->selectById($value);
                break;
            
            case 'nom_organ':
                $resultset = $this->organismeDAO->selectByName($value);
                break;
            
             case 'code_postal_organ':
                break;
            
            default :
                break;
        }
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        return $resultset;
    }

 
    
    private function addOrganisme($values)
    {
        $resultset = $this->organismeDAO->insert($values);

        // Traitement des erreurs de la requête
        if ($this->filterDataErrors($resultset['response']))
        {
            $resultset = false;
        }
        else if (!isset($resultset['response']['organisme']['last_insert_id']) || empty($resultset['response']['organisme']['last_insert_id']))
        {
           $this->registerError("form_insert", "Insertion de l'organisme impossible");
           $resultset = false;
        }
        
        return $resultset;
    }
    
    private function changeOrganisme($values)
    {
        $resultset = $this->organismeDAO->update($values);

        // Traitement des erreurs de la requête
        if ($this->filterDataErrors($resultset['response']))
        {
            $resultset = false;
        }
        else if (!isset($resultset['response']['organisme']['last_insert_id']) || empty($resultset['response']['organisme']['last_insert_id']))
        {
           $this->registerError("form_insert", "Mise à jour de l'organisme impossible");
           $resultset = false;
        }
        
        return $resultset;
    }
    
    
    
    
    
    
    /* requêtes intervenant */
    
    private function findIntervenants()
    {
        $resultset = $this->intervenantDAO->selectAll();

        // Traitement des erreurs de la requête
        if (!empty($resultset['errors']))
        {
            foreach ($resultset['errors'] as $error)
            {
                $this->registerError($error['type'], $error['message']);
            }
            
            $resultset = false;
        }
        
        return $resultset;
    }
    
    
    private function findIntervenant($filter, $value)
    {
        //echo "\$filter = ".$filter."<br/>";
        //echo "\$value = ".$value."<br/>";
        
        switch($filter) 
        {
            case "id_intervenant":
                $resultset = $this->intervenantDAO->selectById($value);
                break;
            
            case "email_intervenant":
                $resultset = $this->intervenantDAO->selectByEmail($value);
                //var_dump($resultset);
                break;
            
            default :
                break;
        }
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        return $resultset;
    }
    
    
    private function addIntervenant($values)
    {
        $resultset = $this->intervenantDAO->insert($values);

        // Traitement des erreurs de la requête
        if ($this->filterDataErrors($resultset['response']))
        {
             $resultset = false;
        }
        else if (!isset($resultset['response']['intervenant']['last_insert_id']) || empty($resultset['response']['intervenant']['last_insert_id']))
        {
           $this->registerError("form_insert", "Insertion de l'intervenant impossible");
           $resultset = false;
        }
        
        return $resultset;

    }
    
    
    
    private function changeIntervenant($values)
    {
        $resultset = $this->intervenantDAO->update($values);

        // Traitement des erreurs de la requête
        if ($this->filterDataErrors($resultset['response']))
        {
             $resultset = false;
        }
        else 
        {
            $intervenant = $this->intervenantDAO->selectByEmail($values['email_intervenant']);
            
            if ($this->filterDataErrors($resultset['response']))
            {
                 $resultset = false;
            }
            else 
            {
                $resultset['response']['intervenant']['id_intervenant'] = $intervenant['response']['intervenant']->getId();
            }
        }
        
        return $resultset;
    }

    
    
    
    
    /* requêtes niveaux d'études */
    
    private function findNiveauxEtudes()
    {
        $resultset = $this->niveauEtudesDAO->selectAll();
        
        // Traitement des erreurs de la requête
        if (!empty($resultset['errors']))
        {
            foreach ($resultset['errors'] as $error)
            {
                $this->registerError($error['type'], $error['message']);
            }
            $resultset = false;
        }
        
        return $resultset;
    }
   
    
    
    
    
    /* requêtes utilisateur */
    
    private function findUtilisateurs()
    {
        $resultset = $this->utilisateurDAO->selectAll($values);
        
        // Traitement des erreurs de la requête
        if (!empty($resultset['errors']))
        {
            foreach ($resultset['errors'] as $error)
            {
                $this->registerError($error['type'], $error['message']);
            }
            $resultset = false;
        }

        return $resultset;
    }

    
    private function findUtilisateur($filter, $values)
    {
        switch($filter) 
        {
            case 'id_user':
                $resultset = $this->utilisateurDAO->selectById($values['id_user']);
                break;
            
            case 'duplicate_entry':
                $resultset = $this->utilisateurDAO->selectDuplicateUser($values['nom_user'], $values['prenom_user'], $values['date_naiss_user']);
                break;
            
            default :
                break;
        }
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        return $resultset;
    }

    
    private function addUtilisateur($values)
    {
        $resultset = $this->utilisateurDAO->insert($values);

        // Traitement des erreurs de la requête
        if ($this->filterDataErrors($resultset['response']))
        {
             $resultset = false;
        }
        else if (!isset($resultset['response']['utilisateur']['last_insert_id']) || empty($resultset['response']['utilisateur']['last_insert_id']))
        {
           $this->registerError("form_insert", "Insertion de l'utilisateur impossible");
           $resultset = false;
        }
        
        return $resultset;
    }
    
    
    private function changeUtilisateur($values)
    {
        $resultset = $this->utilisateurDAO->update($values);

        // Traitement des erreurs de la requête
        if ($this->filterDataErrors($resultset['response']))
        {
             $resultset = false;
        }
        else if (!isset($resultset['response']['utilisateur']['row_count']) || empty($resultset['response']['utilisateur']['row_count']))
        {
           $this->registerError("form_insert", "Mise à jour de l'utilisateur impossible");
           $resultset = false;
        }
        
        return $resultset;
    }
    
    /*
    private function setUtilisateur($values, $mode)
    {
        if ($mode == 'insert')
        {
            $resultset = $this->utilisateurDAO->insert($values);
        }
        else if ($mode == 'update')
        {
            $resultset = $this->utilisateurDAO->update($values);
        }
        
        // Traitement des erreurs de la requête
        if (!empty($resultset['errors']))
        {
            foreach ($resultset['errors'] as $error)
            {
                $this->registerError($error['type'], $error['message']);
            }
            $resultset = false;
        }

        if ($mode == 'insert' && !isset($resultset['response']['utilisateur']['last_insert_id']) || empty($resultset['response']['utilisateur']['last_insert_id']))
        {
           $this->errors[] = array('type' => "form_insert", 'message' => "Insertion de l'utilisateur impossible");
           $resultset = false;
        }
        
        if ($mode == 'update' && (!isset($resultset['response']['utilisateur']['row_count']) || empty($resultset['response']['utilisateur']['row_count'])))
        {
           $this->errors[] = array('type' => "form_insert", 'message' => "Mise à jour de l'utilisateur impossible");
           $resultset = false;
        }
        
        return $resultset;
    }
     */

    
    
    
    
    /* requêtes inscription*/
    
    
    private function addInscription($values)
    {
        $resultset = $this->inscriptionDAO->insert($values);

        // Traitement des erreurs de la requête
        if ($this->filterDataErrors($resultset['response']))
        {
             $resultset = false;
        }
        else if (!isset($resultset['response']['inscription']['last_insert_id']) || empty($resultset['response']['inscription']['last_insert_id']))
        {
           $this->registerError("form_insert", "Insertion de l'inscription impossible");
           $resultset = false;
        }
        
        return $resultset;
    }
    
    private function changeInscription($values)
    {
        $resultset = $this->inscriptionDAO->update($values);

        // Traitement des erreurs de la requête
        if ($this->filterDataErrors($resultset['response']))
        {
             $resultset = false;
        }
        else if (!isset($resultset['response']['inscription']['row_count']) || empty($resultset['response']['inscription']['row_count']))
        {
           $this->registerError("form_insert", "Mise à jour de l'inscription impossible");
           $resultset = false;
        }
        
        return $resultset;
    }
    
    
    /*
    private function setInscription($values, $mode)
    {
        $resultset = $this->inscriptionDAO->insert($values);
        
        // Traitement des erreurs eventuelles de l'insertion ou mise à jour de l'utilisateur
        if (!empty($resultset['response']['errors']) && count($resultset['response']['errors']) > 0)
        {
            foreach($resultset['response']['errors'] as $error)
            {
                $this->errors[] = array('type' => $error['type'], 'message' => $error['message']);
            }
            $resultset = false;
        }

        if (!isset($resultset['response']['inscription']['last_insert_id']) || empty($resultset['response']['inscription']['last_insert_id']))
        {
           $this->errors[] = array('type' => "form_insert", 'message' => "Insertion de l'inscription impossible");
           $resultset = false;
        }
        
        return $resultset;
    }
    */
    
    
}

?>
