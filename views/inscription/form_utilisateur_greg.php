<?php

//echo "form_inscription<br />";

$form_url = WEBROOT."inscription/validation/utilisateur";

//var_dump($organismes);

?>

// Formulaire

<form action="<?php echo $form_url; ?>" method="POST" name="form_utilisateur">


<div id="utilisateur">
  <div id="zone-formu">
      <div id="ico-utili">Utilisateur</div>
        <div class="formu">
        
          <div class="input">
          <label for="nom_user">Nom <div class="asterix">*</div></label>
          <input type="text" name="nom_user" id="nom_user"   value="<?php echo $formData['nom_user']; ?>" required />
          </div>
          
          <div class="input">
          <label for="prenom_user">Prénom <div class="asterix">*</div></label>
          <input type="text" name="prenom_user" id="prenom_user"   value="<?php echo $formData['prenom_user']; ?>" required />
          </div>
          
          <div class="input">
          <label for="date_naiss_user">Date de naissance <div class="asterix">*</div></label>
          <input type="text" name="date_naiss_user" id="date_naiss_user"   title="Veuillez entrer votre date de naissance" value="<?php echo $formData['date_naiss_user']; ?>" required />
          </div>
          
          <label for="niveau_etude">Niveau de formation <div class="asterix">*</div></label>
          <select name="niveau_etude" id="niveau_etude_cbox">
          <option value="select_cbox">Selectionnez votre organisme</option>
            
            <?php 
            $selected = "";
            foreach($response['niveau_etude'] as $niveau_etude)
            {
                if (!empty($formData['niveau_etude_cbox']) && $formData['niveau_etude_cbox'] != "select_cbox" && $formData['niveau_etude_cbox'] == $organisme->getId())
                {
                    $selected = "selected";
                }
                echo '<option value="'.$organisme->getId().'" '.$selected.'>'.$organisme->getNom().'</option>';
            }

            $selected = "";
            if (!empty($formData['niveau_etude_cbox']) && $formData['niveau_etude_cbox'] == "new")
            {
                $selected = "selected";
            }
            echo '<option value="new" '.$selected.'>Autre</option>';
            ?>
            
        </select>
         
          <div id="submit"><input type="submit" value="Envoyer" onClick="javascript: return verif();" /></div>
       </div> 
     </div>
  </div>

</form>

<script language="javascript" type="text/javascript">
    function verif() 
    {
      if (window.navigator.appName == 'Microsoft Internet Explorer')
      {  var nom_user = document.formulaire.nom_user.value;
        if (document.formulaire.nom_user.value == "")
        {
          alert ('Veuillez entrer votre nom');
          document.formulaire.nom_user.focus();
          return false;
        }
		 var prenom_user = document.formulaire.prenom_user.value;
        if (document.formulaire.prenom_user.value == "")
        {
          alert ('Veuillez entrer votre prénom');
          document.formulaire.prenom_user.focus();
          return false;
        }
		var date_naiss_user = document.formulaire.date_naiss_user.value;
        if (document.formulaire.date_naiss_user.value == "")
        {
          alert ('Veuillez entrer votre date de naissance');
          document.formulaire.date_naiss_user.focus();
          return false;
        }
		var niveau_etude = document.formulaire.niveau_etude.value;
        if (document.formulaire.niveau_etude.value == "Aucun")
        {
          alert ('Veuillez entrer votre niveau de formation');
          document.formulaire.niveau_etude.focus();
          return false;
        }
	  }
	} 
</script>