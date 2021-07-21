<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe DataQuestionnaireDaoImpl
 * @author Hugues
 * @version 1.21.07.21
 * @since 1.21.07.21
 */
class DataQuestionnaireDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('DataQuestionnaire'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = DataQuestionnaire::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|DataQuestionnaire
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($arrParams, new DataQuestionnaire()); }
}
