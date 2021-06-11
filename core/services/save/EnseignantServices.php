<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EnseignantServices
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.00
 */
class EnseignantServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var EnseignantDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new EnseignantDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_NOMENSEIGNANT));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_MATIERE_ID));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_STATUS));
    return $arrParams;
  }
  /**
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getEnseignantsWithFilters($arrFilters=array(), $orderby=self::FIELD_NOMENSEIGNANT, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }
  public function deleteIn($ins)
  { $this->Dao->deleteIn($ins); }
  /**
   * @param int $id
   * @return Enseignant
   * @version 1.00.00
   * @since 1.00.00
   */
  public function selectLocal($id)
  { return $this->select(__FILE__, __LINE__, $id); }
  /**
   * @param Enseignant $Enseignant
   * @return Enseignant
   * @version 1.00.01
   * @since 1.00.01
   */
  public function updateLocal($Enseignant)
  { return $this->update(__FILE__, __LINE__, $Enseignant); }
  /**
   * @param Enseignant $Enseignant
   * @return Enseignant
   * @version 1.00.01
   * @since 1.00.01
   */
  public function insertLocal($Enseignant)
  { return $this->insert(__FILE__, __LINE__, $Enseignant); }
  /**
   * @param Enseignant $Enseignant
   * @return null
   * @version 1.00.01
   * @since 1.00.01
   */
  public function deleteLocal($Enseignant)
  { return $this->delete(__FILE__, __LINE__, $Enseignant); }
}
