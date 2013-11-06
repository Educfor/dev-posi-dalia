<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" media="all" href="css/Login.css">
<link rel="stylesheet" href="css/jquery-ui.css" />
<link rel="stylesheet" href="css/styleliste.css">

<!----------------------------------------------------------------------------->

<!--------------------Verification des champs formulaire------------------->
<script language="javascript" type="text/javascript">
    function verif() 
    {
      if (window.navigator.appName == 'Microsoft Internet Explorer')
      {
        var login = document.formulaire.login.value;
        if (document.formulaire.login.value == "")
        {
          alert ('Veuillez entrer votre login');
          document.formulaire.login.focus();
          return false;
        }
          
          var mdp = document.formulaire.mdp.value;
        if (document.formulaire.mdp.value == "")
        {
          alert ('Veuillez entrer votre mot de passe');
          document.formulaire.mdp.focus();
          return false;
        }
		  
        
      }
    }
  </script>


<title>Login administration - Positionnement </title>
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<div id="content">
<div id="titre-page">
      <div id="logo"></div>
      <div id="posi-titre-admin"><div id="txt-titre">Positionnement Dalia</div></div>
</div>

<form action="action.php" method="post" id="formulaire" name="formulaire">
  <div id="administrateur-login">
    <div id="zone-formu">
      <div id="ico-utili">Connexion administrateur</div>
        <div class="formu">
          <div class="input"><label for="Login">Login *</label><input type="text" name="login" id="login"   value="" required /></div>
          <div class="input"><label for="prenom">Mot de passe *</label><input type="password" name="password" id="password"   value="" required /></div>

          <div id="submit"><input type="submit" value="Envoyer" onClick="javascript: return verif();" /></div>
       </div> 
     </div>
</div>
</form>
<div id="footer"><div id"txt-footer">Education et formation 2013</div></div>
</div>


  <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->
</body>
</html>
