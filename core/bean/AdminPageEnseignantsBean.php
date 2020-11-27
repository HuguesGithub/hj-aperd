<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageEnseignantsBean
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.01
 */
class AdminPageEnseignantsBean extends AdminPageBean
{
  protected $urlTemplatePageEnseignantAdmin = 'web/pages/admin/board-enseignants.php';
  /**
   * Class Constructor
   */
  public function __construct($id=null)
  {
    parent::__construct();
    $this->title = 'Enseignants';
    $this->EnseignantServices = new EnseignantServices();
    $this->ProfPrincipalServices = new ProfPrincipalServices();
    $this->AnneeScolaireServices = new AnneeScolaireServices();
    $this->ClasseScolaireServices = new ClasseScolaireServices();
  }
  /**
   * @param array $urlParams
   * @return $Bean
   */
  public static function getStaticContentPage($urlParams)
  {
    $Bean = new AdminPageEnseignantsBean();
    if (isset($urlParams[self::CST_POSTACTION])) {
      $Bean->dealWithPostAction($urlParams);
    }
    return $Bean->getListingPage($urlParams);
  }
  public function dealWithPostAction($urlParams)
  {
    $this->msgErreur = '';
    switch ($urlParams['type']) {
      case 'Enseignants' :
        $this->dealWithEnseignantsAction($urlParams);
      break;
      case 'ProfsPrincipaux' :
        $this->dealWithProfPrincipauxAction($urlParams);
      break;
      default :
      break;
    }
  }
  private function dealWithEnseignantsAction($urlParams)
  {
    $id = $urlParams[self::FIELD_ID];
    if ($urlParams[self::CST_POSTACTION] == 'edit') {
      $this->Enseignant = $this->EnseignantServices->selectLocal($id);
    } elseif ($urlParams[self::CST_POSTACTION] == 'Edition') {
      $this->Enseignant->setNomEnseignant($urlParams[self::FIELD_NOMENSEIGNANT]);
      $this->Enseignant->setMatiereId($urlParams[self::FIELD_MATIERE_ID]);
      $this->Enseignant->setStatus($urlParams[self::FIELD_STATUS]);
      $this->EnseignantServices->updateLocal($this->Enseignant);
    } elseif ($urlParams[self::CST_POSTACTION] == 'Création') {
      $this->Enseignant = new Enseignant();
      $this->Enseignant->setNomEnseignant($urlParams[self::FIELD_NOMENSEIGNANT]);
      $this->Enseignant->setMatiereId($urlParams[self::FIELD_MATIERE_ID]);
      $this->Enseignant->setStatus($urlParams[self::FIELD_STATUS]);
      $this->EnseignantServices->insertLocal($this->Enseignant);
    }
  }
  private function dealWithProfPrincipauxAction($urlParams)
  {
    $id = $urlParams[self::FIELD_ID];
    if ($urlParams[self::CST_POSTACTION] == 'edit') {
      $this->ProfPrincipal = $this->ProfPrincipalServices->selectLocal($id);
    } elseif ($urlParams[self::CST_POSTACTION] == 'Edition') {
      $this->ProfPrincipal->setAnneeScolaireId($urlParams[self::FIELD_ANNEESCOLAIRE_ID]);
      $this->ProfPrincipal->setClasseId($urlParams[self::FIELD_CLASSE_ID]);
      $this->ProfPrincipal->setEnseignantId($urlParams[self::FIELD_ENSEIGNANT_ID]);
      $this->ProfPrincipalServices->updateLocal($this->ProfPrincipal);
    } elseif ($urlParams[self::CST_POSTACTION] == 'Création') {
      $this->ProfPrincipal = new ProfPrincipal();
      $this->ProfPrincipal->setAnneeScolaireId($urlParams[self::FIELD_ANNEESCOLAIRE_ID]);
      $this->ProfPrincipal->setClasseId($urlParams[self::FIELD_CLASSE_ID]);
      $this->ProfPrincipal->setEnseignantId($urlParams[self::FIELD_ENSEIGNANT_ID]);
      $this->ProfPrincipalServices->insertLocal($this->ProfPrincipal);
    } elseif ($urlParams[self::CST_POSTACTION] == 'Upload') {
      // On veut mettre à jour l'ensemble des données de l'année pour les profs principaux.
      // Le format attendu est "anneeScolaire;labelClasse;nomEnseignant"
      // On va parcourir toutes les lignes, vérifier que les données existent.
      // Si au moins une erreur, on retourne les actions à entreprendre.
      // Si aucune erreur, on vide les données de l'année concernée et on load les nouvelles.
      $bln_uploadOkay = true;
      $arrInsert = array();
      $anneeReference = '';
      $arrLines = explode("\r\n", $urlParams['fileContent']);
      while ($bln_uploadOkay && !empty($arrLines)) {
        $line = array_shift($arrLines);
        if (empty(trim($line))) {
          continue;
        }
        list($anneeScolaire, $labelClasse, $nomEnseignant) = explode (';', $line);
        if (empty(trim($anneeScolaire)) || empty(trim($anneeScolaire)) || empty(trim($anneeScolaire))) {
          $bln_uploadOkay = false;
        }
        if ($bln_uploadOkay) {
          $AnneeScolaires = $this->AnneeScolaireServices->getAnneeScolairesWithFilters(array(self::FIELD_ANNEESCOLAIRE=>$anneeScolaire));
          if (empty($AnneeScolaires)) {
            $this->msgErreur .= 'Il est nécessaire de créer l\'année scolaire <strong>'.$anneeScolaire.'</strong>.<br>';
            $bln_uploadOkay = false;
          } elseif ($anneeReference!='' && $anneeReference!=$anneeScolaire) {
            $this->msgErreur .= 'Vous ne pouvez mettre à jour qu\'une seule année scolaire <strong>'.$anneeReference.' et '.$anneeScolaire.'</strong>.<br>';
            $bln_uploadOkay = false;
          } else {
            $AnneeScolaire = array_shift($AnneeScolaires);
            $anneeReference = $anneeScolaire;
          }
        }
        if ($bln_uploadOkay) {
          $ClasseScolaires = $this->ClasseScolaireServices->getClasseScolairesWithFilters(array(self::FIELD_LABELCLASSE=>$labelClasse));
          if (empty($ClasseScolaires)) {
            $this->msgErreur .= 'Il est nécessaire de créer la classe <strong>'.$labelClasse.'</strong>.<br>';
            $bln_uploadOkay = false;
          } else {
            $ClasseScolaire = array_shift($ClasseScolaires);
          }
        }
        if ($bln_uploadOkay) {
          $Enseignants = $this->EnseignantServices->getEnseignantsWithFilters(array(self::FIELD_NOMENSEIGNANT=>$nomEnseignant));
          if (empty($Enseignants)) {
            $this->msgErreur .= 'Il est nécessaire de créer l\'enseignant <strong>'.$nomEnseignant.'</strong>.<br>';
            $bln_uploadOkay = false;
          } else {
            $Enseignant = array_shift($Enseignants);
          }
        }
        if ($bln_uploadOkay) {
          $arrInsert[] = '('.$AnneeScolaire->getId().', '.$ClasseScolaire->getId().', '.$Enseignant->getId().')';
        }
      }
      if ($bln_uploadOkay) {
        MySQL::wpdbDelete('wp_14_aperd_prof_princ', 'anneeScolaireId='.$AnneeScolaire->getId());
        $request = "INSERT INTO wp_14_aperd_prof_princ (anneeScolaireId, classeId, enseignantId) VALUES ";
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
    // On récupère tous les enseignants puis on concatène les rows.
    // On filtre toutefois sur la matière et le statut si renseignés.
    $args = array();
    if (isset($urlParams[self::FIELD_MATIERE_ID]) && $urlParams[self::FIELD_MATIERE_ID]!=-1) {
      $filterMatiereId = $urlParams[self::FIELD_MATIERE_ID];
      $args[self::FIELD_MATIERE_ID] = $filterMatiereId;
    }
    if (isset($urlParams[self::FIELD_STATUS]) && $urlParams[self::FIELD_STATUS]!=-1) {
      $filterStatusId = $urlParams[self::FIELD_STATUS];
      $args[self::FIELD_STATUS] = $filterStatusId;
    } else {
      $filterStatusId = -1;
    }
    $strRows = '';
    $Enseignant = new Enseignant();
    $Bean = new EnseignantBean();
    $Enseignants = $this->EnseignantServices->getEnseignantsWithFilters($args);
    if (empty($Enseignants)) {
      $strRows = '<tr><td colspan="4"><em>Aucun résultat</em></td></tr>';
    } else {
      while (!empty($Enseignants)) {
        $Enseignant = array_shift($Enseignants);
        $Bean = $Enseignant->getBean();
        $strRows .= $Bean->getRowForAdminPage($args);
      }
    }
    /////////////////////////////////////////////////////////////////////////////
    // On récupère tous les profs principaux puis on concatène les rows.
    // On filtre toutefois sur l'année scolaire si renseignée.
    $args = array();
    if (isset($urlParams[self::FIELD_ANNEESCOLAIRE_ID]) && $urlParams[self::FIELD_ANNEESCOLAIRE_ID]!=-1) {
      $anneeScolaireId = $urlParams[self::FIELD_ANNEESCOLAIRE_ID];
      $args[self::FIELD_ANNEESCOLAIRE_ID] = $anneeScolaireId;
    }
    $strRowsProfPrincipaux = '';
    $ProfPrincipal = new ProfPrincipal();
    $Bean = new ProfPrincipalBean();
    $ProfPrincipaux = $this->ProfPrincipalServices->getProfPrincipalsWithFilters($args);
    if (empty($ProfPrincipaux)) {
      $strRowsProfPrincipaux = '<tr><td colspan="4"><em>Aucun résultat</em></td></tr>';
    } else {
      while (!empty($ProfPrincipaux)) {
        $ProfPrincipal = array_shift($ProfPrincipaux);
        $Bean = $ProfPrincipal->getBean();
        $strRowsProfPrincipaux .= $Bean->getRowForAdminPage($args);
      }
    }

    $MatiereBean = new MatiereBean();
    $AnneeScolaireBean = new AnneeScolaireBean();
    $ClasseScolaireBean = new ClasseScolaireBean();
    $EnseignantBean = new EnseignantBean();
    /////////////////////////////////////////////////////////////////////////////
    // On restitue le template enrichi.
    $attibutes = array(
      // Liste des enseignants affichés - 1
      $strRows,
      // Type d'action : Création ou Edition - 2
      $this->Enseignant==null ? 'Création' : 'Edition',
      // Libellé de la matière éditée - 3
      $this->Enseignant==null ? '' : $this->Enseignant->getNomEnseignant(),
      // Url d'annulation - 4
      $Bean->getQueryArg(array(self::CST_ONGLET=>self::PAGE_ENSEIGNANT, self::ATTR_TYPE=>'Enseignants')),
      // Select des Matières - 5
      $MatiereBean->getSelect(self::FIELD_MATIERE_ID, self::CST_DEFAULT_SELECT, ($this->Enseignant==null ? -1 : $this->Enseignant->getMatiereId())),
      // Id de l'enseignant - 6
      $this->Enseignant==null ? '' : $this->Enseignant->getId(),
      // Statut de l'enseignant - 7
      $this->getStatusSelect('Choisir...', $this->Enseignant==null ? -1 : $this->Enseignant->getStatus()),
      // Filtre des Matières - 8
      $MatiereBean->getSelect(self::FIELD_MATIERE_ID, 'Toutes les matières', $filterMatiereId),
      // Filtre du Statut - 9
      $this->getStatusSelect('Tous les statuts', $filterStatusId),
      // Onglet actuel - 10
      'tab'.(isset($urlParams['type']) ? $urlParams['type'] : 'Enseignants'),
      // Liste des profs principaux affichés - 11
      $strRowsProfPrincipaux,
      // Type d'action : Création ou Edition - 12
      $this->ProfPrincipal==null ? 'Création' : 'Edition',
      // Select des Années Scolaires - 13
      $AnneeScolaireBean->getSelect(self::FIELD_ANNEESCOLAIRE_ID, self::CST_DEFAULT_SELECT, ($this->ProfPrincipal==null ? -1 : $this->ProfPrincipal->getAnneeScolaireId())),
      // Select des Classes - 14
      $ClasseScolaireBean->getSelect(self::FIELD_CLASSE_ID, self::CST_DEFAULT_SELECT, ($this->ProfPrincipal==null ? -1 : $this->ProfPrincipal->getClasseId())),
      // Select des Enseignants - 15
      $EnseignantBean->getSelect(self::FIELD_ENSEIGNANT_ID, self::CST_DEFAULT_SELECT, ($this->ProfPrincipal==null ? -1 : $this->ProfPrincipal->getEnseignantId())),
      // Id du prof principal - 16
      $this->ProfPrincipal==null ? '' : $this->ProfPrincipal->getId(),
      // Url d'annulation - 17
      $Bean->getQueryArg(array(self::CST_ONGLET=>self::PAGE_ENSEIGNANT, self::ATTR_TYPE=>'ProfsPrincipaux')),
      // Filtre de l'Année Scolaire - 18
      $AnneeScolaireBean->getSelect(self::FIELD_ANNEESCOLAIRE_ID, 'Toutes les années scolaires', $anneeScolaireId),
      // Message d'erreur en cas d'upload de profs principaux foireux - 19
      (!empty($this->msgErreur) ? '<div class="alert alert-danger" role="alert">'.$this->msgErreur.'</div>' : ''),

    );
    return $this->getRender($this->urlTemplatePageEnseignantAdmin, $attibutes);
  }

  private function getStatusSelect($label, $selectedId=-1)
  {
    // On construit les Options du select
    $strOptions  = $this->getLocalOption($label, -1, $selectedId);
    $strOptions .= $this->getLocalOption('Actif', 1, $selectedId);
    $strOptions .= $this->getLocalOption('Inactif', 0, $selectedId);
    // On définit les attributs du select
    $attributes = array(
      self::ATTR_CLASS => self::CST_MD_SELECT,
      self::ATTR_NAME  => self::FIELD_STATUS,
    );
    // On retourne le select.
    return $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);
  }
}
