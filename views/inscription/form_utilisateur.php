<?php


$form_url = WEBROOT."inscription/validation/utilisateur";


// Initialisation par défaut des valeurs du formulaire
$formData = array();
$formData['ref_user'] = "";
$formData['ref_intervenant'] = "";
if (isset($response['ref_intervenant']) && !empty($response['ref_intervenant']))
{ 
    $formData['ref_intervenant'] = $response['ref_intervenant'];
}
$formData['date_inscription'] = "";
if (isset($response['date_inscription']) && !empty($response['date_inscription']))
{ 
    $formData['date_inscription'] = $response['date_inscription'];
}

$formData['ref_niveau_cbox'] = "";
$formData['ref_niveau'] = "";
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

/*
echo "\$response['form_data'] = <br />";
var_dump($response['form_data']);
*/
//echo "date_inscription = ".$formData['date_inscription']."<br />";

// Affichage des erreurs

if (isset($response['errors']) && !empty($response['errors']))
{	
    foreach($response['errors'] as $error)
    {
        //echo '<div id="error">';
            echo "erreur type => ".$error['type']."<br />";
            echo "message => ".$error['message']."<br />";
            echo "<br />";
	//echo '</div">';
    }
}


/*
echo "\$response = <br />";
var_dump($response);
*/

?>


<!-- Formulaire -->

<form action="<?php echo $form_url; ?>" method="POST">

    <input type="hidden" value="<?php echo $formData['ref_user']; ?>" name="ref_user">
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
        <select name="ref_niveau_cbox" id="ref_niveau_cbox">
            <option value="select_cbox">---</option>
            <?php 
            
            foreach($response['niveaux'] as $niveau)
            {
                $selected = "";
                if (!empty($formData['ref_niveau_cbox']) && $formData['ref_niveau_cbox'] != "select_cbox" && $formData['ref_niveau_cbox'] == $niveau->getId())
                {
                    $selected = "selected";
                }
                echo '<option value="'.$niveau->getId().'" title="'.  htmlentities($niveau->getDescription()).'" '.$selected.'>'.$niveau->getNom().'</option>';
            }
            ?>
        </select>
    </p>
    
    <!--
    <p>
        <label for="adresse_user">Adresse : </label>
        <input type="text" value="<?php //echo $formData['adresse_user']; ?>" name="adresse_user" id="nom_organ" />
    </p>
    
    <p>
        <label for="code_postal_user">Code postal : </label>
        <input type="text" value="<?php //echo $formData['code_postal_user']; ?>" name="code_postal_user" id="nom_organ" />
    </p>
    
    <p>
        <label for="ville_user">Ville : </label>
        <input type="text" value="<?php //echo $formData['ville_user']; ?>" name="prenom_user" id="nom_organ" />
    </p>
    
    <p>
        <label for="email_user">Email : </label>
        <input type="text" value="<?php //echo $formData['email_user']; ?>" name="email_user" id="nom_organ" />
    </p>
    -->
    

    <p>
        <input id="valid-form-user" type="submit" value="Inscrire" />
    </p>
</form>