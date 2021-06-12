<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * ParentDelegueActions
 * @author Hugues
 * @version 1.21.06.12
 * @since 1.21.06.12
 */
class ParentDelegueActions extends LocalActions
{
  /**
   * @version 1.21.06.12
   * @since 1.21.06.12
   */
  public function __construct()
  {
    parent::__construct();
    $this->ParentDelegueServices = new ParentDelegueServices();
  }
  /**
   * @param string $actionType
   * @param mixed $params
   * @version 1.21.06.12
   * @since 1.21.06.12
   */
  public static function dealWithStatic($actionType, &$params=null)
  {
    $Act = new ParentDelegueActions();
    switch ($actionType) {
      case self::CST_EXPORT :
        return $Act->exportParentDelegue($params);
      break;
      case self::CST_IMPORT :
        return $Act->importParentDelegue($params);
      break;
      default :
        return 'Erreur dans ParentDelegueActions > dealWithStatic [<strong>'.$actionType.'</strong>] non défini.';
      break;
    }
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.06.12
   * @since 1.21.06.12
   */
  public function exportParentDelegue($arrIds)
  {
    $arrToExport = array();
    $ParentDelegue = new ParentDelegue();
    // On récupère l'entête
    $arrToExport[] = $ParentDelegue->getCsvEntete();
    // On récupère les données de tous les objets sélectionnés
    foreach ($arrIds as $id) {
      $ParentDelegue = $this->ParentDelegueServices->selectLocal($id);
      $arrToExport[] = $ParentDelegue->toCsv();
    }
    // On retourne le message de réussite.
    return $this->exportFile($arrToExport, ucfirst(self::PAGE_PARENT_DELEGUE));
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.06.12
   * @since 1.21.06.12
   */
  public function importParentDelegue(&$params)
  {
    $fileContent = $this->importFile(self::PAGE_PARENT_DELEGUE);
    $arrContent  = explode(self::EOL, $fileContent);
    $rowContent  = array_shift($arrContent);
    $ParentDelegue = new ParentDelegue();
    $hasErrors   = $ParentDelegue->controleEntete($rowContent, $notif, $msg);

    if (!$hasErrors) {
      while (!empty($arrContent) && !$hasErrors) {
        $rowContent = array_shift($arrContent);
        $hasErrors  = $ParentDelegue->controleImportRow($rowContent, self::SEP, $notif, $msg);
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
