<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * ImportActions
 * @author Hugues
 * @version 1.21.06.12
 * @since 1.21.06.01
 */
class ImportActions extends LocalActions
{

  /**
   * Constructeur
   */
  public function __construct($post=null)
  {
    parent::__construct();
    $this->AnneeScolaireServices = new AnneeScolaireServices();
    $this->DivisionServices = new DivisionServices();
    $this->EnseignantServices = new EnseignantServices();
    $this->EnseignantMatiereServices = new EnseignantMatiereServices();
    $this->MatiereServices = new MatiereServices();
    $this->ProfPrincipalServices = new ProfPrincipalServices();
    $this->post = $post;
  }

  /**
   * @param string $importType
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.06.12
   * @since 1.21.06.01
   */
  public static function dealWithStaticImport($importType, &$notif, &$msg)
  {
    $Act = new ImportActions();
    switch ($importType) {
      case self::PAGE_ADMINISTRATION :
        AdministrationActions::dealWithStatic(self::CST_IMPORT, $params);
        $notif = $params['notif'];
        $msg   = $params['msg'];
      break;
      case self::PAGE_ANNEE_SCOLAIRE :
        AnneeScolaireActions::dealWithStatic(self::CST_IMPORT, $params);
        $notif = $params['notif'];
        $msg   = $params['msg'];
      break;
      case self::PAGE_DIVISION :
        DivisionActions::dealWithStatic(self::CST_IMPORT, $params);
        $notif = $params['notif'];
        $msg   = $params['msg'];
      break;
      case self::PAGE_ELEVE :
        EleveActions::dealWithStatic(self::CST_IMPORT, $params);
        $notif = $params['notif'];
        $msg   = $params['msg'];
      break;
      case self::PAGE_MATIERE :
        MatiereActions::dealWithStatic(self::CST_IMPORT, $params);
        $notif = $params['notif'];
        $msg   = $params['msg'];
      break;
      case self::PAGE_PARENT :
        AdulteActions::dealWithStatic(self::CST_IMPORT, $ids);
        $notif = $params['notif'];
        $msg   = $params['msg'];
      break;


      case self::PAGE_ENSEIGNANT :
        return $Act->importEnseignant($notif, $msg);
      break;
      default :
        return 'Erreur dans ImportActions > dealWithStatic [<strong>'.$actionType.'</strong>] non défini.';
      break;
    }
  }


