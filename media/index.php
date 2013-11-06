<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" media="all" href="css/layout.css">

<link rel="stylesheet" href="css/hot-sneaks/jquery-ui-1.10.3.custom.css">
<link rel="stylesheet" href="css/styleliste.css">



<!----------------------------------------------------------------------------->



<title>Login - Positionnement </title>
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<div id="content">
<div id="titre-page">
      <div id="logo"></div>
      <div id="posi-titre"><div id="txt-titre">Positionnement Dalia</div></div>
</div>

<form action="action.php" method="post" id="formulaire" name="formulaire">
<div id="organisme">
  <div id="zone-formu">
      <div id="ico-orga">Organisme</div>
        <div class="formu">
          <div class="input"> <label for="code_orga">Code organisme <div class="asterix">*</div></label><input type="text" name="code_orga" id="code_orga" value="" required title="Entrer votre code organisme" /></div>
          <div class="input"> <label for="datepicker">Date <div class="asterix">*</div></label><input type="text" name="datepicker" id="datepicker"   title="Veuillez entrer la date" value="" required /></div>
          <fieldset id="main-form" >
          <label for="organismes">Organismes <div class="asterix">*</div></label>
          <select id="organismes" name="organismes" required>
            <option value="">---</option>
            <option value="aaaaa">aaaaa</option>
            <option value="bbbbb">bbbbb</option>
            <option value="autre">autre</option>
          </select>
        </fieldset>
     
        <fieldset id="second-form">
          <label for="organisme_non_dispo">Veuillez entrer votre organisme <div class="asterix">*</div></label> <input id="organisme_non_dispo" name="organisme_non_dispo" type="text" value="" required />
          <div class="input"><label for="code_postal">Code postal <div class="asterix">*</div></label><input type="tel" name="code_postal" id="code_postal" pattern="[0-9]{5}"   value="" title="Ex:76000" required  /></div>
          <div class="input"><label for="telephone">Téléphone <div class="asterix">*</div></label><input type="tel" name="telephone" id="telephone" pattern="[0-9]{10}" value="" required /></div>
        </fieldset>
          <div class="input"><label for="email">EMail <div class="asterix">*</div></label><input type="email" name="Email" id="Email"   value="" required title="Format Email requis(exemple@xxx.yy)" /></div>
          
        </div> 
    </div>
</div>
<div id="utilisateur">
  <div id="zone-formu">
      <div id="ico-utili">Utilisateur</div>
        <div class="formu">
          <div class="input"><label for="nom">Nom <div class="asterix">*</div></label><input type="text" name="nom" id="nom"   value="" required /></div>
          <div class="input"><label for="prenom">Prénom <div class="asterix">*</div></label><input type="text" name="prenom" id="prenom"   value="" required /></div>
          <div class="input"><label for="datenai">Date de naissance <div class="asterix">*</div></label><input type="text" name="datepicker2" id="datepicker2"   title="Veuillez entrer votre date de naissance" value="" required /></div>
          <label id="niveau" for="niveau_de_formation">Niveau de formation <div class="asterix">*</div></label>
          <select id="niveau" name="niveau" required>
            <option value="Aucun">Aucun</option>
   			<option>BEP/CAP</option>
            <option>BAC</option>
            <option>BAC+2</option>
            <option>BAC+3</option>
            <option>BAC+5</option> 
		  </select>
         
          <div id="submit"><input type="submit" value="Envoyer" onClick="javascript: return verif();" /></div>
       </div> 
     </div>
  </div>
</form>
<div id="footer"><div id"txt-footer">Education et formation 2013</div></div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/jquery-1.10.2.min.js"><\/script>')</script>
 <!-- scripts -->
 <script defer src="js/scriptliste.js"></script>
 <!-- end scripts-->
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery.ui.datepicker-fr.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/modernizr-2.6.2.min.js"></script>
<script>$(function() {$( "#datepicker" ).datepicker();});</script>
<script> $(function() { $( "#datepicker2" ).datepicker({ changeMonth: true, changeYear: true, yearRange: "1953:2014"}); });</script>

<!--------------------Verification des champs formulaire------------------->
<script language="javascript" type="text/javascript">
    function verif() 
    {
      if (window.navigator.appName == 'Microsoft Internet Explorer')
      {
        var code_orga = document.formulaire.code_orga.value;
        if (document.formulaire.code_orga.value == "")
        {
          alert ('Veuillez entrer votre code organisme');
          document.formulaire.code_orga.focus();
          return false;
        }
          
          var datepicker = document.formulaire.datepicker.value;
        if (document.formulaire.datepicker.value == "")
        {
          alert ('Veuillez entrer la date');
          document.formulaire.datepicker.focus();
          return false;
        }
		
		    var Email = document.formulaire.Email.value;
       	if(document.formulaire.Email.value.indexOf('@') == -1) 
		{ 
			alert("Il y a une erreur à votre adresse électronique! format Email requis(exemple@xxx.yy)"); 
			document.formulaire.Email.focus(); 
			return false; 
		} 
 
           var nom = document.formulaire.nom.value;
        if (document.formulaire.nom.value == "")
        {
          alert ('Veuillez entrer votre nom');
          document.formulaire.nom.focus();
          return false;
        }
		 var prenom = document.formulaire.prenom.value;
        if (document.formulaire.prenom.value == "")
        {
          alert ('Veuillez entrer votre prénom');
          document.formulaire.prenom.focus();
          return false;
        }
		var datenai = document.formulaire.datepicker2.value;
        if (document.formulaire.datepicker2.value == "")
        {
          alert ('Veuillez entrer votre date de naissance');
          document.formulaire.datepicker2.focus();
          return false;
        }
		var niveau = document.formulaire.niveau.value;
        if (document.formulaire.niveau.value == "Aucun")
        {
          alert ('Veuillez entrer votre niveau de formation');
          document.formulaire.niveau.focus();
          return false;
        }
		 var organismes = document.formulaire.organismes.value;
        if (document.formulaire.organismes.value == "autre" || document.formulaire.organismes.value == "")
        {
          	var organisme_non_dispo = document.formulaire.organisme_non_dispo.value;
        	if (document.formulaire.organisme_non_dispo.value == "")
       		 {
         	 alert ('Veuillez entrer votre organisme');
         	 document.formulaire.organisme_non_dispo.focus();
         	 return false;
        	}
			if(document.formulaire.code_postal.value.length != 5)
       		 { 
         	 alert ('Le code postal doit comporter 5 chiffres'); 
          	 document.formulaire.code_postal.focus();
         	 return false; 
       		 }
			 if(document.formulaire.telephone.value.length != 10)
       		 { 
         	 alert ('Le n° de téléphone doit comporter 10 chiffres'); 
          	 document.formulaire.telephone.focus();
         	 return false; 
       		 }
        }
        
        
      }
    }
  </script>
  <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->
</body>
</html>
