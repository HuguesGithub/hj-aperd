<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdministrationServices
 * @author Hugues
 * @version 1.21.06.10
 * @since 1.21.06.04
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
   * @version 1.21.06.04
   * @since 1.21.06.04
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
   * @version 1.21.06.04
   * @since 1.21.06.04
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
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getAdministrationsWithFilters($arrFilters=array(), $orderby=self::FIELD_LABELPOSTE, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }
  /**
   * @param string $ins
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function deleteIn($ins)
  { $this->Dao->deleteIn($ins); }

}
