<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageCompteRendusBean
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class WpPageCompteRendusBean extends WpPageBean
{
  protected $urlTemplate = 'web/pages/public/wppage-compte-rendus.php';
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
    $this->CompteRenduServices = new CompteRenduServices();
    $this->BilanMatiereServices = new BilanMatiereServices();
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
        $this->CompteRendu->setId(MySQL::getLastInsertId());
      } elseif (isset($_POST[self::AJAX_SEARCH])) {
        $post = array_merge($_POST, array(self::AJAX_ACTION=>self::AJAX_SEARCH, self::FIELD_CRKEY=>$crKey));
        $this->CompteRendu = CompteRenduActions::dealWithStatic($post);
      }
    } elseif ($crKey!=-1) {
      $post = array_merge($_POST, array(self::AJAX_ACTION=>self::AJAX_SEARCH, self::FIELD_CRKEY=>$crKey));
      $this->CompteRendu = CompteRenduActions::dealWithStatic($post);
    } else {
      $this->CompteRendu = new CompteRendu();
    }
    return $this->getContent();
  }
  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getContent()
  {
    //////////////////////////////////////////////////////////////////
    // Initialisation des Beans pour construire les listes déroulantes.
    $AnneeScolaireBean = new AnneeScolaireBean();
    $ClasseScolaireBean = new ClasseScolaireBean();
    $AdministrationBean = new AdministrationBean();
    $EnseignantBean = new EnseignantBean();
    $BilanMatiereBean = new BilanMatiereBean();

    //////////////////////////////////////////////////////////////////
    // Récupération des Bilans par Matières existants et restitution
    $strObservationsByMatieres = '';
    if ($this->CompteRendu->getId()!='') {
      $attributes = array(self::FIELD_COMPTERENDU_ID=>$this->CompteRendu->getId());
      $BilanMatieres = $this->BilanMatiereServices->getBilanMatieresWithFilters($attributes);
      while (!empty($BilanMatieres)) {
        $BilanMatiere = array_shift($BilanMatieres);
        $strObservationsByMatieres .= $BilanMatiere->getBean()->getFragmentObservationMatiere();
      }
    }
    $strObservationsByMatieres .= $BilanMatiereBean->getFragmentObservationMatiere();

    //////////////////////////////////////////////////////////////////
    // On enrichi le template puis on le restitue.
    $args = array(
      // Menu déroulant Année Scolaire - 1
      $AnneeScolaireBean->getSelect(self::FIELD_ANNEESCOLAIRE_ID, $this->CompteRendu->getValue(self::FIELD_ANNEESCOLAIRE_ID), true),
      // Menu déroulant Classe Scolaire - 2
      $ClasseScolaireBean->getSelect(self::FIELD_CLASSE_ID, $this->CompteRendu->getValue(self::FIELD_CLASSE_ID), true),
      // Menu déroulant Présidence - 3
      $AdministrationBean->getSelect(self::FIELD_ADMINISTRATION_ID, $this->CompteRendu->getValue(self::FIELD_ADMINISTRATION_ID), true),
      // Menu déroulant Prof Principal - 4
      $EnseignantBean->getSelect(self::FIELD_ENSEIGNANT_ID, $this->CompteRendu->getValue(self::FIELD_ENSEIGNANT_ID), true),
      // Premier bloc d'observations par matière - 5
      $strObservationsByMatieres,
      // Menu déroulant pour le trimestre - 6
      $this->getSelectTrimestre(true),
      // Notifications éventuelles - 7
      $this->CompteRendu->getNotifications(),
      // Input NbEleves - 8
      $this->getInput(self::FIELD_NBELEVES, true),
      // Input DateConseil - 9
      $this->getInput(self::FIELD_DATECONSEIL, true, array(self::ATTR_PLACEHOLDER=>self::FORMAT_DATE_JJMMAAAA)),
      // Input Premier Parent - 10
      $this->getInput(self::FIELD_PARENT1, true),
      // Input Deuxième Parent - 11
      $this->getInput(self::FIELD_PARENT2),
      // Input Premier Elève - 12
      $this->getInput(self::FIELD_ENFANT1, true),
      // Input Deuxième Elève - 13
      $this->getInput(self::FIELD_ENFANT2),
      // Textarea Bilan Prof Principal - 14
      $this->getTextArea(self::FIELD_BILANPROFPRINCIPAL, true),
      // Textarea Bilan Délégués Elèves - 15
      $this->getTextArea(self::FIELD_BILANELEVES, true),
      // Textarea Bilan Délégués Parents - 16
      $this->getTextArea(self::FIELD_BILANPARENTS, true),
      // Input Nb Encouragements - 17
      $this->getInput(self::FIELD_NBENCOURAGEMENTS, true),
      // Input Nb Compliments - 18
      $this->getInput(self::FIELD_NBCOMPLIMENTS, true),
      // Input Nb Felicitations - 19
      $this->getInput(self::FIELD_NBFELICITATIONS, true),
      // Input Nb MGC - 20
      $this->getInput(self::FIELD_NBMGCPT, true),
      // Input Nb MGT - 21
      $this->getInput(self::FIELD_NBMGTVL, true),
      // Input Nb MGCT - 22
      $this->getInput(self::FIELD_NBMGCPTTVL, true),
      // Input Date Rédaction - 23
      $this->getInput(self::FIELD_DATEREDACTION, true, array(self::ATTR_PLACEHOLDER=>self::FORMAT_DATE_JJMMAAAA)),
      // Input Auteur Rédaction - 24
      $this->getInput(self::FIELD_AUTEURREDACTION, true),
      // Input Mail de Contact - 25
      $this->getInput(self::FIELD_MAILCONTACT, true),
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
  public function getTextArea($field, $isMandatory=false)
  {
    $id = $this->CompteRendu->getId();
    $value = $this->CompteRendu->getValue($field);
    $classe = self::CST_MD_TEXTAREA.($isMandatory && $value=='' && $id!='' ? ' '.self::NOTIF_IS_INVALID : '');
    $args = array(
      self::ATTR_ID => $field,
      self::ATTR_CLASS => $classe,
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
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getInput($field, $isMandatory=false, $extraArgs=array())
  {
    $id = $this->CompteRendu->getId();
    $value = $this->CompteRendu->getValue($field);
    $classe = self::CST_FORMCONTROL.($isMandatory && $value=='' && $id!='' ? ' '.self::NOTIF_IS_INVALID : '');
    $args = array(
      self::ATTR_TYPE => self::CST_TEXT,
      self::ATTR_CLASS => $classe,
      self::ATTR_ID => $field,
      self::ATTR_NAME =>$field,
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
