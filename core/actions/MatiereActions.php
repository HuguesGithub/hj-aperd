<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * MatiereActions
 * @author Hugues
 * @version 1.21.06.18
 * @since 1.21.06.08
 */
class MatiereActions extends LocalActions
{
  /**
   * @version 1.21.06.08
   * @since 1.21.06.08
   */
  public function __construct()
  {
    parent::__construct();
    $this->MatiereServices = new MatiereServices();
  }
  /**
   * @param string $actionType
   * @param mixed $params
   * @version 1.21.06.12
   * @since 1.21.06.08
   */
  public static function dealWithStatic($actionType, &$params=null)
  {
    $Act = new MatiereActions();
    switch ($actionType) {
      case self::CST_EXPORT :
        return $Act->exportMatiere($params);
      break;
      case self::CST_IMPORT :
        return $Act->importMatiere($params);
      break;
      default :
        return 'Erreur dans MatiereActions > dealWithStatic [<strong>'.$actionType.'</strong>] non défini.';
      break;
    }
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.06.08
   * @since 1.21.06.08
   */
  public function exportMatiere($arrIds)
  {
    $arrToExport = array();
    $Matiere = new Matiere();
    // On récupère l'entête
    $arrToExport[] = $Matiere->getCsvEntete();
    // On récupère les données de tous les objets sélectionnés
    foreach ($arrIds as $id) {
      $Matiere = $this->MatiereServices->selectLocal($id);
      $arrToExport[] = $Matiere->toCsv();
    }
    // On retourne le message de réussite.
    return $this->exportFile($arrToExport, ucfirst(self::PAGE_MATIERE));
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.06.18
   * @since 1.21.06.08
   */
  public function importMatiere(&$params)
  {
    $fileContent = $this->importFile(self::PAGE_MATIERE);
    $arrContent  = explode(self::EOL, $fileContent);
    $rowContent  = array_shift($arrContent);
    $Matiere     = new Matiere();
    $hasErrors   = $Matiere->controleEntete($rowContent, $notif, $msg);

    if (!$hasErrors) {
      while (!empty($arrContent) && !$hasErrors) {
        $rowContent = array_shift($arrContent);
        $hasErrors  = $Matiere->controleImportRow($rowContent, self::SEP, $notif, $msg);
      }
    }

    if (!$hasErrors) {
      $notif = self::NOTIF_SUCCESS;
      $msg   = self::MSG_SUCCESS_IMPORT;
    }
    $params['notif'] = $notif;
    $params['msg']   = $msg;

    $strRows = '';
    $Matieres = $this->MatiereServices->getMatieresWithFilters();
    foreach ($Matieres as $Matiere) {
      $Bean = $Matiere->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($Matiere->getId(), $this->arrIds));
    }
    return $strRows;
  }

}
