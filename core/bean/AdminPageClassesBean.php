<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageClassesBean
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.01
 */
class AdminPageClassesBean extends AdminPageBean
{
  protected $urlTemplatePageClasseAdmin = 'web/pages/admin/board-classes.php';
  /**
   * Class Constructor
   */
  public function __construct($id=null)
  {
    parent::__construct();
    $this->title = 'Classes';
    $this->AnneeScolaireServices = new AnneeScolaireServices();
    $this->ClasseScolaireServices = new ClasseScolaireServices();
    $this->CompoClasseServices = new CompoClasseServices();
    $this->EnseignantServices = new EnseignantServices();
    $this->MatiereServices = new MatiereServices();
  }
  /**
   * @param array $urlParams
   * @return $Bean
   */
  public static function getStaticContentPage($urlParams)
  {
    $Bean = new AdminPageClassesBean();
    if (isset($urlParams[self::CST_POSTACTION])) {
      $Bean->dealWithPostAction($urlParams);
    }
    return $Bean->getListingPage($urlParams);
  }
  public function dealWithPostAction($urlParams)
  {
    switch ($urlParams['type']) {
      case 'Classe' :
        $this->dealWithClasseScolaireAction($urlParams);
      break;
      case 'ClasseScolaire' :
        $this->dealWithCompoClasseAction($urlParams);
      break;
      default :
      break;
    }
  }
  private function dealWithClasseScolaireAction($urlParams)
  {
    $id = $urlParams[self::FIELD_ID];
    if ($urlParams[self::CST_POSTACTION] == 'edit') {
      $this->ClasseScolaire = $this->ClasseScolaireServices->selectLocal($id);
    } elseif ($urlParams[self::CST_POSTACTION] == 'Edition') {
      $this->ClasseScolaire = $this->ClasseScolaireServices->selectLocal($id);
      $this->ClasseScolaire->setLabelClasse($urlParams[self::FIELD_LABELCLASSE]);
      $this->ClasseScolaireServices->updateLocal($this->ClasseScolaire);
    } elseif ($urlParams[self::CST_POSTACTION] == 'Création') {
      $this->ClasseScolaire = new ClasseScolaire();
      $this->ClasseScolaire->setLabelClasse($urlParams[self::FIELD_LABELCLASSE]);
      $this->ClasseScolaireServices->insertLocal($this->ClasseScolaire);
    }
  }
  private function dealWithCompoClasseAction($urlParams)
  {
    $id = $urlParams[self::FIELD_ID];
    if ($urlParams[self::CST_POSTACTION] == 'Upload') {
      // On veut mettre à jour l'ensemble des données de l'année pour chaque classe, les couples Matière/Enseignant.
      // Le format attendu est "
      // anneeScolaire
      // Matières;Liste des matières séparées par des ;
      // LabelClasse;Liste des Enseignants séparés par des ; correspondant à la Matière
      // On va parcourir toutes les lignes, vérifier que les données existent.
      // Si on a une erreur sur l'année ou une Matière, on retourne les actions à entreprendre.
      // Si on a au moins une erreur sur une Classe, on retourne les actions à entreprendre.
      // Si on a aucune erreur sur une Classe, on supprimer les infos pour l'année et la classe concernées et on insère les nouvelles données.
      $bln_uploadOkay = true;
      $arrInsert = array();
      // L'année que l'on va traiter
      $AnneeReference = '';
      // L'ordre des Matières selon la deuxième ligne du fichier
      $arrMatieresOrdonnees = array();
      // Le contenu de l'upload
      $arrLines = explode("\r\n", $urlParams['fileContent']);
      // On checke la première ligne
      $line = array_shift($arrLines);
      $AnneeScolaires = $this->AnneeScolaireServices->getAnneeScolairesWithFilters(array(self::FIELD_ANNEESCOLAIRE=>$line));
      if (empty($AnneeScolaires)) {
        $this->msgErreur .= 'Il est nécessaire de créer l\'année scolaire <strong>'.$line.'</strong>.<br>';
        $bln_uploadOkay = false;
      } else {
        // On sauvegarde l'année de référence
        $AnneeReference = array_shift($AnneeScolaires);

        // On checke la deuxième ligne
        $line = array_shift($arrLines);
        $arrMatieres = explode(';', $line);
        if ($arrMatieres[0]!='Matières') {
          // On vérifie qu'elle commence par "Matières".
          $this->msgErreur .= 'La ligne des Matières doit commencer par le libellé <strong>Matières</strong>. Ici, il commence par <em>'.$arrMatieres[0].'</em><br>';
          $bln_uploadOkay = false;
        } else {
          // Puis on analyse les différentes Matières saisies.
          array_shift($arrMatieres);
          while ($bln_uploadOkay && !empty($arrMatieres)) {
            $labelMatiere = array_shift($arrMatieres);
            $Matieres = $this->MatiereServices->getMatieresWithFilters(array(self::FIELD_LABELMATIERE=>$labelMatiere));
            if (empty($Matieres)) {
              // On vérifie l'existence de la Matière
              $this->msgErreur .= 'Il est nécessaire de créer la Matière <strong>'.$labelMatiere.'</strong>.<br>';
              $bln_uploadOkay = false;
            } else {
              // On la sauvegarde.
              $Matiere = array_shift($Matieres);
              $arrMatieresOrdonnees[] = $Matiere;
            }
          }
        }
      }

      // On peut ensuite vérifier les lignes restantes par classe.
      while ($bln_uploadOkay && !empty($arrLines)) {
        $line = array_shift($arrLines);
        if (empty(trim($line))) {
          continue;
        }
        $arrEnseignants = explode(';', $line);
        // La première donnée de la ligne est le libellé de la Classe, on vérifie son existence
        $labelClasse = array_shift($arrEnseignants);
        $ClasseScolaires = $this->ClasseScolaireServices->getClasseScolairesWithFilters(array(self::FIELD_LABELCLASSE=>$labelClasse));
        if (empty($ClasseScolaires)) {
          // Elle n'existe pas.
          $this->msgErreur .= 'Il est nécessaire de créer la classe <strong>'.$labelClasse.'</strong>.<br>';
          $bln_uploadOkay = false;
        } else {
          // On récupère la classe
          $ClasseScolaire = array_shift($ClasseScolaires);
        }

        // On parcourt les Enseignants
        $rk = 0;
        while (!empty($arrEnseignants)) {
          $nomEnseignant = array_shift($arrEnseignants);
          if (trim($nomEnseignant)=='') {
            $rk++;
            continue;
          }
          $Enseignants = $this->EnseignantServices->getEnseignantsWithFilters(array(self::FIELD_NOMENSEIGNANT=>$nomEnseignant));
          if (empty($Enseignants)) {
            // On vérifie son existence.
            $this->msgErreur .= 'Il est nécessaire de créer l\'enseignant <strong>'.$nomEnseignant.'</strong>.<br>';
            $bln_uploadOkay = false;
          } else {
            $Enseignant = array_shift($Enseignants);
            $Matiere = $arrMatieresOrdonnees[$rk];
            $arrInsert[] = '('.$AnneeReference->getId().','.$ClasseScolaire->getId().','.$Matiere->getId().','.$Enseignant->getId().')';
          }
          $rk++;
        }
      }

      if ($bln_uploadOkay) {
        $this->msgErreur = 'Aucune erreur rencontrée dans le traitement.<br>';
        $deleteWhere = 'anneeScolaireId='.$AnneeReference->getId();
        MySQL::wpdbDelete('wp_14_aperd_compo_classe', $deleteWhere);

        $request = "INSERT INTO wp_14_aperd_compo_classe (anneeScolaireId, classeId, matiereId, enseignantId) VALUES ";
        $request .= implode(',', $arrInsert);
        MySQL::wpdbQuery($request);
      }
    }
  }
  /**
   * @return string
   */
  public function getListingPage($urlParams)
  {
    /////////////////////////////////////////////////////////////////////////////
    // Onglet Classe
    $strAdminRowsClasse = '';
    $ClasseScolaires = $this->ClasseScolaireServices->getClasseScolairesWithFilters();
    while (!empty($ClasseScolaires)) {
      $ClasseScolaire = array_shift($ClasseScolaires);
      $Bean = $ClasseScolaire->getBean();
      $strAdminRowsClasse .= $Bean->getRowForAdminPage();
    }
    $urlCancelClasse = $Bean->getQueryArg(array(self::CST_ONGLET=>self::PAGE_CLASSE, 'type'=>'Classe'));

    /////////////////////////////////////////////////////////////////////////////
    // Onglet Classe Scolaire
    $strAdminRowsCompoClasses = '';
    // Filtres disponibles
    $args = array();
    if (isset($urlParams[self::FIELD_ANNEESCOLAIRE_ID]) && $urlParams[self::FIELD_ANNEESCOLAIRE_ID]!=-1) {
      $anneeScolaireId = $urlParams[self::FIELD_ANNEESCOLAIRE_ID];
      $args[self::FIELD_ANNEESCOLAIRE_ID] = $anneeScolaireId;
    }
    if (isset($urlParams[self::FIELD_CLASSE_ID]) && $urlParams[self::FIELD_CLASSE_ID]!=-1) {
      $filterClasseId = $urlParams[self::FIELD_CLASSE_ID];
      $args[self::FIELD_CLASSE_ID] = $filterClasseId;
    }
    if (isset($urlParams[self::FIELD_MATIERE_ID]) && $urlParams[self::FIELD_MATIERE_ID]!=-1) {
      $filterMatiereId = $urlParams[self::FIELD_MATIERE_ID];
      $args[self::FIELD_MATIERE_ID] = $filterMatiereId;
    }
    if (isset($urlParams[self::FIELD_ENSEIGNANT_ID]) && $urlParams[self::FIELD_ENSEIGNANT_ID]!=-1) {
      $filterEnseignantId = $urlParams[self::FIELD_ENSEIGNANT_ID];
      $args[self::FIELD_ENSEIGNANT_ID] = $filterEnseignantId;
    }
    // Fin gestion des filtres

    $CompoClasses = $this->CompoClasseServices->getCompoClassesWithFilters($args);
    if (empty($CompoClasses)) {
      $strAdminRowsCompoClasses = '<tr><td colspan="5"><em>Aucun résultat</em></td></tr>';
    } else {
      while (!empty($CompoClasses)) {
        $CompoClasse = array_shift($CompoClasses);
        $Bean = $CompoClasse->getBean();
        $strAdminRowsCompoClasses .= $Bean->getRowForAdminPage();
      }
    }
    $urlCancelClasse = $Bean->getQueryArg(array(self::CST_ONGLET=>self::PAGE_CLASSE, 'type'=>'ClasseScolaire'));
    /////////////////////////////////////////////////////////////////////////////
    $strFiltres = '';
    $AnneeScolaireBean = new AnneeScolaireBean();
    $strFiltres .= $AnneeScolaireBean->getSelect(self::FIELD_ANNEESCOLAIRE_ID, 'Toutes les années scolaires', $anneeScolaireId);
    $ClasseScolaireBean = new ClasseScolaireBean();
    $strFiltres .= $ClasseScolaireBean->getSelect(self::FIELD_CLASSE_ID, 'Toutes les classes', $filterClasseId);
    $MatiereBean = new MatiereBean();
    $strFiltres .= $MatiereBean->getSelect(self::FIELD_MATIERE_ID, 'Toutes les matières', $filterMatiereId);
    $EnseignantBean = new EnseignantBean();
    $strFiltres .= $EnseignantBean->getSelect(self::FIELD_ENSEIGNANT_ID, 'Tous les enseignants', $filterEnseignantId);

    /////////////////////////////////////////////////////////////////////////////
    // On restitue le template enrichi.
    $attibutes = array(
      // Onglet actuel - 1
      'tab'.(isset($urlParams['type']) ? $urlParams['type'] : 'Classe'),
      // Liste des Classes Scolaires - 2
      $strAdminRowsClasse,
      // Titre du bloc de Création / Edition pour Classe Scolaire - 3
      $this->ClasseScolaire==null ? 'Création' : 'Edition',
      // Libellé de la Classe Scolaire - 4
      $this->ClasseScolaire==null ? '' : $this->ClasseScolaire->getLabelClasse(),
      // Identifiant de l'élément sélectionné - 5
      $this->ClasseScolaire==null ? '' : $this->ClasseScolaire->getId(),
      // Url pour Annuler l'action - 6
      $urlCancelClasse,
      // Message d'erreur en cas d'upload de profs principaux foireux - 7
      (!empty($this->msgErreur) ? '<div class="alert alert-danger" role="alert">'.$this->msgErreur.'</div>' : ''),
      // Url pour annuler l'action - 8
      $Bean->getQueryArg(array(self::CST_ONGLET=>self::PAGE_CLASSE, 'type'=>'ClasseScolaire')),
      // Liste des Compo Scolaires - 9
      $strAdminRowsCompoClasses,
      // Filtre de l'onglet Classe Scolaire - 10
      $strFiltres,
      '','','','','','','',
      '','','','','','','',
      '','','','','','','',
      '','','','','','','',
    );
    return $this->getRender($this->urlTemplatePageClasseAdmin, $attibutes);
  }
}
