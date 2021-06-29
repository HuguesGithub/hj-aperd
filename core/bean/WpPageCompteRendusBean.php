<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageCompteRendusBean
 * @author Hugues
 * @version 1.21.06.17
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
  }
  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getContentPage()
  {
    $crKey = $this->initVar(self::FIELD_CRKEY, -1);
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
      $post = array(self::AJAX_ACTION=>self::AJAX_SEARCH, self::FIELD_CRKEY=>$crKey);
      $this->CompteRendu = CompteRenduActions::dealWithStatic($post);
    } else {
      $this->CompteRendu = new CompteRendu();
      return $this->getContentIdentification();
    }
    return $this->getContent();
  }
  public function getContentIdentification()
  {
    $args = array('');
    return $this->getRender($this->urlTemplateIdentification, $args);
  }
  /**
   * @return string
   * @version 1.21.06.17
   * @since 1.21.06.01
   */
  public function getContent()
  {
    $update = false;
    //////////////////////////////////////////////////////////////////
    // On peut faire des contrôles de valeurs pour les initialiser si nécessaire
    //////////////////////////////////////////////////////////////////
    // Prof Principal :
    $profPrincId = $this->CompteRendu->getValue(self::FIELD_ENSEIGNANT_ID);
    if ($profPrincId==0) {
      // S'il n'est pas défini, on peut regarder en base et faire une proposition...
      $args = array(
        self::FIELD_ANNEESCOLAIRE_ID => $this->CompteRendu->getAnneeScolaireId(),
        self::FIELD_DIVISION_ID      => $this->CompteRendu->getDivisionId(),
      );
      $ProfPrincipals = $this->ProfPrincipalServices->getProfPrincipalsWithFilters($args);
      if (!empty($ProfPrincipals)) {
        $ProfPrincipal = array_shift($ProfPrincipals);
        $profPrincId = $ProfPrincipal->getEnseignantId();
        $this->CompteRendu->setValue(self::FIELD_ENSEIGNANT_ID, $profPrincId);
        $update = true;
      }
    }
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
    $strNewObservationsByMatieres .= '<div class="form-group col-md-3 btn-group-vertical btn-group-sm">';
    $strNewObservationsByMatieres .= $strButtonMatieres;
    $strNewObservationsByMatieres .= '</div>';
    $strNewObservationsByMatieres .= '<div class="form-group col-md-9">';
    $strNewObservationsByMatieres .= '  <div class="tab-content" id="v-pills-tabContent">';
    $strNewObservationsByMatieres .= $strPanelMatieres;
    $strNewObservationsByMatieres .= '  </div>';
    $strNewObservationsByMatieres .= '</div>';
    $strNewObservationsByMatieres .= '</div>';


    //////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////
    // Initialisation des Beans pour construire les listes déroulantes.
    $AnneeScolaireBean = new AnneeScolaireBean();
    $DivisionBean = new DivisionBean();
    $AdministrationBean = new AdministrationBean();
    $EnseignantBean = new EnseignantBean();

    if ($update) {
      $this->CompteRenduServices->updateLocal($this->CompteRendu);
    }

    //////////////////////////////////////////////////////////////////
    // On enrichi le template puis on le restitue.
    $args = array(
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
      // Input NbEleves - 8
      $this->getInput(self::FIELD_NBELEVES, true, array(), true),
      // Input DateConseil - 9
      $this->getInput(self::FIELD_DATECONSEIL, true, array(self::ATTR_PLACEHOLDER=>self::FORMAT_DATE_JJMMAAAA), true),
      // Input Premier Parent - 10
      $this->getInput(self::FIELD_PARENT1, true, array(), true),
      // Input Deuxième Parent - 11
      $this->getInput(self::FIELD_PARENT2, false, array(), true),
      // Input Premier Elève - 12
      $this->getInput(self::FIELD_ENFANT1, true, array(), true),
      // Input Deuxième Elève - 13
      $this->getInput(self::FIELD_ENFANT2, false, array(), true),
      // Textarea Bilan Prof Principal - 14
      $this->getTextArea(self::FIELD_BILANPROFPRINCIPAL, true, true),
      // Textarea Bilan Délégués Elèves - 15
      $this->getTextArea(self::FIELD_BILANELEVES, true, true),
      // Textarea Bilan Délégués Parents - 16
      $this->getTextArea(self::FIELD_BILANPARENTS, true, true),
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
    );
    return $this->getRender($this->urlTemplate, $args);
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
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getInput($field, $isMandatory=false, $extraArgs=array(), $isAjaxUpload=false)
  {
    $id = $this->CompteRendu->getId();
    switch ($field) {
      case self::FIELD_ANNEESCOLAIRE_ID :
        $value = $this->CompteRendu->getAnneeScolaire()->getAnneeScolaire();
      break;
      case self::FIELD_DIVISION_ID :
        $value = $this->CompteRendu->getDivision()->getLabelDivision();
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
