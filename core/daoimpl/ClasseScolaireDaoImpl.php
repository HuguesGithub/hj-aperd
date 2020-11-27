<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ClasseScolaireDaoImpl
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class ClasseScolaireDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('ClasseScolaire'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = ClasseScolaire::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|ClasseScolaire
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($arrParams, new ClasseScolaire()); }
}
