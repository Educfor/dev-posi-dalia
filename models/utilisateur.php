<?php

/**
 * Description of utilisateur
 *
 * @author Nicolas Beurion
 * 
 */

class Utilisateur 
{

    private $id = NULL;
    private $refNiveau = NULL;
    private $nom = NULL;
    private $prenom = NULL;
    private $adresse = NULL;
    private $codePostal = NULL;
    private $ville = NULL;
    private $telephone = NULL;
    private $email = NULL;


    public function __construct($idUser, $refNiveau, $nomUser, $prenomUser, $adresseUser = NULL, $codePostalUser = NULL, $villeUser = NULL, $telUser = NULL, $emailUser = NULL) 
    {
        $this->id = $idUser;
        $this->refNiveau = $refNiveau;
        $this->nom = $nomUser;
        $this->prenom = $prenomUser;
        $this->adresse = $adresseUser;
        $this->codePostal = $codePostalUser;
        $this->ville = $villeUser;
        $this->telephone = $telUser;
        $this->email = $emailUser;
    }


    public function getId()
    {
        return $this->id;
    }
    
    public function getRefNiveau()
    {
        return $this->refNiveau;
    }
    
    public function getNom()
    {
        return $this->nom;
    }
    
    public function getPrenom()
    {
        return $this->prenom;
    }
    
    public function getAdresse()
    {
        return $this->adresse;
    }
    
    public function getCodePostal()
    {
        return $this->codePostal;
    }
    
    public function getVille()
    {
        return $this->ville;
    }
    
    public function getTelephone()
    {
        return $this->telephone;
    }
    
    public function getEmail()
    {
        return $this->email;
    }


    public function toString()
    {
        echo $this->nom." ".$this->prenom;
    }
    
}

?>
