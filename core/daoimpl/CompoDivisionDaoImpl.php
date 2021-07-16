<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CompoDivisionDaoImpl
 * @author Hugues
 * @version 1.21.07.15
 * @since 1.21.06.01
 */
class CompoDivisionDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('CompoDivision'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = CompoDivision::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|CompoDivision
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($arrParams, new CompoDivision()); }

  /**
   * @param string $ins
   * @version 1.21.07.15
   * @since 1.21.07.15
   */
  public function deleteIn($ins)
  {
    $request = $this->delete.$this->fromRequest.'WHERE id IN ('.$ins.');';
    MySQL::wpdbQuery($request);
  }

}
