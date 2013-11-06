<?php


/**
 * Description of intervenant
 *
 * @author Nicolas Beurion
 */


class Intervenant
{
    
    private $id = NULL;
    private $refOrganisme = NULL;
    private $nom = NULL;
    private $telephone = NULL;
    private $email = NULL;
    
    
    public function __construct($idIntervenant, $refOrgan, $emailIntervenant, $nomIntervenant = "", $telIntervenant = "") 
    {
        $this->id = $idIntervenant;
        $this->refOrganisme = $refOrgan;
        $this->nom = $nomIntervenant;
        $this->telephone = $telIntervenant;
        $this->email = $emailIntervenant;
    }
    
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getRefOrganisme()
    {
        return $this->refOrganisme;
    }
    
    public function getNom()
    {
        return $this->nom;
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
        echo $this->nom;
    }
    
}

?>
