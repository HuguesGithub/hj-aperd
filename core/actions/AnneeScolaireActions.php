<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AnneeScolaireActions
 * @author Hugues
 * @version 1.21.06.18
 * @since 1.21.06.10
 */
class AnneeScolaireActions extends LocalActions
{
  /**
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function __construct()
  {
    parent::__construct();
    $this->AnneeScolaireServices = new AnneeScolaireServices();
  }
  /**
   * @param string $actionType
   * @param mixed $params
   * @version 1.21.06.12
   * @since 1.21.06.10
   */
  public static function dealWithStatic($actionType, &$params=null)
  {
    $Act = new AnneeScolaireActions();
    switch ($actionType) {
      case self::CST_EXPORT :
        return $Act->exportAnneeScolaire($params);
      break;
      case self::CST_IMPORT :
        return $Act->importAnneeScolaire($params);
      break;
      default :
        return 'Erreur dans AnneeScolaireActions > dealWithStatic [<strong>'.$actionType.'</strong>] non défini.';
      break;
    }
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function exportAnneeScolaire($arrIds)
  {
    $arrToExport = array();
    $AnneeScolaire = new AnneeScolaire();
    // On récupère l'entête
    $arrToExport[] = $AnneeScolaire->getCsvEntete();
    // On récupère les données de tous les objets sélectionnés
    foreach ($arrIds as $id) {
      $AnneeScolaire = $this->AnneeScolaireServices->selectLocal($id);
      $arrToExport[] = $AnneeScolaire->toCsv();
    }
    // On retourne le message de réussite.
    return $this->exportFile($arrToExport, ucfirst(self::PAGE_ANNEE_SCOLAIRE));
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.06.18
   * @since 1.21.06.10
   */
  public function importAnneeScolaire(&$params)
  {
    $fileContent = $this->importFile(self::PAGE_ANNEE_SCOLAIRE);
    $arrContent  = explode(self::EOL, $fileContent);
    $rowContent  = array_shift($arrContent);
    $AnneeScolaire     = new AnneeScolaire();
    $hasErrors   = $AnneeScolaire->controleEntete($rowContent, $notif, $msg);

    if (!$hasErrors) {
      while (!empty($arrContent) && !$hasErrors) {
        $rowContent = array_shift($arrContent);
        $hasErrors  = $AnneeScolaire->controleImportRow($rowContent, self::SEP, $notif, $msg);
      }
    }

    if (!$hasErrors) {
      $notif = self::NOTIF_SUCCESS;
      $msg   = self::MSG_SUCCESS_IMPORT;
    }
    $params['notif'] = $notif;
    $params['msg']   = $msg;

    $strRows = '';
    $AnneeScolaires = $this->AnneeScolaireServices->getAnneeScolairesWithFilters();
    foreach ($AnneeScolaires as $AnneeScolaire) {
      $Bean = $AnneeScolaire->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($AnneeScolaire->getId(), $this->arrIds));
    }
    return $strRows;
  }

}
