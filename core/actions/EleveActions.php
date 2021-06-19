<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * EleveActions
 * @author Hugues
 * @version 1.21.06.18
 * @since 1.21.06.11
 */
class EleveActions extends LocalActions
{
  /**
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function __construct()
  {
    parent::__construct();
    $this->EleveServices = new EleveServices();
  }
  /**
   * @param string $actionType
   * @param mixed $params
   * @version 1.21.06.12
   * @since 1.21.06.11
   */
  public static function dealWithStatic($actionType, &$params=null)
  {
    $Act = new EleveActions();
    switch ($actionType) {
      case self::CST_EXPORT :
        return $Act->exportEleve($params);
      break;
      case self::CST_IMPORT :
        return $Act->importEleve($params);
      break;
      default :
        return 'Erreur dans EleveActions > dealWithStatic [<strong>'.$actionType.'</strong>] non défini.';
      break;
    }
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function exportEleve($arrIds)
  {
    $arrToExport = array();
    $Eleve = new Eleve();
    // On récupère l'entête
    $arrToExport[] = $Eleve->getCsvEntete();
    // On récupère les données de tous les objets sélectionnés
    foreach ($arrIds as $id) {
      $Eleve = $this->EleveServices->selectLocal($id);
      $arrToExport[] = $Eleve->toCsv();
    }
    // On retourne le message de réussite.
    return $this->exportFile($arrToExport, ucfirst(self::PAGE_ELEVE));
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.06.18
   * @since 1.21.06.11
   */
  public function importEleve(&$params)
  {
    $fileContent = $this->importFile(self::PAGE_ELEVE);
    $arrContent  = explode(self::EOL, $fileContent);
    $rowContent  = array_shift($arrContent);
    $Eleve     = new Eleve();
    $hasErrors   = $Eleve->controleEntete($rowContent, $notif, $msg);

    if (!$hasErrors) {
      while (!empty($arrContent) && !$hasErrors) {
        $rowContent = array_shift($arrContent);
        $hasErrors  = $Eleve->controleImportRow($rowContent, self::SEP, $notif, $msg);
      }
    }

    if (!$hasErrors) {
      $notif = self::NOTIF_SUCCESS;
      $msg   = self::MSG_SUCCESS_IMPORT;
    }
    $params['notif'] = $notif;
    $params['msg']   = $msg;

    $strRows = '';
    $Eleves = $this->EleveServices->getElevesWithFilters();
    foreach ($Eleves as $Eleve) {
      $Bean = $Eleve->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($Eleve->getId(), $this->arrIds));
    }
    return $strRows;
  }

}
