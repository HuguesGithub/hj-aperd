<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EleveServices
 * @author Hugues
 * @version 1.21.06.04
 * @since 1.21.06.04
 */
class EleveServices extends LocalServices
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * L'objet Dao pour faire les requêtes
   * @var EleveDaoImpl $Dao
   */
  protected $Dao;

  //////////////////////////////////////////////////
  // CONSTRUCT
  //////////////////////////////////////////////////
  /**
   * Class constructor
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function __construct()
  {
    $this->Dao = new EleveDaoImpl();
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @param array $arrFilters
   * @return array
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, '%'.$this->getValueToSearch($arrFilters, self::FIELD_NOMELEVE).'%');
    array_push($arrParams, '%'.$this->getValueToSearch($arrFilters, self::FIELD_PRENOMELEVE).'%');
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_DIVISION_ID));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_DELEGUE));
    return $arrParams;
  }
  /**
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getElevesWithFilters($arrFilters=array(), $orderby=self::FIELD_NOMELEVE, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }
  /**
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   * @version 1.21.06.19
   * @since 1.21.06.19
   */
  public function getElevesWithFilteredSearch($arrFilters=array(), $orderby=self::FIELD_NOMELEVE, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->getElevesWithFilteredSearch(__FILE__, __LINE__, $arrParams);
  }
  /**
   * @param string $ins
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function deleteIn($ins)
  { $this->Dao->deleteIn($ins); }
}
