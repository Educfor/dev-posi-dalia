<?php


/**
 * Description of intervenant
 *
 * @author Nicolas Beurion
 */

class NiveauEtudes
{
    
    private $id = NULL;
    private $nom = NULL;
    private $description = NULL;
    
    
    public function __construct($idNiveau, $nomNiveau, $descriptionNiveau = "") 
    {
        $this->id = $idNiveau;
        $this->nom = $nomNiveau;
        $this->description = $descriptionNiveau;
    }
    
    
    public function getId()
    {
        return $this->id;
    }
  
    public function getNom()
    {
        return $this->nom;
    }

    public function getDescription()
    {
        return $this->description;
    }
    
    
    public function toString()
    {
        echo $this->nom." : ".$this->description;
    }
    
}

?>
