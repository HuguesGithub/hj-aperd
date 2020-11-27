<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe BilanMatiereDaoImpl
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class BilanMatiereDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('BilanMatiere'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = BilanMatiere::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|BilanMatiere
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($arrParams, new BilanMatiere()); }
}
