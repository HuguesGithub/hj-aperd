<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CompteRenduDaoImpl
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class CompteRenduDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('CompteRendu'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = CompteRendu::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|CompteRendu
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($arrParams, new CompteRendu()); }
}
