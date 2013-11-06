
<?php

include('pbo_connect_db.php');


class RubriqueDAO
{

	private $dbConnect = null;

	
	public function selectAll() 
	{
		// Retourne une liste complete d'objets rubriques
		
		$rubriques = array();
		$rubriques['liste'] = null;
		$rubriques['erreurs'] = null;
		
		try
		{
			// Connection � la base de donn�es
			$dbConnect = new PDOConnectDB();
			$dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// Cr�ation de la l'appel � la proc�dure stock�es et ex�cution
			$callStatement = $dbConnect->prepare("CALL selectRubriques(@retour, @foundrows)");
			$callStatement->execute();
			
			// resultat des rubriques trouv�es
			
			if ($callStatement->rowCount() > 0)
			{
				// Inclusion du fichier de classe Rubrique
				include ('../model/Rubrique.php');
				
				// Pour chaque ligne trouv�es, ajout d'une Rubrique dans la liste
				while ($tabChamps = $callStatement->fetch())
				{
					$nId = $tabChamps['id_rubrique'];
					$sLibelle = $tabChamps['libelle'];
					$sIcone = null;
					$bAffichable = false;
					if ((isset($tabChamps['icone'])) && (!empty($tabChamps['icone'])))
					{
						$sIcone = $tabChamps['icone'];
					}
					if ((isset($tabChamps['affichable'])) && (!empty($tabChamps['affichable'])))
					{
						$bAffichable = $tabChamps['affichable'];
					}
					
					$rubrique = new Rubrique($nId, $sLibelle, $sIcone, $bAffichable);
					$rubriques['liste'][] = $rubrique;
					
				}
			}
			
			// Fermeture de la requ�te pr�par�e
			$callStatement->closeCursor();
			$callStatement = null;
		
			// Requete pour les valeurs de retour de la proc�dure stock�e
			$callStatement = $dbConnect->query("SELECT @retour, @foundrows");
		
			$retour_statmt = $callStatement->fetch();
			$rubriques['code_retour'] = $retour_statmt[0];
			$rubriques['nbre_lignes'] = $retour_statmt[1];
			
			
			// Gestion du code de retour de la proc�dure stock�es et cr�ation des messages d'erreurs en cas d'echec
			
			switch($rubriques['code_retour'])
			{
				case 0 :
					if ($rubriques['nbre_lignes'] == 0)
					{
						$rubriques['erreurs'][]['type'] = "no_resultset";
						$rubriques['erreurs'][]['message'] = "Il n'existe aucune rubrique.";
						$rubriques['liste'] = null;
					}
					break;
				case 1317 :
					$rubriques['erreurs'][]['type'] = "sql";
					$rubriques['erreurs'][]['message'] = "La requ�te a �t� interrompue.";
					$rubriques['liste'] = null;
					break;
				case 10000 :
					$rubriques['erreurs'][]['type'] = "sql";
					$rubriques['erreurs'][]['message'] = "Exception SQL non g�r�e.";
					$rubriques['liste'] = null;
					break;
				default :
					break;
			}
			
			// Fermeture de la requ�te pr�par�e et fermeture de la connection
			$callStatement->closeCursor();
			$callStatement = null;
			$dbConnect = null;
		} 
		catch (PDOException $e)
		{
			// Erreur de connection ou probleme avec la cr�ation de la requ�te pr�par�e
			$rubriques['erreurs'][]['type'] = "pdo_exception";
			$rubriques['erreurs'][]['message'] = $e->getMessage() . ".";
			//exit();
		}
		
		// Retourne un tableau index� avec pour chaque ligne un tableau de champs d'une rubrique
		// Retourne null si aucune rubrique n'a �t� trouv�e
		// Renvoi egalement les erreurs qui se sont produites
		return $rubriques;
		
	}
	
	
	public function insert($libelle, $icone, $affichable) // Retourne la liste complete des rubriques
	{
		$tab_rubriques = null;
		
		try
		{
			// Connection � la base de donn�es
			$dbConnect = new PDOConnectDB();
			$dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// Cr�ation de la l'appel � la proc�dure stock�es et ex�cution
			$callStatement = $dbConnect->prepare("CALL insertRubrique(?, ?, ?, @retour, @insertid, @rowcount)");
			$callStatement->bindParam(1, $libelle, PDO::PARAM_STR);
			$callStatement->bindParam(2, $icone, PDO::PARAM_STR);
			$callStatement->bindParam(3, $affichable, PDO::PARAM_INT);
			$callStatement->execute();
		
			// Fermeture de la requ�te pr�par�e
			$callStatement->closeCursor();
			$callStatement = null;
		
			// Requete pour les valeurs de retour de la proc�dure stock�e
			$callStatement = $dbConnect->query("SELECT @retour, @insertid, @rowcount");
		
			$retour_statmt = $callStatement->fetch();
			$code_retour = $retour_statmt[0];
			$insert_id = $retour_statmt[1];
			$row_count = $retour_statmt[2];
		
			// Gestion du code de retour de la proc�dure stock�es
			switch($code_retour)
			{
				case 0 :
					if ($row_count == -1)
					{
						echo "les champs \"libelle\", \"icone\" ou \"affichable\" sont incorrects.<br/>";
					}
					break;
				case 1062 :
					echo "Le libell� de la rubrique \"" . $libelle . "\" existe d�j�.<br/>";
					break;
				case 1317 :
					echo "Erreur SQL : la requ�te a �t� interrompue.<br/>";
					break;
				case 10000 :
					echo "Erreur SQL : Exception SQL non g�r�e.<br/>";
					break;
				case 20000 :
					echo "Erreur de saisie des champs \"libelle\", \"icone\" ou \"affichable\".<br/>";
					break;
				default :
					break;
			}
			
			// Fermeture de la requ�te pr�par�e et fermeture de la connection
			$callStatement->closeCursor();
			$callStatement = null;
			$dbConnect = null;
		} 
		catch (PDOException $e)
		{
			// Erreur de connection ou probleme avec la cr�ation de la requ�te pr�par�e
			echo "Erreur ! => " . $e->getMessage() . "<br/>";
			die();
		}
		
		// Retourne l'id g�n�r�e par l'insertion d'une rubrique si elle a fonctionn�e
		return $insert_id;

	}
	
	
	/*
	public function insert($rubrique) // Retourne l'id de la rubrique g�n�r� par l'insertion
	{
	
	}
    
	public function int delete($rubrique) // Retourne le nombre de ligne rubriques effac�es
	{
	
	}
    
	public function int update($rubrique) // Retourne le nombre de ligne rubriques mises � jour 
	{
	
	}
	*/
	
}

?>