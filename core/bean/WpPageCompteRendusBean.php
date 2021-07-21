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
    $this->EnseignantMatiereServices = new EnseignantMatiereServices();
  }
  public function initCompteRendu()
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
        // On recherche le premier Compte Rendu éditable ou correspondant au Trimestre passé en paramètre
        if (in_array($CompteRendu->getStatus(), array(self::STATUS_FUTURE, self::STATUS_WORKING, self::STATUS_PENDING)) || $CompteRendu->getTrimestre()==$trimestre) {
          $this->CompteRendu = $CompteRendu;
          $this->trimestre   = $CompteRendu->getTrimestre();
          $initDone = true;
        }
      }
    }

    if (!$initDone) {
      // C'est bizarre d'être ici. Ca voudrait dire qu'on n'a trouvé aucun Compte Rendu associé à la Division dans la boucle précédente
      // Et donc, de fait, je vois mal comme $CompteRendu est défini ci-dessous.
      // Il faudrait peut-être créer un nouveau CompteRendu associé à la Division, pour le premier Trimestre.
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
    //////////////////////////////////////////////////////////////////
    // On enrichi le template puis on le restitue.
    $args = array(
      // Contenu de l'étape n°1 - 1
      $this->getContentStep1(),
      // Contenu de l'étape n°2 - 2
      $this->getContentStep2(),
      // Contenu de l'étape n°3 - 3
      $this->getContentStep3(),
      // Contenu de l'étape n°4 - 4
      $this->getContentStep4(),
      // Contenu de l'étape n°5 - 5
      $this->getContentStep5(),
      // Contenu de l'étape n°6 - 6
      $this->getContentStep6(),
      // Les notifications éventuelles - 7
      $this->CompteRendu->getNotifications(),
    );
    return $this->getRender($this->urlTemplate, $args);
  }

  private function getContentStep6()
  {
    $urlTemplateStep6 = 'web/pages/public/fragments/panel-compte-rendu-step6.php';

    $args = array(
      // CrKey - 1
      $this->Division->getCrKey(),
    );
    return $this->getRender($urlTemplateStep6, $args);
  }

  private function getContentStep5()
  {
    $urlTemplateStep5 = 'web/pages/public/fragments/panel-compte-rendu-step5.php';

    $args = array(
      // Input Date Rédaction - 1
      $this->getInput(self::FIELD_DATEREDACTION, false, array(self::ATTR_PLACEHOLDER=>self::FORMAT_DATE_JJMMAAAA, self::ATTR_READONLY=>'')),
      // Input Dernier Auteur Rédaction - 2
      $this->getInput(self::FIELD_AUTEURREDACTION, false, array(self::ATTR_READONLY=>'')),
    );
    return $this->getRender($urlTemplateStep5, $args);
  }

  private function getContentStep4()
  {
    $urlTemplateStep4 = 'web/pages/public/fragments/panel-compte-rendu-step4.php';

    $args = array(
      // Input Nb Felicitations - 1
      $this->getInput(self::FIELD_NBFELICITATIONS, true, array(), true),
      // Input Nb MGT - 2
      $this->getInput(self::FIELD_NBMGTVL, true, array(), true),
      // Input Nb Compliments - 3
      $this->getInput(self::FIELD_NBCOMPLIMENTS, true, array(), true),
      // Input Nb MGC - 4
      $this->getInput(self::FIELD_NBMGCPT, true, array(), true),
      // Input Nb Encouragements - 5
      $this->getInput(self::FIELD_NBENCOURAGEMENTS, true, array(), true),
      // Input Nb MGCT - 6
      $this->getInput(self::FIELD_NBMGCPTTVL, true, array(), true),
    );
    return $this->getRender($urlTemplateStep4, $args);
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

  private function getContentStep2()
  {
    $urlTemplateStep2 = 'web/pages/public/fragments/panel-compte-rendu-step2.php';

    //////////////////////////////////////////////////////////////////
    // Récupération des Bilans par Matières existants et restitution
    $strButtonMatieres = '';
    $strPanelMatieres  = '';
    $isFirstButton = true;

    //////////////////////////////////////////////////////////////////
    $Division = $this->CompteRendu->getDivision();
    // On récupère la liste des couples (Enseignant/Matiere) associés à la Division
    $CompoDivisions = $this->CompoDivisionServices->getCompoDivisionsWithFilters(array(self::FIELD_DIVISION_ID=>$Division->getId()));
    while (!empty($CompoDivisions)) {
      $CompoDivision = array_shift($CompoDivisions);
      $EnseignantMatiere = $CompoDivision->getEnseignantMatiere();
      // On récupère le Bilan Matière associé au couple (Compte Rendu/Matiere)
      $argsBM = array(
        self::FIELD_COMPTERENDU_ID => $this->CompteRendu->getId(),
        self::FIELD_MATIERE_ID     => $EnseignantMatiere->getMatiereId(),
      );
      $BilanMatieres = $this->BilanMatiereServices->getBilanMatieresWithFilters($argsBM);
      if (empty($BilanMatieres)) {
        // S'il n'existe pas, on le créé.
        $BilanMatiere = new BilanMatiere();
        $BilanMatiere->setCompteRenduId($this->CompteRendu->getId());
        $BilanMatiere->setMatiereId($EnseignantMatiere->getMatiereId());
        $this->BilanMatiereServices->insertLocal($BilanMatiere);
      } else {
        // S'il existe, on le récupère.
        $BilanMatiere = array_shift($BilanMatieres);
      }

      $BilanMatiere->getBean()->getBilanMatiere($strButtonMatieres, $strPanelMatieres, $isFirstButton);
    }
    //////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////
    // On enrichi le Template et on le restitue
    $args = array(
      // Bilan du Prof Principal - 1
      $this->getTextArea(self::FIELD_BILANPROFPRINCIPAL, true, true),
      // Liste des boutons des Matières - 2
      $strButtonMatieres,
      // Liste des saisies des Matières - 3
      $strPanelMatieres,
    );
    return $this->getRender($urlTemplateStep2, $args);
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
    switch ($field) {
      case self::FIELD_DIVISION_ID :
        $value = $this->CompteRendu->getDivision()->getLabelDivision();
      break;
      case self::FIELD_AUTEURREDACTION :
        $value = $this->CompteRendu->getAuteurRedaction()->getFullName();
      break;
      default :
        $value = $this->CompteRendu->getValue($field);
      break;
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
}
