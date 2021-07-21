<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * DataQuestionnaireActions
 * @author Hugues
 * @version 1.21.07.21
 * @since 1.21.07.21
 */
class DataQuestionnaireActions extends LocalActions
{
  /**
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function __construct()
  {
    parent::__construct();
    $this->ConfigQuestionnaireServices = new QuestionnaireServices();
    $this->Services = new DataQuestionnaireServices();
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
    $Act = new DataQuestionnaireActions();
    // Repasser en switch s'il y a un export
    if ($actionType==self::CST_IMPORT) {
      return $Act->importDataQuestionnaire($params);
    } else {
      return 'Erreur dans DataQuestionnaireActions > dealWithStatic [<strong>'.$actionType.'</strong>] non défini.';
    }
  }
  /**
   * @param array $arrIds
   * @return string
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function importDataQuestionnaire(&$params)
  {
    $fileContent    = $this->importFile(self::PAGE_DATA_QUESTIONS);
    $arrContent     = explode("\n", $fileContent);
    $hasErrors = false;

    do {
      $rowContent = array_shift($arrContent);
      if (substr($rowContent, 0, 5)=='"Séq') {
        break;
      }
    } while (!empty($arrContent));

    $arrColumns = array();
    $cpt = -1;
    $arrRef = explode(";", $rowContent);
    foreach ($arrRef as $key=>$value) {
      $cpt++;
      $Questionnaires = $this->ConfigQuestionnaireServices->getQuestionnairesWithFilters(array(self::FIELD_CONFIG_VALUE=>str_replace('"', '', substr($value, 1, -1))));
      if (empty($Questionnaires)) {
        continue;
      }
      $Questionnaire = array_shift($Questionnaires);
      $arrColumns[$Questionnaire->getConfigKey()] = $cpt;

    }
    print_r($arrColumns);

    /*
    $DataQuestionnaire  = new Questionnaire();
    $hasErrors      = $Questionnaire->controleEntete($rowContent, $notif, $msg);
    */
    while (!empty($arrContent)) {
      $rowContent = array_shift($arrContent);
      $arrData = explode(";", $rowContent);

      $arrInsert = array();
      foreach ($arrColumns as $key=>$value) {
        if (in_array($key, array('nomEnfant', 'prenomEnfant', 'nomPrenomParent'))) {
          $arrInsert[$key] = ucwords(strtolower(substr($arrData[$value], 1, -1)));
        } else {
          $arrInsert[$key] = substr($arrData[$value], 1, -1);
        }
      }

      $DataQuestionnaire = new DataQuestionnaire();
      $DataQuestionnaire->setData(serialize($arrInsert));
      $this->Services->insertLocal($DataQuestionnaire);
    }

    if (!$hasErrors) {
      $notif = self::NOTIF_SUCCESS;
      $msg   = self::MSG_SUCCESS_IMPORT;
    }
    $params['notif'] = $notif;
    $params['msg']   = $msg;

    $strRows = '';
    $DataQuestionnaires = $this->Services->getDataQuestionnairesWithFilters();
    foreach ($DataQuestionnaires as $DataQuestionnaire) {
      $Bean = $DataQuestionnaire->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($DataQuestionnaire->getId(), $this->arrIds));
    }
    return $strRows;
  }

}
