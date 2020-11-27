<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe BilanMatiereBean
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
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
    parent::__construct();
    $this->BilanMatiere = ($BilanMatiere=='' ? new BilanMatiere() : $BilanMatiere);
  }
  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
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
      self::ATTR_NAME => self::FIELD_OBSERVATIONS.'[]',
      self::ATTR_CLASS => self::CST_MD_TEXTAREA,
      self::ATTR_ROWS => 3,
    );
    $strTextArea = $this->getBalise(self::TAG_TEXTAREA, $this->BilanMatiere->getObservations(), $attributes);

    $args = array(
      // Identifiant de l'observation - 1
      '',
      // Menu déroulant des matières - 2
      $MatiereBean->getSelect(self::FIELD_MATIERE_ID.'s[]', $this->BilanMatiere->getMatiereId()),
      // Menu déroulant des enseignants - 3
      $EnseignantBean->getSelect(self::FIELD_ENSEIGNANT_ID.'s[]', $this->BilanMatiere->getEnseignantId()),
      // Menu déroulant des statuts - 4
      $this->getBalise(self::TAG_SELECT, $optionsSelectStatus, array(self::ATTR_NAME=>'status[]', self::ATTR_CLASS=>self::CST_MD_SELECT)),
      // Textarea de saisie des observations - 5
      $strTextArea,
      // Le textarea est-il renseigné ? - 6
      (!empty($this->BilanMatiere->getObservations()) ? 'active' : ''),
    );
    return $this->getRender($this->urlFragmentObservationMatiere, $args);
  }

}
