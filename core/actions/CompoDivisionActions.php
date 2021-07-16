<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CompoDivisionActions
 * @author Hugues
 * @version 1.21.07.16
 * @since 1.21.07.15
 */
class CompoDivisionActions extends LocalActions
{
  /**
   * @version 1.21.07.15
   * @since 1.21.07.15
   */
  public function __construct()
  {
    parent::__construct();
    $this->CompoDivisionServices = new CompoDivisionServices();
  }
  /**
   * @param string $actionType
   * @param mixed $params
   * @version 1.21.07.15
   * @since 1.21.07.15
   */
  public static function dealWithStatic($actionType, &$params=null)
  {
    $Act = new CompoDivisionActions();
    switch ($actionType) {
      case self::CST_EXPORT :
        return $Act->exportCompoDivision($params);
      break;
      case self::CST_IMPORT :
        return $Act->importCompoDivision($params);
      break;
      default :
        return 'Erreur dans CompoDivisionActions > dealWithStatic [<strong>'.$actionType.'</strong>] non défini.';
      break;
    }
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.07.16
   * @since 1.21.07.15
   */
  public function exportCompoDivision($arrIds)
  {
    $arrToExport = array();
    $CompoDivision = new CompoDivision();
    // On récupère l'entête
    $arrToExport[] = $CompoDivision->getCsvEntete();
    // On récupère les données de tous les objets sélectionnés
    foreach ($arrIds as $id) {
      $CompoDivision = $this->CompoDivisionServices->selectLocal($id);
      $arrToExport[] = $CompoDivision->toCsv();
    }
    // On retourne le message de réussite.
    return $this->exportFile($arrToExport, ucfirst(self::PAGE_COMPO_DIVISION));
  }
  /**
   * @param array $params
   * @return string
   * @version 1.21.07.15
   * @since 1.21.07.15
   */
  public function importCompoDivision(&$params)
  {
    $fileContent = $this->importFile(self::PAGE_COMPO_DIVISION);
    $arrContent  = explode(self::EOL, $fileContent);
    $rowContent = array_shift($arrContent);
    $CompoDivision    = new CompoDivision();
    $hasErrors  = $CompoDivision->controleEntete($rowContent, $notif, $msg);

    if (!$hasErrors) {
      while (!empty($arrContent) && !$hasErrors) {
        $rowContent = array_shift($arrContent);
        $hasErrors  = $CompoDivision->controleImportRow($rowContent, self::SEP, $notif, $msg);
      }
    }

    if (!$hasErrors) {
      $notif = self::NOTIF_SUCCESS;
      $msg   = self::MSG_SUCCESS_IMPORT;
    }
    $params['notif'] = $notif;
    $params['msg']   = $msg;

    $strRows = '';
    $CompoDivisions = $this->CompoDivisionServices->getCompoDivisionsWithFilters();
    foreach ($CompoDivisions as $CompoDivision) {
      $Bean = $CompoDivision->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($CompoDivision->getId(), $this->arrIds));
    }
    return $strRows;
  }

}
