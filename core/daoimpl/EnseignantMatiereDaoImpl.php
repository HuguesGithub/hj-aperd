<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EnseignantMatiereDaoImpl
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class EnseignantMatiereDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('EnseignantMatiere'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = EnseignantMatiere::convertElement($row);
      }
    }
    return $Items;
  }

  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|EnseignantMatiere
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($arrParams, new EnseignantMatiere()); }
}
