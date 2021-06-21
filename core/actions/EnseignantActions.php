<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * EnseignantActions
 * @author Hugues
 * @version 1.21.06.21
 * @since 1.21.06.21
 */
class EnseignantActions extends LocalActions
{
  /**
   * @version 1.21.06.21
   * @since 1.21.06.21
   */
  public function __construct()
  {
    parent::__construct();
    $this->EnseignantServices = new EnseignantServices();
  }
  /**
   * @param string $actionType
   * @param mixed $params
   * @version 1.21.06.21
   * @since 1.21.06.21
   */
  public static function dealWithStatic($actionType, &$params=null)
  {
    $Act = new EnseignantActions();
    switch ($actionType) {
      case self::CST_EXPORT :
        return $Act->exportEnseignant($params);
      break;
      case self::CST_IMPORT :
        return $Act->importEnseignant($params);
      break;
      default :
        return 'Erreur dans EnseignantActions > dealWithStatic [<strong>'.$actionType.'</strong>] non défini.';
      break;
    }
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.06.21
   * @since 1.21.06.21
   */
  public function exportEnseignant($arrIds)
  {
    $arrToExport = array();
    $Enseignant = new Enseignant();
    // On récupère l'entête
    $arrToExport[] = $Enseignant->getCsvEntete();
    // On récupère les données de tous les objets sélectionnés
    foreach ($arrIds as $id) {
      $Enseignant = $this->EnseignantServices->selectLocal($id);
      $arrToExport[] = $Enseignant->toCsv();
    }
    // On retourne le message de réussite.
    return $this->exportFile($arrToExport, ucfirst(self::PAGE_ENSEIGNANT));
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.06.21
   * @since 1.21.06.21
   */
  public function importEnseignant(&$params)
  {
    $fileContent = $this->importFile(self::PAGE_ENSEIGNANT);
    $arrContent  = explode(self::EOL, $fileContent);
    $rowContent  = array_shift($arrContent);
    $Enseignant  = new Enseignant();
    $hasErrors   = $Enseignant->controleEntete($rowContent, $notif, $msg);

    if (!$hasErrors) {
      while (!empty($arrContent) && !$hasErrors) {
        $rowContent = array_shift($arrContent);
        $hasErrors  = $Enseignant->controleImportRow($rowContent, self::SEP, $notif, $msg);
      }
    }

    if (!$hasErrors) {
      $notif = self::NOTIF_SUCCESS;
      $msg   = self::MSG_SUCCESS_IMPORT;
    }
    $params['notif'] = $notif;
    $params['msg']   = $msg;

    $strRows = '';
    $Enseignants = $this->EnseignantServices->getEnseignantsWithFilters();
    foreach ($Enseignants as $Enseignant) {
      $Bean = $Enseignant->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($Enseignant->getId(), $this->arrIds));
    }
    return $strRows;
  }

}
