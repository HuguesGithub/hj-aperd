<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe BilanMatiereBean
 * @author Hugues
 * @version 1.21.06.29
 * @since 1.21.06.01
 */
class BilanMatiereBean extends LocalBean
{
  protected $urlFragmentObservationMatiere = 'web/pages/public/fragments/fragment-observation-matiere.php';

  /**
   * Class Constructor
   * @param BilanMatiere $BilanMatiere
   * @version 1.00.00
   * @since 1.00.00
   */
  public function __construct($BilanMatiere='')
  {
    $this->BilanMatiere = ($BilanMatiere=='' ? new BilanMatiere() : $BilanMatiere);
    $this->EnseignantServices = new EnseignantServices();
  }
  /**
   * @return string
   * @version 1.21.06.29
   * @since 1.21.06.01
   */
  public function getFragmentObservationMatiere()
  {
    /////////////////////////////////////////////////////////////////////////
    // On initialise les Bean nécessaires pour les menus déroulants.
    $MatiereBean = new MatiereBean();
    $EnseignantBean = new EnseignantBean();
    /////////////////////////////////////////////////////////////////////////
    // On construit le menu déroulant du statut.
    $optionsSelectStatus  = $this->getDefaultOption();
    $optionsSelectStatus .= $this->getLocalOption('Présent&bull;e', 'P', $this->BilanMatiere->getStatus());
    $optionsSelectStatus .= $this->getLocalOption('Absent&bull;e', 'A', $this->BilanMatiere->getStatus());
    $optionsSelectStatus .= $this->getLocalOption('Excusé&bull;e', 'E', $this->BilanMatiere->getStatus());
    // Et on construit le Textarea
    $attributes = array(
      self::ATTR_NAME  => self::FIELD_OBSERVATIONS.'[]',
      self::ATTR_CLASS => self::CST_MD_TEXTAREA,
      self::ATTR_ROWS  => 3,
    );
    $strTextArea = $this->getBalise(self::TAG_TEXTAREA, $this->BilanMatiere->getObservations(), $attributes);

    $args = array(
      // Identifiant de l'observation - 1
      '',
      // Menu déroulant des matières - 2
      $MatiereBean->getSelect(array('tag'=>self::FIELD_MATIERE_ID.'s[]', 'selectedId'=>$this->BilanMatiere->getMatiereId())),
      // Menu déroulant des enseignants - 3
      $EnseignantBean->getSelect(array('tag'=>self::FIELD_ENSEIGNANT_ID.'s[]', 'selectedId'=>$this->BilanMatiere->getEnseignantId())),
      // Menu déroulant des statuts - 4
      $this->getBalise(self::TAG_SELECT, $optionsSelectStatus, array(self::ATTR_NAME=>'status[]', self::ATTR_CLASS=>self::CST_MD_SELECT)),
      // Textarea de saisie des observations - 5
      $strTextArea,
      // Le textarea est-il renseigné ? - 6
      (!empty($this->BilanMatiere->getObservations()) ? 'active' : ''),
    );
    return $this->getRender($this->urlFragmentObservationMatiere, $args);
  }

