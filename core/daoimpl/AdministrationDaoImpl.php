<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdministrationDaoImpl
 * @author Hugues
 * @version 1.21.06.10
 * @since 1.21.06.10
 */
class AdministrationDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('Administration'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = Administration::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Administration
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($arrParams, new Administration()); }
  /**
   * @param string $ins
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function deleteIn($ins)
  {
    $request = $this->delete.$this->fromRequest.'WHERE id IN ('.$ins.');';
    MySQL::wpdbQuery($request);
  }
}
