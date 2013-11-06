<?php


/**
 * Description de organisme
 *
 * @author Nicolas Beurion
 */


class Organisme 
{
    
    private $id = NULL;
    private $refCode = NULL;
    private $numeroInterne = NULL;
    private $nom = NULL;
    private $adresse = NULL;
    private $codePostal = NULL;
    private $ville = NULL;
    private $telephone = NULL;
    private $fax = NULL;
    private $email = NULL;


    public function __construct($idOrgan, $refCodeOrgan, $nomOrgan, $codePostalOrgan, $adresseOrgan = "", $villeOrgan = "", $telOrgan = "", $faxOrgan = "", $emailOrgan = "", $numInterne="") 
    {
        $this->id = $idOrgan;
        $this->refCode = $refCodeOrgan;
        $this->numeroInterne = $numInterne;
        $this->nom = $nomOrgan;
        $this->adresse = $adresseOrgan;
        $this->codePostal = $codePostalOrgan;
        $this->ville = $villeOrgan;
        $this->telephone = $telOrgan;
        $this->fax = $faxOrgan;
        $this->email = $emailOrgan;
    }


    public function getId()
    {
        return $this->id;
    }
    
    public function getRefCode()
    {
        return $this->refCode;
    }
    
    public function getNumeroInterne()
    {
        return $this->numeroInterne;
    }
    
    public function getNom()
    {
        return $this->nom;
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
    
    public function getFax()
    {
        return $this->fax;
    }
    
    public function getEmail()
    {
        return $this->email;
    }


    public function toString()
    {
        echo $this->nom;
    }
    
}

?>
