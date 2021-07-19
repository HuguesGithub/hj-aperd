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
    if ($status=='') {
      $badgeStatus = 'danger';
    } elseif ($observations=='') {
      $badgeStatus = 'warning';
    } else {
      $badgeStatus = 'success';
    }
    // Fin initialisation
    /////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////////
    // On construit l'élément Bouton en rapport avec la Matière
    $strContentButton  = '<button class="btn btn-outline-secondary'.($isFirstButton?' active':'').'"';
    $strContentButton .= ' id="v-pills-'.$matiereId.'-tab"';
    $strContentButton .= ' data-bs-target="#v-pills-'.$matiereId.'"';
    $strContentButton .= ' type="button" role="tab" aria-controls="v-pills-'.$matiereId.'"';
    $strContentButton .= ' aria-selected="false">';
    $strContentButton .= '<span class="badge-display '.$badgeStatus.'">';
    $strContentButton .= '<span class="badge bg-danger">/!\</span>';
    $strContentButton .= '<span class="badge bg-warning">/!\</span>';
    $strContentButton .= '<span class="badge bg-success">/!\</span>';
    $strContentButton .= '</span>';
    $strContentButton .= $this->BilanMatiere->getMatiere()->getLabelMatiere().'</button>';

    $strButtonMatieres .= $strContentButton;
    // Fin du Bouton
    /////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////////
    // On construit l'élément Panel en rapport avec la Matière
    $strContentPanel  = '<div class="tab-pane fade'.($isFirstButton?' show active':'').'" id="v-pills-'.$matiereId.'" role="tabpanel"';
    $strContentPanel .= ' aria-labelledby="v-pills-'.$matiereId.'-tab" data-bilan-matiere-id="'.$bilanMatiereId.'">';
    $strContentPanel .= '<div class="form-row">';

    /////////////////////////////////////////////////////////////////////////
    // On construit l'input caché de la Matière.
    $strContentPanel .= '<input type="hidden" name="'.self::FIELD_MATIERE_ID.'s[]" value="'.$matiereId.'"/>';

    /////////////////////////////////////////////////////////////////////////
    // On construit le menu déroulant du statut.
    $optionsSelectStatus  = $this->getDefaultOption();
    $optionsSelectStatus .= $this->getLocalOption('Présent&bull;e', 'P', $status);
    $optionsSelectStatus .= $this->getLocalOption('Absent&bull;e', 'A', $status);
    $optionsSelectStatus .= $this->getLocalOption('Excusé&bull;e', 'E', $status);

    $strContentPanel .= '<div class="form-group col-md-4">';
    $strContentPanel .= '<label for="statut-'.$matiereId.'">Statut</label>';
    $args = array(
      self::ATTR_NAME=>'status[]',
      self::ATTR_CLASS=>self::CST_MD_SELECT.' ajaxUpload',
      self::ATTR_ID=>'statut-'.$matiereId
    );
    $strContentPanel .= $this->getBalise(self::TAG_SELECT, $optionsSelectStatus, $args).'</div>';

    /////////////////////////////////////////////////////////////////////////
    // Et on construit le Textarea
    $strContentPanel .= '<div class="form-group col-md-12">';
    $attributes = array(
      self::ATTR_NAME  => self::FIELD_OBSERVATIONS.'[]',
      self::ATTR_CLASS => self::CST_MD_TEXTAREA.' ajaxUpload',
      self::ATTR_ROWS  => 3,
    );
    $strContentPanel .= $this->getBalise(self::TAG_TEXTAREA, $observations, $attributes);
    $strContentPanel .= '<label class="'.($observations!=''?'active':'').'" for="observation-'.$matiereId.'">Observations</label>';
    $strContentPanel .= '</div></div></div>';

    $strPanelMatieres  .= $strContentPanel;
    // Fin du Panel
    /////////////////////////////////////////////////////////////////////////

    // On met à jour isFirstButton pour désactiver le tag active.
    $isFirstButton = false;
  }

}
