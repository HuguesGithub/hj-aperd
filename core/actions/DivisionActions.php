<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * DivisionActions
 * @author Hugues
 * @version 1.21.06.18
 * @since 1.21.06.08
 */
class DivisionActions extends LocalActions
{
  /**
   * @version 1.21.06.08
   * @since 1.21.06.08
   */
  public function __construct()
  {
    parent::__construct();
    $this->DivisionServices = new DivisionServices();
  }
  /**
   * @param string $actionType
   * @param mixed $params
   * @version 1.21.06.12
   * @since 1.21.06.08
   */
  public static function dealWithStatic($actionType, &$params=null)
  {
    $Act = new DivisionActions();
    switch ($actionType) {
      case self::CST_EXPORT :
        return $Act->exportDivision($params);
      break;
      case self::CST_IMPORT :
        return $Act->importDivision($params);
      break;
      default :
        return 'Erreur dans DivisionActions > dealWithStatic [<strong>'.$actionType.'</strong>] non défini.';
      break;
    }
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.06.08
   * @since 1.21.06.08
   */
  public function exportDivision($arrIds)
  {
    $arrToExport = array();
    $Division = new Division();
    // On récupère l'entête
    $arrToExport[] = $Division->getCsvEntete();
    // On récupère les données de tous les objets sélectionnés
    foreach ($arrIds as $id) {
      $Division = $this->DivisionServices->selectLocal($id);
      $arrToExport[] = $Division->toCsv();
    }
    // On retourne le message de réussite.
    return $this->exportFile($arrToExport, ucfirst(self::PAGE_DIVISION));
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.06.18
   * @since 1.21.06.08
   */
  public function importDivision(&$params)
  {
    $fileContent = $this->importFile(self::PAGE_DIVISION);
    $arrContent  = explode(self::EOL, $fileContent);
    $rowContent = array_shift($arrContent);
    $Division    = new Division();
    $hasErrors  = $Division->controleEntete($rowContent, $notif, $msg);

    if (!$hasErrors) {
      while (!empty($arrContent) && !$hasErrors) {
        $rowContent = array_shift($arrContent);
        $hasErrors  = $Division->controleImportRow($rowContent, self::SEP, $notif, $msg);
      }
    }

    if (!$hasErrors) {
      $notif = self::NOTIF_SUCCESS;
      $msg   = self::MSG_SUCCESS_IMPORT;
    }
    $params['notif'] = $notif;
    $params['msg']   = $msg;

    $strRows = '';
    $Divisions = $this->DivisionServices->getDivisionsWithFilters();
    foreach ($Divisions as $Division) {
      $Bean = $Division->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($Division->getId(), $this->arrIds));
    }
    return $strRows;
  }

}
