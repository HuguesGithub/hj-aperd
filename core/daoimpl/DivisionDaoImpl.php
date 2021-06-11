<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe DivisionDaoImpl
 * @author Hugues
 * @version 1.21.06.06
 * @since 1.21.06.01
 */
class DivisionDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('Division'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = Division::convertElement($row);
      }
    }
    return $Items;
  }
  public function deleteIn($ins)
  {
    $request = $this->delete.$this->fromRequest.'WHERE id IN ('.$ins.');';
    MySQL::wpdbQuery($request);
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Division
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($arrParams, new Division()); }
}
