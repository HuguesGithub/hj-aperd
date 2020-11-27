<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ProfPrincipalDaoImpl
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class ProfPrincipalDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('ProfPrincipal'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = ProfPrincipal::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|ProfPrincipal
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($arrParams, new ProfPrincipal()); }
}
