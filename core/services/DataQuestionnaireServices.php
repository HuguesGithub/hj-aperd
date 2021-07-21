<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe DataQuestionnaireServices
 * @author Hugues
 * @version 1.21.07.21
 * @since 1.21.07.21
 */
class DataQuestionnaireServices extends LocalServices
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * L'objet Dao pour faire les requêtes
   * @var DataQuestionnaireDaoImpl $Dao
   */
  protected $Dao;

  //////////////////////////////////////////////////
  // CONSTRUCT
  //////////////////////////////////////////////////
  /**
   * Class constructor
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function __construct()
  {
    $this->Dao = new DataQuestionnaireDaoImpl();
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @param array $arrFilters
   * @return array
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_DATA));
    return $arrParams;
  }

  /**
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function getDataQuestionnairesWithFilters($arrFilters=array(), $orderby=self::FIELD_ID, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }
  /**
   * @param string $ins
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function deleteIn($ins)
  { $this->Dao->deleteIn($ins); }
}

