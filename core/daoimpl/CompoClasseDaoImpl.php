<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CompoClasseDaoImpl
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class CompoClasseDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('CompoClasse'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = CompoClasse::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|CompoClasse
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($arrParams, new CompoClasse()); }
}
