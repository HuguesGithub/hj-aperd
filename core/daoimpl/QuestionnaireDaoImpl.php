<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe QuestionnaireDaoImpl
 * @author Hugues
 * @version 1.21.06.09
 * @since 1.21.06.09
 */
class QuestionnaireDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('Questionnaire'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = Questionnaire::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $ins
   */
  public function deleteIn($ins)
  {
    $request = $this->delete.$this->fromRequest.'WHERE configKey IN ('.$ins.');';
    MySQL::wpdbQuery($request);
    echo "[".MySQL::wpdbLastQuery()."]]";
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Questionnaire
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($arrParams, new Questionnaire()); }
}
