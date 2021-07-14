<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EnseignantMatiereBean
 * @author Hugues
 * @version 1.21.07.08
 * @since 1.21.07.07
 */
class EnseignantMatiereBean extends LocalBean
{
  /**
   * Class Constructor
   * @param EnseignantMatiere $EnseignantMatiere
   * @version 1.21.07.07
   * @since 1.21.07.07
   */
  public function __construct($EnseignantMatiere='')
  {
    $this->EnseignantMatiere = ($EnseignantMatiere=='' ? new EnseignantMatiere() : $EnseignantMatiere);
    $this->EnseignantMatiereServices = new EnseignantMatiereServices();
    $this->EnseignantServices = new EnseignantServices();
    $this->MatiereServices = new MatiereServices();
  }
  /**
   * @param array $params
   * @return string
   * @version 1.21.07.08
   * @since 1.21.07.07
   */
  public function getSelect($params = array())
  {
    $Objs = $this->EnseignantMatiereServices->getEnseignantMatieresWithFilters();
    $arrSelect = array();
    while (!empty($Objs)) {
      $Obj = array_shift($Objs);

      // On récupère la Matière et son label
      $matiereId = $Obj->getMatiereId();
      $Matiere = $this->MatiereServices->selectLocal($matiereId);

      // On récupère l'Enseignant et son nom
      $enseignantId = $Obj->getEnseignantId();
      $Enseignant = $this->EnseignantServices->selectLocal($enseignantId);

      if (!isset($arrSelect[$matiereId])) {
        $arrSelect[$matiereId]['Enseignants'] = array();
        $arrSelect[$matiereId]['Matiere'] = $Matiere;
      }
      array_push($arrSelect[$matiereId]['Enseignants'], $Enseignant);
    }
    $strContentSelect = '<option value="">Choisir...</option>';
    foreach ($arrSelect as $matiereId=>$Obj) {
      $Matiere = $Obj['Matiere'];
      $strContentSelect .= '<optgroup label="'.$Matiere->getLabelMatiere().'">';
      $Enseignants = $Obj['Enseignants'];
      foreach ($Enseignants as $Enseignant) {
        $strContentSelect .= '<option value="'.$Matiere->getId().'|'.$Enseignant->getId().'">'.$Enseignant->getFullName().'</option>';
      }
      $strContentSelect .= '</optgroup>';
    }
    return '<select name="enseignantMatiereId" class="form-control md-select" required>'.$strContentSelect.'</select>';
  }
}
