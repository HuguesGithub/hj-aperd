<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdministrationServices
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.00
 */
class AdministrationServices extends LocalServices
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * L'objet Dao pour faire les requêtes
   * @var AdministrationDaoImpl $Dao
   */
  protected $Dao;

  //////////////////////////////////////////////////
  // CONSTRUCT
  //////////////////////////////////////////////////
  /**
   * Class constructor
   */
  public function __construct()
  {
    $this->Dao = new AdministrationDaoImpl();
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @param array $arrFilters
   * @return array
   */
  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_LABELPOSTE));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_NOMTITULAIRE));
    return $arrParams;
  }
  /**
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getAdministrationsWithFilters($arrFilters=array(), $orderby=self::FIELD_LABELPOSTE, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }

}