  /**
   * @param string $strButtonMatires
   * @param string $strPanelMatieres
   * @param boolean $isFirstButton
   * @version 1.21.06.29
   * @since 1.21.06.01
   */
  public function getBilanMatiere(&$strButtonMatieres, &$strPanelMatieres, &$isFirstButton=false)
  {
    /////////////////////////////////////////////////////////////////////////
    // Initialisation des variables
    $bilanMatiereId = $this->BilanMatiere->getId();
    $status         = $this->BilanMatiere->getStatus();
    $matiereId      = $this->BilanMatiere->getMatiereId();
    $observations   = $this->BilanMatiere->getObservations();
    $moyenneDivision = $this->BilanMatiere->getMoyenneDivision();

    $CompteRendu = $this->BilanMatiere->getCompteRendu();
    $Division    = $CompteRendu->getDivision();
    $Enseignants = $this->EnseignantServices->getEnseignantByMatiereAndDivision($matiereId, $Division->getId());
    $Enseignant = array_shift($Enseignants);

    if ($status=='' || $Enseignant->getId()=='') {
      $badgeStatus = 'danger';
    } elseif ($observations=='' || $moyenneDivision=='' || $moyenneDivision==0) {
      $badgeStatus = 'warning';
    } else {
      $badgeStatus = 'success';
    }
    // Fin initialisation
    /////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////////
    // On construit l'élément Bouton en rapport avec la Matière
    $urlButtonBilanMatiere = 'web/pages/public/fragments/button-bilan-matiere.php';
    $argsBbm = array(
      // Est-ce le premier bouton ? - 1
      ($isFirstButton?' active':''),
      // Identifiant de la Matière - 2
      $matiereId,
      // Statut du Badge - 3
      $badgeStatus,
      // Libellé de la Matière - 4
      $this->BilanMatiere->getMatiere()->getLabelMatiere(),
    );
    $strButtonMatieres .= $this->getRender($urlButtonBilanMatiere, $argsBbm);
    // Fin du Bouton
    /////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////////
    // On construit l'élément Panel en rapport avec la Matière
    $urlPanelBilanMatiere = 'web/pages/public/fragments/panel-bilan-matiere.php';

    // Initialisation des éléments à afficher dans le Panel
    // Pour la liste déroulante Enseignant
    $EnseignantBean = new EnseignantBean();
    $argEnseignants = array(
      self::ATTR_NAME  => 'enseignantIds[]',
      self::ATTR_CLASS => self::CST_MD_SELECT, //.' ajaxUpload',
      self::ATTR_ID    => 'enseignant-'.$matiereId,
      self::ATTR_READONLY => '',
      'selectedId'     => $Enseignant->getId(),
    );
    // Pour la liste déroulante des Statuts
    $optionsSelectStatus  = $this->getDefaultOption();
    $optionsSelectStatus .= $this->getLocalOption('Présent&bull;e', 'P', $status);
    $optionsSelectStatus .= $this->getLocalOption('Absent&bull;e', 'A', $status);
    $optionsSelectStatus .= $this->getLocalOption('Excusé&bull;e', 'E', $status);
    $argStatuts = array(
      self::ATTR_NAME=>'status[]',
      self::ATTR_CLASS=>self::CST_MD_SELECT.self::CST_BLANK.self::AJAX_UPLOAD,
      self::ATTR_ID=>'statut-'.$matiereId
    );
    // Pour l'input de la Moyenne
    $argMoyennes = array(
      self::ATTR_TYPE  => self::CST_TEXT,
      self::ATTR_NAME  => 'moyennes[]',
      self::ATTR_CLASS => self::CST_FORMCONTROL.self::CST_BLANK.self::AJAX_UPLOAD,
      self::ATTR_ID    => 'moyenne-'.$matiereId,
      self::ATTR_VALUE => number_format($this->BilanMatiere->getMoyenneDivision(), 2, '.', ''),
    );
    // Pour le Textarea
    $attributes = array(
      self::ATTR_NAME  => self::FIELD_OBSERVATIONS.'[]',
      self::ATTR_CLASS => self::CST_MD_TEXTAREA.self::CST_BLANK.self::AJAX_UPLOAD,
      self::ATTR_ROWS  => 3,
    );
    /////////////////////////////////////////////////////////////////////////

    $argsPbm = array(
      // Est-ce le premier panel ? - 1
      ($isFirstButton?' show active':''),
      // Identifiant de la Matière - 2
      $matiereId,
      // Identifiant du BilanMatière - 3
      $bilanMatiereId,
      // Liste déroulante des Enseignants - 4
      $EnseignantBean->getSelect($argEnseignants),
      // Liste déroulante des Statuts - 5
      $this->getBalise(self::TAG_SELECT, $optionsSelectStatus, $argStatuts),
      // Input des Moyennes - 6
      $this->getBalise(self::TAG_INPUT, '', $argMoyennes),
      // Textarea de l'observation - 7
      $this->getBalise(self::TAG_TEXTAREA, $observations, $attributes),
      // Classe sur le Textarea - 8
      ($observations!=''?'active':''),
    );
    $strPanelMatieres  .= $this->getRender($urlPanelBilanMatiere, $argsPbm);
    // Fin du Panel
    /////////////////////////////////////////////////////////////////////////

    // On met à jour isFirstButton pour désactiver le tag active.
    $isFirstButton = false;
  }

}
