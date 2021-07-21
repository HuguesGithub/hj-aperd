<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EnseignantServices
 * @author Hugues
 * @version 1.21.07.20
 * @since 1.21.06.04
 */
class EnseignantServices extends LocalServices
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * L'objet Dao pour faire les requêtes
   * @var EnseignantDaoImpl $Dao
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
    $this->Dao = new EnseignantDaoImpl();
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @param array $arrFilters
   * @return array
   * @version 1.21.07.06
   * @since 1.21.06.04
   */
  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_NOMENSEIGNANT));
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
  public function getEnseignantsWithFilters($arrFilters=array(), $orderby=self::FIELD_NOMENSEIGNANT, $order=self::ORDER_ASC)
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
   * @version 1.21.07.06
   * @since 1.21.07.06
   */
  public function getEnseignantsJointsWithFilters($arrFilters=array(), $orderby=self::FIELD_NOMENSEIGNANT, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = array();
    array_push($arrParams[SQL_PARAMS_WHERE], $this->getValueToSearch($arrFilters, self::FIELD_NOMENSEIGNANT));
    array_push($arrParams[SQL_PARAMS_WHERE], $this->getValueToSearch($arrFilters, self::FIELD_MATIERE_ID));
    return $this->Dao->getEnseignantsJointsWithFilters($arrParams);
  }
  /**
   * @param string $ins
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function deleteIn($ins)
  { $this->Dao->deleteIn($ins); }

  /**
   * @param int $matiereId
   * @param int $divisionId
   * @return Eneignant
   * @version 1.21.07.20
   * @since 1.21.07.20
   */
  public function getEnseignantByMatiereAndDivision($matiereId, $divisionId)
  {
    $arrParams[SQL_PARAMS_WHERE] = array();
    array_push($arrParams[SQL_PARAMS_WHERE], $matiereId);
    array_push($arrParams[SQL_PARAMS_WHERE], $divisionId);
    return $this->Dao->getEnseignantByMatiereAndDivision($arrParams);
  }

}
