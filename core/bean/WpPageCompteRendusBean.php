<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageCompteRendusBean
 * @author Hugues
 * @version 1.21.06.29
 * @since 1.21.06.01
 */
class WpPageCompteRendusBean extends WpPageBean
{
  protected $urlTemplate = 'web/pages/public/wppage-compte-rendus.php';
  protected $urlTemplateIdentification = 'web/pages/public/wppage-compte-rendus-identification.php';
  protected $urlFragmentNotification = 'web/pages/public/fragments/fragment-notification.php';
  /**
   * Class Constructor
   * @param WpPage $WpPage
   * @version 1.00.00
   * @since 1.00.00
   */
  public function __construct($WpPage='')
  {
    parent::__construct($WpPage);
    $this->AnneeScolaireServices = new AnneeScolaireServices();
    $this->BilanMatiereServices  = new BilanMatiereServices();
    $this->CompoDivisionServices = new CompoDivisionServices();
    $this->CompteRenduServices   = new CompteRenduServices();
    $this->EleveServices         = new EleveServices();
    $this->ProfPrincipalServices = new ProfPrincipalServices();
    $this->ParentDelegueServices = new ParentDelegueServices();
  }
  private function initCompteRendu()
  {
    ////////////////////////////////////////////////////
    // On va initialiser le Compte-Rendu comme il faut.
    // On récupère le crKey, qui par défaut doit être dans la Session.
    $crKey = $this->initVar(self::FIELD_CRKEY, -1);
    // On récupère la Division associée.
    $Divisions = $this->DivisionServices->getDivisionsWithFilters(array(self::FIELD_CRKEY=>$crKey));
    // S'il n'y en a pas, on retourne à la page d'accueil.
    if (empty($Divisions)) {
      return false;
    }

    $initDone = false;

    // Sinon, on récupère la Division concernée.
    $this->Division = array_shift($Divisions);
    // On récupère ensuite les Comptes Rendus associés à cette Division
    $CompteRendus = $this->CompteRenduServices->getCompteRendusWithFilters(array(self::FIELD_DIVISION_ID=>$this->Division->getId()), self::FIELD_TRIMESTRE);
    // Si $CompteRendus est vide, on a aucun CompteRendu existant. On créé un Compte Rendu pour le premier trimestre et on termine
    if (empty($CompteRendus)) {
      $this->CompteRendu = new CompteRendu();
      $this->CompteRendu->setField(self::FIELD_DIVISION_ID, $this->Division->getId());
      $this->CompteRendu->setField(self::FIELD_TRIMESTRE, 1);
      $this->trimestre = 1;
      $this->CompteRendu->setField(self::FIELD_STATUS, self::STATUS_FUTURE);
      $this->CompteRenduServices->insertLocal($this->CompteRendu);
      $initDone = true;
    }

    if (!$initDone) {
      // On récupère la variable Trimestre si elle est définie.
      $trimestre = $this->initVar(self::FIELD_TRIMESTRE, -1);
      // On parcourt les Comptes Rendus existants pour cette Division
      while (!empty($CompteRendus)) {
        $CompteRendu = array_shift($CompteRendus);
        if (in_array($CompteRendu->getStatus(), array(self::STATUS_FUTURE, self::STATUS_WORKING, self::STATUS_PENDING)) || $CompteRendu->getTrimestre()==$trimestre) {
          $this->CompteRendu = $CompteRendu;
          $this->trimestre   = $CompteRendu->getTrimestre();
          $initDone = true;
        }
      }
    }

    if (!$initDone) {
      $this->CompteRendu = $CompteRendu;
      $this->trimestre   = $CompteRendu->getTrimestre();
      $initDone = true;
    }
    return $initDone;
  }
  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getContentPage()
  {

    $this->initCompteRendu();


    /*
    // On devrait traiter la soumission d'un formulaire.
    if (isset($_POST)&&!empty($_POST)) {
      if (isset($_POST[self::AJAX_SAVE])) {
        $post = array_merge($_POST, array(self::AJAX_ACTION=>self::AJAX_SAVE));
        $this->CompteRendu = CompteRenduActions::dealWithStatic($post);
        $post = array(self::AJAX_ACTION=>self::AJAX_SEARCH, self::FIELD_CRKEY=>$crKey);
        $this->CompteRendu = CompteRenduActions::dealWithStatic($post);
      } elseif (isset($_POST[self::AJAX_SEARCH])) {
        $post = array_merge($_POST, array(self::AJAX_ACTION=>self::AJAX_SEARCH, self::FIELD_CRKEY=>$crKey));
        $this->CompteRendu = CompteRenduActions::dealWithStatic($post);
      }
    } elseif ($crKey!=-1) {
      /*
      $post = array(self::AJAX_ACTION=>self::AJAX_SEARCH, self::FIELD_CRKEY=>$crKey);
      $this->CompteRendu = CompteRenduActions::dealWithStatic($post);
      * /
      $this->CompteRendu = array_shift($CompteRendus);
    } else {
      $this->CompteRendu = new CompteRendu();
      return $this->getContentIdentification();
    }
    */
    return $this->getContent();
  }
  public function getContentIdentification()
  {
    $args = array('');
    return $this->getRender($this->urlTemplateIdentification, $args);
  }
  /**
   * @return string
   * @version 1.21.06.29
   * @since 1.21.06.01
   */
  public function getContent()
  {
    $update = false;
    /*
    //////////////////////////////////////////////////////////////////
    // On peut faire des contrôles de valeurs pour les initialiser si nécessaire
    //////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////
    // Récupération des Bilans par Matières existants et restitution
    $strNewObservationsByMatieres = '';

    $strButtonMatieres = '';
    $strPanelMatieres  = '';
    $isFirstButton = true;

    $strObservationsByMatieres = '';
    if ($this->CompteRendu->getId()!='') {
      $attributes = array(self::FIELD_COMPTERENDU_ID=>$this->CompteRendu->getId());
      $BilanMatieres = $this->BilanMatiereServices->getBilanMatieresWithFilters($attributes);
      if (!empty($BilanMatieres)) {
        while (!empty($BilanMatieres)) {
          $BilanMatiere = array_shift($BilanMatieres);
          $strObservationsByMatieres .= $BilanMatiere->getBean()->getFragmentObservationMatiere();
          $BilanMatiere->getBean()->getBilanMatiere($strButtonMatieres, $strPanelMatieres, $isFirstButton);
        }
      } else {
        $args = array(
          self::FIELD_ANNEESCOLAIRE_ID => $this->CompteRendu->getAnneeScolaireId(),
          self::FIELD_DIVISION_ID      => $this->CompteRendu->getDivisionId(),
        );
        $CompoClasses = $this->CompoDivisionServices->getCompoDivisionsWithFilters($args);
        while (!empty($CompoClasses)) {
          $CompoClasse = array_shift($CompoClasses);
          $BilanMatiere = new BilanMatiere();
          $BilanMatiere->setCompteRenduId($this->CompteRendu->getId());
          $BilanMatiere->setMatiereId($CompoClasse->getMatiereId());
          $BilanMatiere->setEnseignantId($CompoClasse->getEnseignantId());
          $strObservationsByMatieres .= $BilanMatiere->getBean()->getFragmentObservationMatiere();
          $BilanMatiere->getBean()->getBilanMatiere($strButtonMatieres, $strPanelMatieres, $isFirstButton);
        }
      }
    }
    $BilanMatiereBean = new BilanMatiereBean();
    $strObservationsByMatieres .= $BilanMatiereBean->getFragmentObservationMatiere();

    $strNewObservationsByMatieres .= '<div class="form-row" style="width:100%;">';
    $strNewObservationsByMatieres .= '<div class="form-group col-md-3 btn-group-vertical btn-group-sm">'.$strButtonMatieres.'</div>';
    $strNewObservationsByMatieres .= '<div class="form-group col-md-9"><div class="tab-content" id="v-pills-tabContent">'.$strPanelMatieres.'</div></div></div>';

    //////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////
    // Initialisation des Beans pour construire les listes déroulantes.
    $AnneeScolaireBean = new AnneeScolaireBean();
    $DivisionBean = new DivisionBean();
    $AdministrationBean = new AdministrationBean();
    $EnseignantBean = new EnseignantBean();

    */

    //////////////////////////////////////////////////////////////////
    // On enrichi le template puis on le restitue.
    $args = array(
      // Contenu de l'étape n°1 - 1
      $this->getContentStep1(),
      // Contenu de l'étape n°2 - 2
      '',
      // Contenu de l'étape n°3 - 3
      $this->getContentStep3(),
      // Contenu de l'étape n°4 - 4
      '',
      // Contenu de l'étape n°5 - 5
      '',
      // Contenu de l'étape n°6 - 6
      '',
      // Les notifications éventuelles - 7
      '',
      /*
      // Menu déroulant Année Scolaire - 1
      $AnneeScolaireBean->getSelect(array('tag'=>self::FIELD_ANNEESCOLAIRE_ID, 'selectedId'=>$this->CompteRendu->getAnneeScolaireId(), 'readonly'=>'', self::AJAX_UPLOAD=>'')),
      // Menu déroulant Classe Scolaire - 2
      $DivisionBean->getSelect(array('tag'=>self::FIELD_DIVISION_ID, 'selectedId'=>$this->CompteRendu->getDivisionId(), 'readonly'=>'', self::AJAX_UPLOAD=>'')),
      // Menu déroulant Présidence - 3
      $AdministrationBean->getSelect(array('tag'=>self::FIELD_ADMINISTRATION_ID, 'selectedId'=>$this->CompteRendu->getValue(self::FIELD_ADMINISTRATION_ID), 'required'=>'', self::AJAX_UPLOAD=>'')),
      // Menu déroulant Prof Principal - 4
      $EnseignantBean->getSelect(array('tag'=>self::FIELD_ENSEIGNANT_ID, 'selectedId'=>$profPrincId, 'required'=>'', self::AJAX_UPLOAD=>'')),
      // Premier bloc d'observations par matière - 5
      (self::isAdmin() ? $strNewObservationsByMatieres : $strObservationsByMatieres),
      // Menu déroulant pour le trimestre - 6
      //$this->getSelectTrimestre(true),
      $this->getInput(self::FIELD_TRIMESTRE, true, array(self::ATTR_READONLY=>'')),
      // Notifications éventuelles - 7
      $this->CompteRendu->getNotifications(),
      // Textarea Bilan Prof Principal - 14
      $this->getTextArea(self::FIELD_BILANPROFPRINCIPAL, true, true),
      // Input Nb Encouragements - 17
      $this->getInput(self::FIELD_NBENCOURAGEMENTS, true, array(), true),
      // Input Nb Compliments - 18
      $this->getInput(self::FIELD_NBCOMPLIMENTS, true, array(), true),
      // Input Nb Felicitations - 19
      $this->getInput(self::FIELD_NBFELICITATIONS, true, array(), true),
      // Input Nb MGC - 20
      $this->getInput(self::FIELD_NBMGCPT, true, array(), true),
      // Input Nb MGT - 21
      $this->getInput(self::FIELD_NBMGTVL, true, array(), true),
      // Input Nb MGCT - 22
      $this->getInput(self::FIELD_NBMGCPTTVL, true, array(), true),
      // Input Date Rédaction - 23
      $this->getInput(self::FIELD_DATEREDACTION, true, array(self::ATTR_PLACEHOLDER=>self::FORMAT_DATE_JJMMAAAA), true),
      // Input Auteur Rédaction - 24
      $this->getInput(self::FIELD_AUTEURREDACTION, true, array(), true),
      // Input Mail de Contact - 25
      $this->getInput(self::FIELD_MAILCONTACT, false),
      // CrKey - 26
      $this->CompteRendu->getCrKey(),
      * */
    );
    return $this->getRender($this->urlTemplate, $args);
  }

