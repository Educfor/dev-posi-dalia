<?php

/*
echo "POST = ";
var_dump($_POST);
*/


// url vers laquel doit pointer le formulaire
$form_url = WEBROOT."inscription/validation/organisme";


// Pour connaitre le contenu du tableau $response

echo "\$response => ";
var_dump($response);



/*--- affichage des erreurs (à mettre dans une div au dessus du formulaire) ---*/
/*
if (isset($response['errors']) && !empty($response['errors']))
{
    foreach($response['errors'] as $error)
    {
        echo "erreur type => ".$error['type']."<br />";
        echo "message => ".$error['message']."<br />";
        echo "<br />";
    }
}
*/
/*
if (isset($response['form_data']) && !empty($response['form_data']))
{
    echo "\$response['form_data'] => ";
    var_dump($response['form_data']);
}
*/

// Initialisation par défaut des valeurs du formulaire
$formData = array();
$formData['ref_organ'] = "";
/* new */
$formData['ref_intervenant'] = "";

$formData['code_identification'] = "";
$formData['nom_organ_cbox'] = "";
$formData['nom_organ'] = "";
$formData['adresse_organ'] = "";
$formData['code_postal_organ'] = "";
$formData['ville_organ'] = "";
$formData['tel_organ'] = "";
$formData['fax_organ'] = "";
$formData['email_organ'] = "";
$formData['numero_interne'] = "";

$formData['date_inscript'] = "";
$formData['nom_intervenant'] = "";
$formData['email_intervenant'] = "";
$formData['tel_intervenant'] = "";
 

// S'il y a des valeurs déjà existantes pour le formulaire, on remplace les valeurs par défaut par ces valeurs
if (isset($response['form_data']) && !empty($response['form_data']))
{
    echo "\$response['form_data'] => ";
    var_dump($response['form_data']);

    foreach($response['form_data'] as $key => $value)
    {
        $formData[$key] = $value;
    }
    
}




?>



<!-- Formulaire organisme et intervenant -->

<form action="<?php echo $form_url; ?>" method="POST" name="form_organisme" id="form_organisme">


    <h2>Organisme</h2>
    
    <!-- exemple variable token (session)
    <input type="hidden" value="hnk9oSWHGamyofGgDve4NR7F1HZfY44FF5sYBjDanZA=" name="token">
    -->
    <input type="hidden" value="<?php echo $formData['ref_organ']; ?>" name="ref_organ">
    <!-- new -->
    <input type="hidden" value="<?php echo $formData['ref_intervenant']; ?>" name="ref_intervenant">
    
    <p>
        <label for="code_identification">Code organisme* : </label>
        <input type="password" value="<?php echo $formData['code_identification']; ?>" name="code_identification" id="code_identification" />
    </p>

    <p>
        <label for="ref_organ_cbox">Nom* : </label>
        <select name="ref_organ_cbox" id="ref_organ_cbox">
            <option value="select_cbox">Selectionnez votre organisme</option>
            
            <?php 
            $selected = "";
            foreach($response['organismes'] as $organisme)
            {
                if (!empty($formData['ref_organ_cbox']) && $formData['ref_organ_cbox'] != "select_cbox" && $formData['ref_organ_cbox'] == $organisme->getId())
                {
                    $selected = "selected";
                }
                echo '<option value="'.$organisme->getId().'" '.$selected.'>'.$organisme->getNom().'</option>';
            }

            $selected = "";
            if (!empty($formData['ref_organ_cbox']) && $formData['ref_organ_cbox'] == "new")
            {
                $selected = "selected";
            }
            echo '<option value="new" '.$selected.'>Autre</option>';
            ?>
            
        </select>
    </p>

    <p>
        <label for="nom_organ">Nom* : </label>
        <input type="text" value="<?php echo $formData['nom_organ']; ?>" name="nom_organ" id="nom_organ" />
    </p>
    <!--
    <p>
        <label for="adresse_organ">Adresse : </label>
        <input type="text" value="<?php //echo $formData['adresse_organ']; ?>" name="adresse_organ" id="adresse_organ" />
    </p>
    -->
    <p>
        <label for="code_postal_organ">Code postal* : </label>
        <input type="text" value="<?php echo $formData['code_postal_organ']; ?>" name="code_postal_organ" id="code_postal_organ" />
    </p>
    <!--
    <p>
        <label for="ville_organ">Ville : </label>
        <input type="text" value="<?php //echo $formData['ville_organ']; ?>" name="ville_organ" id="ville_organ" />
    </p>
    -->
    <p>
        <label for="tel_organ">Téléphone : </label>
        <input type="text" value="<?php echo $formData['tel_organ']; ?>" name="tel_organ" id="tel_organ" />
    </p>
    <!--
    <p>
        <label for="fax_organ">Fax : </label>
        <input type="text" value="<?php //echo $formData['fax_organ']; ?>" name="fax_organ" id="fax_organ" />
    </p>
    <p>
        <label for="email_organ">Email : </label>
        <input type="text" value="<?php //echo $formData['email_organ']; ?>" name="email_organ" id="email_organ" />
    </p>
    <p>
        <label for="numero_interne">Numéro interne de l'organisme : </label>
        <input type="text" value="<?php //echo $formData['numero_interne']; ?>" name="numero_interne" id="numero_interne" />
    </p>
    -->

    <h3>Information sur votre session</h3>
    <p>
        <label for="date_inscript">Date : </label>
        <input type="text" value="<?php echo $formData['date_inscript']; ?>" name="date_inscript" id="date_inscript" />
    </p>
    <!--
    <p>
        <label for="nom_intervenant">Votre nom : </label>
        <input type="text" value="<?php //echo $formData['email_intervenant']; ?>" name="nom_intervenant" id="nom_intervenant" />
    </p>
    -->
    <p>
        <label for="email_intervenant">Votre email : </label>
        <input type="text" value="<?php echo $formData['email_intervenant']; ?>" name="email_intervenant" id="email_intervenant" />
    </p>
    <!--
    <p>
        <label for="tel_intervenant">Votre téléphone : </label>
        <input type="text" value="<?php //echo $formData['tel_intervenant']; ?>" name="tel_intervenant" id="tel_intervenant" />
    </p>
    -->
    <!-- Validation du formulaire -->
    <p>
        <input type="submit" name="valid_form_organ" value="Ouvrir une session" />
    </p>
    
</form>