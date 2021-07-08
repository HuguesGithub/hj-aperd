<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EnseignantMatiereBean
 * @author Hugues
 * @version 1.21.07.07
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
  }
  /**
   * @param array $params
   * @return string
   * @version 1.21.07.07
   * @since 1.21.07.07
   */
  public function getSelect($params = array())
  {
    $Objs = $this->EnseignantMatiereServices->getEnseignantMatieresWithFilters();
    $arrSelect = array();
    while (!empty($Objs)) {
      $Obj = array_shift($Objs);
      $matiereId = $Obj->getMatiereId();
      $enseignantId = $Obj->getEnseignantId();
      if (!isset($arrSelect[$matiereId])) {
        $arrSelect[$matiereId] = array();
      }
      if (!isset($arrSelect[$matiereId][$enseignantId])) {
        array_push($arrSelect[$matiereId], $enseignantId);
      }
    }
    $params['Objs'] = $this->EnseignantMatiereServices->getEnseignantMatieresWithFilters();
    echo "<pre>";
    print_r($arrSelect);
    echo "</pre>";
    return 'WIP';
    return parent::getSelect($params);
  }
}