  private function getContentStep3()
  {
    $urlTemplateStep3 = 'web/pages/public/fragments/panel-compte-rendu-step3.php';

    $args = array(
      // Bilan des élèves - 1
      $this->getTextArea(self::FIELD_BILANELEVES, true, true),
      // Bilan des parents - 2
      $this->getTextArea(self::FIELD_BILANPARENTS, true, true),
    );
    return $this->getRender($urlTemplateStep3, $args);
  }

  private function getContentStep1()
  {
    $urlTemplateStep1 = 'web/pages/public/fragments/panel-compte-rendu-step1.php';

    //////////////////////////////////////////////////////////////////
    // On peut faire des contrôles de valeurs pour les initialiser si nécessaire
    // Nb d'élèves :
    $nbEleves = $this->CompteRendu->getValue(self::FIELD_NBELEVES);
    if ($nbEleves==0) {
      // Si le nombre d'élèves est à 0, on peut regarder en base et faire une proposition...
      $args = array(
        self::FIELD_DIVISION_ID      => $this->CompteRendu->getDivisionId(),
      );
      $Eleves = $this->EleveServices->getElevesWithFilters($args);
      $this->CompteRendu->setValue(self::FIELD_NBELEVES, count($Eleves));
      $update = true;
    }
    // Prof Principal :
    $profPrincId = $this->CompteRendu->getValue(self::FIELD_PROFPRINCIPAL_ID);
    if ($profPrincId==0) {
      // S'il n'est pas défini, on peut regarder en base et faire une proposition...
      $args = array(
        self::FIELD_DIVISION_ID      => $this->CompteRendu->getDivisionId(),
      );
      $ProfPrincipals = $this->ProfPrincipalServices->getProfPrincipalsWithFilters($args);
      if (!empty($ProfPrincipals)) {
        $ProfPrincipal = array_shift($ProfPrincipals);
        $profPrincId = $ProfPrincipal->getEnseignantId();
        $this->CompteRendu->setValue(self::FIELD_PROFPRINCIPAL_ID, $profPrincId);
        $update = true;
      }
    }
    //////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////
    // Gestion du Menu déroulant de l'Année Scolaire
    $attributes = array(
      self::ATTR_CLASS => self::CST_MD_SELECT,
      self::ATTR_NAME  => self::FIELD_ADMINISTRATION_ID,
      self::ATTR_READONLY =>'',
    );
    $anneeScolaire = '2021-2022';
    $strOptions  = $this->getLocalOption($anneeScolaire, $anneeScolaire, $anneeScolaire);
    $strSelectAnneeScolaire = $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);
    // Gestion du Menu déroulant du Trimestre
    $attributes = array(
      self::ATTR_CLASS => self::CST_MD_SELECT,
      self::ATTR_NAME  => self::FIELD_TRIMESTRE,
      self::ATTR_READONLY =>'',
    );
    $trimestre = $this->CompteRendu->getField(self::FIELD_TRIMESTRE);
    $strOptions  = $this->getLocalOption($trimestre, $trimestre, $trimestre);
    $strSelectTrimestre = $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);
    // Gestion du Menu déroulant de la Division
    $attributes = array(
      self::ATTR_CLASS => self::CST_MD_SELECT,
      self::ATTR_NAME  => self::FIELD_DIVISION_ID,
      self::ATTR_READONLY =>'',
    );
    $divisionId = $this->Division->getId();
    $strOptions  = $this->getLocalOption($this->Division->getLabelDivision(), $divisionId, $divisionId);
    $strSelectDivision = $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);
    // Gestion du Menu déroulant du Professeur Principal
    $attributes = array(
      self::ATTR_CLASS => self::CST_MD_SELECT,
      self::ATTR_NAME  => self::FIELD_PROFPRINCIPAL_ID,
      self::ATTR_READONLY =>'',
    );
    $ProfPrincipal = $this->CompteRendu->getProfPrincipal();
    $strOptions  = $this->getLocalOption($ProfPrincipal->getFullName(), $ProfPrincipal->getId(), $ProfPrincipal->getId());
    $strSelectProfPrincipal = $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);
    // Gestion des Menus déroulants des Parents d'élèves
    $attributes = array(
      self::ATTR_CLASS => self::CST_MD_SELECT.self::CST_BLANK.self::AJAX_UPLOAD,
    );
    $strOptionsP1 = $this->getDefaultOption();
    $strOptionsP2 = $this->getDefaultOption();
    $ParentDelegues = $this->ParentDelegueServices->getParentDeleguesWithFilters(array(self::FIELD_DIVISION_ID=>$divisionId));
    foreach ($ParentDelegues as $ParentDelegue) {
      $Adulte = $ParentDelegue->getAdulte();
      $strOptionsP1 .= $this->getLocalOption($Adulte->getFullName(), $ParentDelegue->getId(), $this->CompteRendu->getField(self::FIELD_PARENT1));
      $strOptionsP2 .= $this->getLocalOption($Adulte->getFullName(), $ParentDelegue->getId(), $this->CompteRendu->getField(self::FIELD_PARENT2));
    }
    $attributes[self::ATTR_NAME] = self::FIELD_PARENT1;
    $strSelectP1 = $this->getBalise(self::TAG_SELECT, $strOptionsP1, $attributes);
    $attributes[self::ATTR_NAME] = self::FIELD_PARENT2;
    $strSelectP2 = $this->getBalise(self::TAG_SELECT, $strOptionsP2, $attributes);
    // Gestion des Menus déroulants des Elèves délégués
    $attributes = array(
      self::ATTR_CLASS => self::CST_MD_SELECT.self::CST_BLANK.self::AJAX_UPLOAD,
    );
    $strOptionsE1 = $this->getDefaultOption();
    $strOptionsE2 = $this->getDefaultOption();
    $Eleves = $this->EleveServices->getElevesWithFilters(array(self::FIELD_DIVISION_ID=>$divisionId, self::FIELD_DELEGUE=>1));
    foreach ($Eleves as $Eleve) {
      $strOptionsE1 .= $this->getLocalOption($Eleve->getFullName(), $Eleve->getId(), $this->CompteRendu->getField(self::FIELD_ENFANT1));
      $strOptionsE2 .= $this->getLocalOption($Eleve->getFullName(), $Eleve->getId(), $this->CompteRendu->getField(self::FIELD_ENFANT2));
    }
    $attributes[self::ATTR_NAME] = self::FIELD_ENFANT1;
    $strSelectE1 = $this->getBalise(self::TAG_SELECT, $strOptionsE1, $attributes);
    $attributes[self::ATTR_NAME] = self::FIELD_ENFANT2;
    $strSelectE2 = $this->getBalise(self::TAG_SELECT, $strOptionsE2, $attributes);
    ////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////
    // Si on a alimenté une donnée de façon mécanique, il faut mettre à jour
    if ($update) {
      $this->CompteRenduServices->updateLocal($this->CompteRendu);
    }
    //////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////
    // On défini quelques Bean utile pour le Template
    $AdministrationBean = new AdministrationBean();

    //////////////////////////////////////////////////////////////////
    // On enrichi le Template, puis on le restitue.
    $args = array(
      // Donnée fixe Année Scolaire - 1
      $strSelectAnneeScolaire,
      // Donnée fixe Trimestre - 2
      $strSelectTrimestre,
      // Donnée fixe Division - 3
      $strSelectDivision,
      // Input NbEleves - 4
      $this->getInput(self::FIELD_NBELEVES, true, array(), true),
      // Input DateConseil - 5
      $this->getInput(self::FIELD_DATECONSEIL, true, array(self::ATTR_PLACEHOLDER=>self::FORMAT_DATE_JJMMAAAA), true),
      // Menu déroulant Présidence - 6
      $AdministrationBean->getSelect(array('tag'=>self::FIELD_ADMINISTRATION_ID, 'selectedId'=>$this->CompteRendu->getValue(self::FIELD_ADMINISTRATION_ID), self::ATTR_REQUIRED=>'', self::AJAX_UPLOAD=>'')),
      // Menu déroulant Prof Principal - 7
      $strSelectProfPrincipal,
      // Menu déroulant Premier Parent - 8
      $strSelectP1,
      // Menu déroulant Deuxième Parent - 9
      $strSelectP2,
     // Menu déroulant Premier Elève - 10
      $strSelectE1,
     // Menu déroulant Premier Elève - 11
      $strSelectE2,
    );
    return $this->getRender($urlTemplateStep1, $args);
  }

  /**
   * @param string $field
   * @param boolean $isMandatory
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getTextArea($field, $isMandatory=false, $isAjaxUpload=false)
  {
    $id = $this->CompteRendu->getId();
    $value = $this->CompteRendu->getValue($field);
    $classe = self::CST_MD_TEXTAREA.($isMandatory && $value=='' && $id!='' ? ' '.self::NOTIF_IS_INVALID : '');
    $args = array(
      self::ATTR_ID => $field,
      self::ATTR_CLASS => $classe.($isAjaxUpload ? ' '.self::AJAX_UPLOAD : ''),
      self::ATTR_ROWS =>5,
      self::ATTR_NAME =>$field,
    );
    if ($isMandatory) {
      $args[self::ATTR_REQUIRED] = '';
    }
    return $this->getBalise(self::TAG_TEXTAREA, $value, $args);
  }

  /**
   * @param string $field
   * @param boolean $isMandatory
   * @param array $extraArgs
   * @param boolean $isAjaxUpload
   * @return string
   * @version 1.21.07.16
   * @since 1.21.06.01
   */
  public function getInput($field, $isMandatory=false, $extraArgs=array(), $isAjaxUpload=false)
  {
    $id = $this->CompteRendu->getId();
    // On passe sur un ifelse, à repasser en switch si le nombre de cas évolue...
    if ($field == self::FIELD_DIVISION_ID) {
      $value = $this->CompteRendu->getDivision()->getLabelDivision();
    } else {
      $value = $this->CompteRendu->getValue($field);
    }

    $classe = self::CST_FORMCONTROL.($isMandatory && $value=='' && $id!='' ? ' '.self::NOTIF_IS_INVALID : '');
    $args = array(
      self::ATTR_TYPE  => self::CST_TEXT,
      self::ATTR_CLASS => $classe.($isAjaxUpload ? ' '.self::AJAX_UPLOAD : ''),
      self::ATTR_ID    => $field,
      self::ATTR_NAME  => $field,
      self::ATTR_VALUE => $value,
    );
    if ($isMandatory) {
      $args[self::ATTR_REQUIRED] = '';
    }
    if (!empty($extraArgs)) {
      $args = array_merge($args, $extraArgs);
    }
    return $this->getBalise(self::TAG_INPUT, '', $args);
  }

  /**
   * @param boolean $isMandatory
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getSelectTrimestre($isMandatory=false)
  {
    $selectedId = $this->CompteRendu->getValue(self::FIELD_TRIMESTRE);
    $strOptions  = $this->getDefaultOption($selectedId);
    $strOptions .= $this->getLocalOption(1, 1, $selectedId);
    $strOptions .= $this->getLocalOption(2, 2, $selectedId);
    $strOptions .= $this->getLocalOption(3, 3, $selectedId);
    $bFlag = $isMandatory && ($selectedId==-1||$selectedId==self::CST_DEFAULT_SELECT);
    $attributes = array(
      self::ATTR_ID       => self::FIELD_TRIMESTRE,
      self::ATTR_CLASS    => self::CST_MD_SELECT.($bFlag ? ' '.self::NOTIF_IS_INVALID : ''),
      self::ATTR_NAME     => self::FIELD_TRIMESTRE,
      self::ATTR_REQUIRED => '',
    );
    return $this->getBalise(self::TAG_SELECT, $strOptions, $attributes);
  }
}
