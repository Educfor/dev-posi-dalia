<?php


/**
 * Description of intervenant
 *
 * @author Nicolas Beurion
 */


class Inscription
{
    
    private $id = NULL;
    private $refUtilisateur = NULL;
    private $refIntervenant = null;
    private $dateInscription = NULL;
    
    
    public function __construct($idInscript, $refUser, $refInterv, $dateInscript) 
    {
        $this->id = $idInscript;
        $this->refUtilisateur = $refUser;
        $this->refIntervenant = $refInterv;
        $this->dateInscription = $dateInscript;
    }
    
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getRefUtilisateur()
    {
        return $this->refUtilisateur;
    }
    
    public function getRefIntervenant()
    {
        return $this->refIntervenant;
    }
    
    public function getDateInscription()
    {
        return $this->dateInscription;
    }

    
    
}

?>
