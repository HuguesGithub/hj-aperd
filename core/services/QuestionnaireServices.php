<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe QuestionnaireServices
 * @author Hugues
 * @version 1.21.06.09
 * @since 1.21.06.09
 */
class QuestionnaireServices extends LocalServices
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * L'objet Dao pour faire les requêtes
   * @var QuestionnaireDaoImpl $Dao
   */
  protected $Dao;

  //////////////////////////////////////////////////
  // CONSTRUCT
  //////////////////////////////////////////////////
  /**
   * Class constructor
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function __construct()
  {
    $this->Dao = new QuestionnaireDaoImpl();
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////
  /**
   * @param array $arrFilters
   * @return array
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_CONFIG_KEY));
    array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_CONFIG_VALUE));
    return $arrParams;
  }
  /**
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function getQuestionnairesWithFilters($arrFilters=array(), $orderby=self::FIELD_CONFIG_KEY, $order=self::ORDER_ASC)
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters(__FILE__, __LINE__, $arrParams);
  }
  /**
   * @param string $ins
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function deleteIn($ins)
  { $this->Dao->deleteIn($ins); }
}

