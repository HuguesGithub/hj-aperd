<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdministrationActions
 * @author Hugues
 * @version 1.21.06.12
 * @since 1.21.06.10
 */
class AdministrationActions extends LocalActions
{
  /**
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function __construct()
  {
    parent::__construct();
    $this->AdministrationServices = new AdministrationServices();
  }
  /**
   * @param string $actionType
   * @param mixed $params
   * @return string
   * @version 1.21.06.12
   * @since 1.21.06.10
   */
  public static function dealWithStatic($actionType, &$params=null)
  {
    $Act = new AdministrationActions();
    switch ($actionType) {
      case self::CST_EXPORT :
        return $Act->exportAdministration($params);
      break;
      case self::CST_IMPORT :
        return $Act->importAdministration($params);
      break;
      default :
        return 'Erreur dans AdministrationActions > dealWithStatic [<strong>'.$actionType.'</strong>] non défini.';
      break;
    }
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function exportAdministration($arrIds)
  {
    $arrToExport = array();
    $Administration = new Administration();
    // On récupère l'entête
    $arrToExport[] = $Administration->getCsvEntete();
    // On récupère les données de tous les objets sélectionnés
    foreach ($arrIds as $id) {
      $Administration = $this->AdministrationServices->selectLocal($id);
      $arrToExport[] = $Administration->toCsv();
    }
    // On retourne le message de réussite.
    return $this->exportFile($arrToExport, ucfirst(self::PAGE_ADMINISTRATION));
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function importAdministration(&$params)
  {
    $fileContent = $this->importFile(self::PAGE_ADMINISTRATION);
    $arrContent  = explode(self::EOL, $fileContent);
    $rowContent  = array_shift($arrContent);
    $Administration     = new Administration();
    $hasErrors   = $Administration->controleEntete($rowContent, $notif, $msg);

    if (!$hasErrors) {
      while (!empty($arrContent) && !$hasErrors) {
        $rowContent = array_shift($arrContent);
        $hasErrors  = $Administration->controleImportRow($rowContent, self::SEP, $notif, $msg);
      }
    }

    if (!$hasErrors) {
      $notif = self::NOTIF_SUCCESS;
      $msg   = self::MSG_SUCCESS_IMPORT;
    }
    $params['notif'] = $notif;
    $params['msg']   = $msg;
  }

}
