<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ParentDelegueServices
 * @author Hugues
 * @version 1.21.06.12
 * @since 1.21.06.11
 */
class ParentDelegueServices extends LocalServices
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * L'objet Dao pour faire les requêtes
   * @var ParentDelegueDaoImpl $Dao
   */
  protected $Dao;

  //////////////////////////////////////////////////
  // CONSTRUCT
  //////////////////////////////////////////////////
  /**
   * Class constructor
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function __construct()
  {
    $this->Dao = new ParentDelegueDaoImpl();
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @param array $arrFilters
   * @return array
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_PARENT_ID));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_DIVISION_ID));
    return $arrParams;
  }

  /**
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function getParentDeleguesWithFilters($arrFilters=array(), $orderby=self::FIELD_ID, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }

  /**
   * @param array $arrFilters
   * @return null
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function deleteWithFilters($arrFilters=array())
  {
    $arrParams = $this->buildFilters($arrFilters);
    return $this->Dao->deleteWithFilters(__FILE__, __LINE__, $arrParams);
  }
  /**
   * @param string $ins
   * @version 1.21.06.12
   * @since 1.21.06.12
   */
  public function deleteIn($ins)
  { $this->Dao->deleteIn($ins); }
}
