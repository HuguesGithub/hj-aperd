<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ProfPrincipalServices
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class ProfPrincipalServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var ProfPrincipalDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new ProfPrincipalDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_ANNEESCOLAIRE_ID));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_CLASSE_ID));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_ENSEIGNANT_ID));
    return $arrParams;
  }

  /**
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getProfPrincipalsWithFilters($arrFilters=array(), $orderby=self::FIELD_ID, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }
  /**
   * @param int $id
   * @return ProfPrincipal
   * @version 1.00.00
   * @since 1.00.00
   */
  public function selectLocal($id)
  { return $this->select(__FILE__, __LINE__, $id); }
  /**
   * @param ProfPrincipal $ProfPrincipal
   * @return ProfPrincipal
   * @version 1.00.01
   * @since 1.00.01
   */
  public function updateLocal($ProfPrincipal)
  { return $this->update(__FILE__, __LINE__, $ProfPrincipal); }
  /**
   * @param ProfPrincipal $ProfPrincipal
   * @return ProfPrincipal
   * @version 1.00.01
   * @since 1.00.01
   */
  public function insertLocal($ProfPrincipal)
  { return $this->insert(__FILE__, __LINE__, $ProfPrincipal); }
}
