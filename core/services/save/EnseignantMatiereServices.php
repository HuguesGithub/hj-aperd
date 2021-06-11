<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EnseignantMatiereServices
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.00
 */
class EnseignantMatiereServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var EnseignantMatiereDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new EnseignantMatiereDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_ENSEIGNANT_ID));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_MATIERE_ID));
    return $arrParams;
  }
  /**
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getEnseignantMatieresWithFilters($arrFilters=array(), $orderby=self::FIELD_ID, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }

  public function deleteWithFilters($arrFilters=array())
  {
    $arrParams = $this->buildFilters($arrFilters);
    return $this->Dao->deleteWithFilters(__FILE__, __LINE__, $arrParams);
  }

  /**
   * @param int $id
   * @return EnseignantMatiere
   * @version 1.00.00
   * @since 1.00.00
   */
  public function selectLocal($id)
  { return $this->select(__FILE__, __LINE__, $id); }
  /**
   * @param EnseignantMatiere $EnseignantMatiere
   * @return EnseignantMatiere
   * @version 1.00.01
   * @since 1.00.01
   */
  public function updateLocal($EnseignantMatiere)
  { return $this->update(__FILE__, __LINE__, $EnseignantMatiere); }
  /**
   * @param EnseignantMatiere $EnseignantMatiere
   * @return EnseignantMatiere
   * @version 1.00.01
   * @since 1.00.01
   */
  public function insertLocal($EnseignantMatiere)
  { return $this->insert(__FILE__, __LINE__, $EnseignantMatiere); }
  /**
   * @param EnseignantMatiere $EnseignantMatiere
   * @return null
   * @version 1.00.01
   * @since 1.00.01
   */
  public function deleteLocal($EnseignantMatiere)
  { return $this->delete(__FILE__, __LINE__, $EnseignantMatiere); }
}
