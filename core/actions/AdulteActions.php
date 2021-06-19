<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdulteActions
 * @author Hugues
 * @version 1.21.06.18
 * @since 1.21.06.11
 */
class AdulteActions extends LocalActions
{
  /**
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function __construct()
  {
    parent::__construct();
    $this->AdulteServices = new AdulteServices();
  }
  /**
   * @param string $actionType
   * @param mixed $params
   * @version 1.21.06.12
   * @since 1.21.06.11
   */
  public static function dealWithStatic($actionType, &$params=null)
  {
    $Act = new AdulteActions();
    switch ($actionType) {
      case self::CST_EXPORT :
        return $Act->exportAdulte($params);
      break;
      case self::CST_IMPORT :
        return $Act->importAdulte($params);
      break;
      default :
        return 'Erreur dans AdulteActions > dealWithStatic [<strong>'.$actionType.'</strong>] non défini.';
      break;
    }
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function exportAdulte($arrIds)
  {
    $arrToExport = array();
    $Adulte = new Adulte();
    // On récupère l'entête
    $arrToExport[] = $Adulte->getCsvEntete();
    // On récupère les données de tous les objets sélectionnés
    foreach ($arrIds as $id) {
      $Adulte = $this->AdulteServices->selectLocal($id);
      $arrToExport[] = $Adulte->toCsv();
    }
    // On retourne le message de réussite.
    return $this->exportFile($arrToExport, ucfirst(self::PAGE_PARENT));
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.06.18
   * @since 1.21.06.11
   */
  public function importAdulte(&$params)
  {
    $fileContent = $this->importFile(self::PAGE_PARENT);
    $arrContent  = explode(self::EOL, $fileContent);
    $rowContent  = array_shift($arrContent);
    $Adulte     = new Adulte();
    $hasErrors   = $Adulte->controleEntete($rowContent, $notif, $msg);

    if (!$hasErrors) {
      while (!empty($arrContent) && !$hasErrors) {
        $rowContent = array_shift($arrContent);
        $hasErrors  = $Adulte->controleImportRow($rowContent, self::SEP, $notif, $msg);
      }
    }

    if (!$hasErrors) {
      $notif = self::NOTIF_SUCCESS;
      $msg   = self::MSG_SUCCESS_IMPORT;
    }
    $params['notif'] = $notif;
    $params['msg']   = $msg;

    $strRows = '';
    $Adultes = $this->AdulteServices->getAdultesWithFilters();
    foreach ($Adultes as $Adulte) {
      $Bean = $Adulte->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($Adulte->getId(), $this->arrIds));
    }
    return $strRows;
  }

}
