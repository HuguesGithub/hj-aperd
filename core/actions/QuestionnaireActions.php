<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * QuestionnaireActions
 * @author Hugues
 * @version 1.21.07.21
 * @since 1.21.07.21
 */
class QuestionnaireActions extends LocalActions
{
  /**
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function __construct()
  {
    parent::__construct();
    $this->QuestionnaireServices = new QuestionnaireServices();
  }
  /**
   * @param string $actionType
   * @param mixed $params
   * @return string
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public static function dealWithStatic($actionType, &$params=null)
  {
    $Act = new QuestionnaireActions();
    switch ($actionType) {
      case self::CST_EXPORT :
        return $Act->exportQuestionnaire($params);
      break;
      case self::CST_IMPORT :
        return $Act->importQuestionnaire($params);
      break;
      default :
        return 'Erreur dans QuestionnaireActions > dealWithStatic [<strong>'.$actionType.'</strong>] non défini.';
      break;
    }
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function exportQuestionnaire($arrIds)
  {
    $arrToExport = array();
    $Questionnaire = new Questionnaire();
    // On récupère l'entête
    $arrToExport[] = $Questionnaire->getCsvEntete();
    // On récupère les données de tous les objets sélectionnés
    foreach ($arrIds as $id) {
      $Questionnaires = $this->QuestionnaireServices->getQuestionnairesWithFilters(array(self::FIELD_CONFIG_KEY=>$id));
      $Questionnaire = array_shift($Questionnaires);
      $arrToExport[] = $Questionnaire->toCsv();
    }
    // On retourne le message de réussite.
    return $this->exportFile($arrToExport, ucfirst(self::PAGE_QUESTIONNAIRE));
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function importQuestionnaire(&$params)
  {
    $fileContent    = $this->importFile(self::PAGE_QUESTIONNAIRE);
    $arrContent     = explode(self::EOL, $fileContent);
    $rowContent     = array_shift($arrContent);
    $Questionnaire  = new Questionnaire();
    $hasErrors      = $Questionnaire->controleEntete($rowContent, $notif, $msg);

    if (!$hasErrors) {
      while (!empty($arrContent) && !$hasErrors) {
        $rowContent = array_shift($arrContent);
        $hasErrors  = $Questionnaire->controleImportRow($rowContent, self::SEP, $notif, $msg);
      }
    }

    if (!$hasErrors) {
      $notif = self::NOTIF_SUCCESS;
      $msg   = self::MSG_SUCCESS_IMPORT;
    }
    $params['notif'] = $notif;
    $params['msg']   = $msg;

    $strRows = '';
    $Questionnaires = $this->QuestionnaireServices->getQuestionnairesWithFilters();
    foreach ($Questionnaires as $Questionnaire) {
      $Bean = $Questionnaire->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($Questionnaire->getConfigKey(), $this->arrIds));
    }
    return $strRows;
  }

}
