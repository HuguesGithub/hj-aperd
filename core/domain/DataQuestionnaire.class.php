<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe DataQuestionnaire
 * @author Hugues
 * @version 1.21.07.21
 * @since 1.21.07.21
 */
class DataQuestionnaire extends LocalDomain
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   *
   * @var string $data
   */
  protected $data;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////
  /**
   * @return int
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function getId()
  { return $this->id; }
  /**
   * @return string
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function getData()
  { return $this->data; }
  /**
   * @param int $id
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function setId($id)
  { $this->id = $id; }
  /**
   * @param string $data
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function setData($data)
  { $this->data = $data; }

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->Services              = new DataQuestionnaireServices();
  }
  /**
   * @return array
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function getClassVars()
  { return get_class_vars('DataQuestionnaire'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return DataQuestionnaire
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new DataQuestionnaire(), self::getClassVars(), $row); }
  /**
   * @return DataQuestionnaireBean
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function getBean()
  { return new DataQuestionnaireBean($this); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
  /**
   * @param string $rowContent
   * @param string $sep
   * @param string &$notif
   * @param string &$msg
   * @return boolean
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function controleImportRow($rowContent, $sep, &$notif, &$msg)
  {
    // TODO

    return false;
  }
  /**
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function controleDonnees(&$notif, &$msg)
  {
    // TODO

    return false;
  }

  /**
   * @return string
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function getFullName()
  { return 'Questionnaire n°'.$this->getId(); }

  public function getLabelDivision()
  {
    $arrData = unserialize($this->data);
    return $arrData['classe'];
  }
  public function getFullNameEleve()
  {
    $arrData = unserialize($this->data);
    return $arrData['nomEnfant'].self::CST_BLANK.$arrData['prenomEnfant'];
  }
  public function getFullNameParent()
  {
    $arrData = unserialize($this->data);
    return $arrData['nomPrenomParent'];
  }
  public function getMailParent()
  {
    $arrData = unserialize($this->data);
    return $arrData['mailParent'];
  }
}
