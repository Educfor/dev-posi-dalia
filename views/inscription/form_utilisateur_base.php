<?php


$form_url = WEBROOT."inscription/validation/utilisateur";


//var_dump($organismes);

// Initialisation par défaut des valeurs du formulaire
$formData = array();
$formData['ref_intervenant'] = "";
$formData['date_inscription'] = "";

$formData['nom_user'] = "";
$formData['prenom_user'] = "";
$formData['date_naiss_user'] = "";
$formData['adresse_user'] = "";
$formData['code_postal_user'] = "";
$formData['ville_user'] = "";
$formData['email_user'] = "";
 

// S'il y a des valeurs déjà existantes pour le formulaire, on remplace les valeurs par défaut par ces valeurs
if (isset($response['form_data']) && !empty($response['form_data']))
{
    foreach($response['form_data'] as $key => $value)
    {
        $formData[$key] = $value;
    }
}

// Affichage des erreurs
if (isset($response['errors']) && !empty($response['errors']))
{	
    foreach($response['errors'] as $error)
    {
        echo '<div id="error">';
            echo "erreur type => ".$error['type']."<br />";
            echo "message => ".$error['message']."<br />";
            echo "<br />";
	echo '</div">';
    }
}

/*
echo "\$response = <br />";
var_dump($response);
*/

?>


<!-- Formulaire -->

<form action="<?php echo $form_url; ?>" method="POST">


    <input type="hidden" value="<?php echo $formData['ref_intervenant']; ?>" name="ref_intervenant">
    <input type="hidden" value="<?php echo $formData['date_inscription']; ?>" name="date_inscription">
    
    <h2>Utilisateur</h2>
    
    <p>
        <label for="nom_user">Nom* : </label>
        <input type="text" value="<?php echo $formData['nom_user']; ?>" name="nom_user" id="nom_user" />
    </p>
    
    <p>
        <label for="prenom_user">Prénom* : </label>
        <input type="text" value="<?php echo $formData['prenom_user']; ?>" name="prenom_user" id="prenom_user" />
    </p>
    
    <p>
        <label for="date_naiss_user">Date de naissance* : </label>
        <input type="text" value="<?php echo $formData['date_naiss_user']; ?>" name="date_naiss_user" id="date_naiss_user" />
    </p>
    
    <p>
        <select name="ref_niveau_cbox" id="ref_organ_cbox">
            <option value="select_cbox">---</option>
            
            <?php 
            $selected = "";
            foreach($response['organismes'] as $organisme)
            {
                if (!empty($formData['ref_niveau_cbox']) && $formData['ref_niveau_cbox'] != "select_cbox" && $formData['ref_niveau_cbox'] == $organisme->getId())
                {
                    $selected = "selected";
                }
                echo '<option value="'.$organisme->getId().'" '.$selected.'>'.$organisme->getNom().'</option>';
            }
            /*
            $selected = "";
            if (!empty($formData['ref_niveau_cbox']) && $formData['ref_niveau_cbox'] == "new")
            {
                $selected = "selected";
            }
            //echo '<option value="new" '.$selected.'>Autre</option>';
            */
            
            ?>
 
        </select>
    </p>
    
    <!--
    <p>
        <label for="adresse_user">Adresse : </label>
        <input type="text" value="<?php echo $formData['adresse_user']; ?>" name="adresse_user" id="nom_organ" />
    </p>
    
    <p>
        <label for="code_postal_user">Code postal : </label>
        <input type="text" value="<?php echo $formData['code_postal_user']; ?>" name="code_postal_user" id="nom_organ" />
    </p>
    
    <p>
        <label for="ville_user">Ville : </label>
        <input type="text" value="<?php echo $formData['ville_user']; ?>" name="prenom_user" id="nom_organ" />
    </p>
    
    <p>
        <label for="email_user">Email : </label>
        <input type="text" value="<?php echo $formData['email_user']; ?>" name="email_user" id="nom_organ" />
    </p>
    -->
    

    <p>
        <input id="valid-form-user" type="submit" value="Inscrire" />
    </p>
</form>