  public function importEnseignant(&$notif, &$msg, $sep=';')
  {
    $dir_name    = dirname(__FILE__).'/../../web/rsc/csv-files/';
    $file_name   = 'import_'.self::PAGE_ENSEIGNANT.'.csv';
    $fileContent = file_get_contents($dir_name.$file_name);
    $arrContent  = explode("\r\n", $fileContent);
    $firstLine   = true;
    $hasMajorErrors   = false;
    $hasMinorErrors   = false;
    $msgErrors   = array(self::NOTIF_DANGER, self::NOTIF_WARNING=>array(), self::NOTIF_SUCCESS);

    while (!empty($arrContent) && !$hasMajorErrors) {
      $rowContent = array_shift($arrContent);
      list($id, $genre, $nomEnseignant, $prenomEnseignant, $labelMatiere, $division, $anneeScolaire) = explode($sep, $rowContent);
      if ($firstLine) {
        if ($id!='id' || $genre!='genre' || $nomEnseignant!='nomEnseignant' || $prenomEnseignant!='prenomEnseignant' || $labelMatiere!='labelMatiere') {
          if ($anneeScolaire=='' || $division=='') {
            $hasMajorErrors = true;
            $msgErrors[self::NOTIF_DANGER] = 'La première ligne ne correspond pas aux champs attendus : <strong>id;genre;nomEnseignant;prenomEnseignant;labelMatiere</strong>.';
          }
          if ($anneeScolaire!='anneeScolaire' || $division!='division') {
            $hasMajorErrors = true;
            $msgErrors[self::NOTIF_DANGER] = 'La première ligne ne correspond pas aux champs attendus : <strong>id;genre;nomEnseignant;prenomEnseignant;labelMatiere;division;anneeScolaire</strong>.';
          }
        }
        $firstLine = false;
      } else {
        $isInsert = true;
        $division = str_replace("\r\n", '', $division);

        // Contrôle de l'identifiant
        $id = trim($id);
        if ($id!='') {
          $Enseignant = $this->EnseignantServices->selectLocal($id);
          if ($Enseignant->getId()=='') {
            $Enseignant = new Enseignant();
            $Enseignant->setId($id);
          } else {
            $isInsert = false;
          }
        } else {
          $Enseignant = new Enseignant();
          $Enseignant->setId($id);
        }

        // Contrôle du genre
        $genre = trim($genre);
        if ($genre!='Mme' && $genre!='M' && $genre!='') {
          $hasMinorErrors = true;
          $msgErrors[self::NOTIF_WARNING]['genre'] = 'Au moins une ligne a un genre dont la valeur n\'est pas valable. Valeurs attendues : M, Mme ou rien.';
        } else {
          $Enseignant->setGenre(trim($genre));
        }

        // Contrôle du Nom de l'Enseignant
        $nomEnseignant = trim($nomEnseignant);
        if (!$hasMinorErrors) {
          if ($nomEnseignant=='') {
            $msgErrors[self::NOTIF_WARNING]['nom'] = 'Au moins une ligne a un nom non renseigné. Le nom est un champ obligatoire.';
            $hasMinorErrors = true;
          } else {
            $Enseignant->setNomEnseignant($nomEnseignant);
          }
        }

        // Pas de Contrôle du Prénom de l'Enseignant
        $prenomEnseignant = trim($prenomEnseignant);
        if (!$hasMinorErrors) {
          //$Enseignant->setPrenomEnseignant($prenomEnseignant);
        }

        // Contrôle sur la Matière de l'Enseignant
        $labelMatiere = trim($labelMatiere);
        if (!$hasMinorErrors) {
          $arrLabelsMatieres = explode(',', $labelMatiere);
          $arrMatieres = array();
          foreach ($arrLabelsMatieres as $labelMatiere) {
            $Matieres = $this->MatiereServices->getMatieresWithFilters(array(self::FIELD_LABELMATIERE=>$labelMatiere));
            if (empty($Matieres)) {
              $msgErrors[self::NOTIF_WARNING]['matiere'] = 'Au moins une ligne a une Matière qui ne correspond pas ['.$labelMatiere.']. Elle doit être créée ou corrigée.';
            } elseif (count($Matieres)>2) {
              $msgErrors[self::NOTIF_WARNING]['matiere'] = 'Au moins une ligne a une Matière ['.$labelMatiere.'] qui correspond à 2 Matières en base. Merci de vérifier.';
            } else {
              $Matiere = array_shift($Matieres);
              array_push($arrMatieres, $Matiere);
            }
          }
        }

        // Contrôles sur l'Année Scolaire et la Division.
        $anneeScolaire = trim($anneeScolaire);
        $division = trim($division);
        if (!$hasMinorErrors) {
          if ($anneeScolaire=='' && $division!='' || $anneeScolaire!='' && $division=='') {
            // Les champs ne sont pas obligatoires, mais si l'un est renseigné, l'autre doit l'être.
            $msgErrors[self::NOTIF_WARNING]['profprincipal'] = 'Les informations relatives au prof principal doivent être toutes les 2 saisies pour être traitées.';
          } else {
            $AnneeScolaires = $this->AnneeScolaireServices->getAnneeScolairesWithFilters(array(self::FIELD_ANNEESCOLAIRE=>$anneeScolaire));
            if (count($AnneeScolaires)!=1) {
              $msgErrors[self::NOTIF_WARNING]['profprincipal'] .= 'Au moins une Année Scolaire pour le prof principal n\'est pas au bon format (AAAA-AAAA).';
              $AnneeScolaire = null;
            } else {
              $AnneeScolaire = array_shift($AnneeScolaires);
            }
            $Divisions = $this->DivisionServices->getDivisionsWithFilters(array(self::FIELD_LABELDIVISION=>$division));
            if (count($Divisions)!=1) {
              $msgErrors[self::NOTIF_WARNING]['profprincipal'] .= 'Au moins une Division pour le prof principal n\'est pas valide.';
              $Division = null;
            } else {
              $Division = array_shift($Divisions);
            }
          }
        }
        // Chacune des valeurs doit correspondre à

        if (!$hasMinorErrors) {
          // On crée ou met à jour l'Enseignant.
          if ($isInsert) {
            $this->EnseignantServices->insertLocal($Enseignant);
          } else {
            $this->EnseignantServices->updateLocal($Enseignant);
          }

          // On supprime les anciennes Matières rattachées à l'Enseignant et on ajoute les nouvelles
          $EnseignantMatiere = new EnseignantMatiere();
          $EnseignantMatiere->setEnseignantId($Enseignant->getId());
          // On supprime les liens éventuels dans la table de liaison Enseignant-Matiere
          $this->EnseignantMatiereServices->deleteWithFilters(array(self::FIELD_ENSEIGNANT_ID=>$Enseignant->getId()));
          // On ajoute les entrées correspondants dans la table de liaison.
          foreach ($arrMatieres as $Matiere) {
            $EnseignantMatiere->setMatiereId($Matiere->getId());
            $this->EnseignantMatiereServices->insertLocal($EnseignantMatiere);
          }

          // On supprime les anciens rôles de Prof Principal de l'Enseignant et on ajoute le nouveau.
          if (!$hasMinorErros && $AnneeScolaire!=null && $Division!=null) {
            $this->ProfPrincipalServices->deleteWithFilters(array(self::FIELD_ENSEIGNANT_ID=>$Enseignant->getId()));

            $ProfPrincipal = new ProfPrincipal();
            $ProfPrincipal->setAnneeScolaireId($AnneeScolaire->getId());
            $ProfPrincipal->setDivisionId($Division->getId());
            $ProfPrincipal->setEnseignantId($Enseignant->getId());
            $this->ProfPrincipalServices->insertLocal($ProfPrincipal);
          }
        }
      }
    }

    if ($hasMajorErrors) {
      $notif = self::NOTIF_DANGER;
      $msg   = $msgErrors[self::NOTIF_DANGER];
    } elseif ($hasMinorErrors) {
      $notif = self::NOTIF_WARNING;
      $msg   = implode('<br>', $msgErrors[self::NOTIF_WARNING]);
    } else {
      $notif = self::NOTIF_SUCCESS;
      $msg = 'L\'importation des données s\'est correctement déroulée.';
    }
  }

}
