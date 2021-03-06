<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EleveDaoImpl
 * @author Hugues
 * @version 1.21.06.12
 * @since 1.21.06.01
 */
class EleveDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('Eleve'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = Eleve::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Eleve
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($arrParams, new Eleve()); }
  /**
   * @param string $ins
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function deleteIn($ins)
  {
    $request = $this->delete.$this->fromRequest.'WHERE id IN ('.$ins.');';
    MySQL::wpdbQuery($request);
  }
  /**
   * @version 1.21.06.19
   * @since 1.21.06.19
   */
  public function getElevesWithFilteredSearch($file, $line, $arrParams)
  {
    $request = $this->selectRequest.$this->fromRequest.$this->arrConfigs['whereOr'];
    return $this->convertToArray($this->selectEntriesAndLogQuery($file, $line, $request, $arrParams));
  }
}
