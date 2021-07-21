<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Questionnaire
 * @author Hugues
 * @version 1.21.07.21
 * @since 1.21.06.09
 */
class Questionnaire extends LocalDomain
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * Id technique de la donnée
   * @var string configKey
   */
  protected $configKey;
  /**
   *
   * @var string $configValue
   */
  protected $configValue;
  /**
   *
   * @var int $displayOrder
   */
  protected $displayOrder;

  //////////////////////////////////////////////////
  // GETTERS & SETTERS
  //////////////////////////////////////////////////
  /**
   * @return string
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function getConfigKey()
  { return $this->configKey; }
  /**
   * @return string
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function getConfigValue()
  { return $this->configValue; }
  /**
   * @return int
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function getDisplayOrder()
  { return $this->displayOrder; }
  /**
   * @param string $configKey
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function setConfigKey($configKey)
  { $this->configKey = $configKey; }
  /**
   * @param string $configValue
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function setConfigValue($configValue)
  { $this->configValue = $configValue; }
  /**
   * @param int $displayOrder
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function setDisplayOrder($displayOrder)
  { $this->displayOrder = $displayOrder; }

  //////////////////////////////////////////////////
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////
  /**
   * @param array $attributes
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->QuestionnaireServices = new QuestionnaireServices();
    $this->Services              = new QuestionnaireServices();
  }
  /**
   * @return array
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function getClassVars()
  { return get_class_vars('Questionnaire'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return Questionnaire
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new Questionnaire(), self::getClassVars(), $row); }
  /**
   * @return QuestionnaireBean
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function getBean()
  { return new QuestionnaireBean($this); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
  /**
   * @param string $sep
   * @return string
   * @version 1.21.07.21
   * @since 1.21.06.09
   */
  public function getCsvEntete($sep=self::SEP)
  { return implode($sep, array(self::FIELD_CONFIG_KEY, self::FIELD_CONFIG_VALUE, self::FIELD_DISPLAY_ORDER)); }
  /**
   * @param string $sep
   * @return string
   * @version 1.21.07.21
   * @since 1.21.06.09
   */
  public function toCsv($sep=self::SEP)
  { return implode($sep, array($this->configKey, $this->configValue, $this->displayOrder)); }
  /**
   * @param string $rowContent
   * @param string $sep
   * @param string &$notif
   * @param string &$msg
   * @return boolean
   * @version 1.21.07.21
   * @since 1.21.06.09
   */
  public function controleImportRow($rowContent, $sep, &$notif, &$msg)
  {
    list($configKey, $configValue, $displayOrder) = explode($sep, $rowContent);
    $this->setConfigKey($configKey);
    $this->setConfigValue($configValue);
    $this->setDisplayOrder($displayOrder);

    if (!$this->controleDonnees($notif, $msg)) {
      $notif = self::NOTIF_WARNING;
      $msg  .= self::MSG_SUCCESS_PARTIEL_IMPORT;
      return true;
    }

    $Questionnaires = $this->Services->getQuestionnairesWithFilters(array(self::FIELD_CONFIG_KEY=>$configKey));
    if (empty($Questionnaires)) {
      $this->Services->insertLocal($this);
    } else {
      $this->Services->updateLocal($this);
    }

    return false;
  }
  /**
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.07.21
   * @since 1.21.06.09
   */
  public function controleDonnees(&$notif, &$msg)
  {
    $returned = true;
    // La clé doit être alphabétique sans accent
    $pattern = "/^[a-zA-Z]+$/";
    if (!preg_match($pattern, $this->configKey)) {
      $notif = self::NOTIF_DANGER;
      $msg   = sprintf(self::MSG_ERREUR_CONTROL_FORMAT, $pattern);
      $returned = false;
    } else {
      // La clé doit être unique
      $Questionnaires = $this->QuestionnaireServices->getQuestionnairesWithFilters(array(self::FIELD_CONFIG_KEY=>$this->configKey));
      if (!empty($Questionnaires)) {
        $notif = self::NOTIF_DANGER;
        $msg   = self::MSG_ERREUR_CONTROL_UNICITE;
        $returned = false;
      } elseif (empty($this->configValue)) {
      // La valeur de la config doit être renseignée
        $notif = self::NOTIF_DANGER;
        $msg   = self::MSG_ERREUR_CONTROL_EXISTENCE;
        $returned = false;
      } else {
        // La valeur doit être unique
        $Questionnaires = $this->QuestionnaireServices->getQuestionnairesWithFilters(array(self::FIELD_CONFIG_VALUE=>$this->configValue));
        if (!empty($Questionnaires)) {
          $notif = self::NOTIF_DANGER;
          $msg   = self::MSG_ERREUR_CONTROL_UNICITE;
          $returned = false;
        }
      }
    }
    return $returned;
  }
  /**
   * @param string &$notif
   * @param string &$msg
   * @param array $urlParams
   * @return boolean
   * @version 1.21.07.21
   * @since 1.21.06.01
   */
  public function update(&$notif, &$msg, $urlParams=array())
  {
    if ($this->controleDonnees($notif, $msg)) {
      if ($this->configKey=='' || $this->configValue=='') {
        $notif = self::NOTIF_DANGER;
        $msg   = self::MSG_ERREUR_CONTROL_EXISTENCE;
      } else {
        $this->Services->updateLocal($this);
        $notif = self::NOTIF_SUCCESS;
        $msg   = self::MSG_SUCCESS_UPDATE;
        return true;
      }
    }
    return false;
  }
  /**
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function delete(&$notif, &$msg)
  {
    $this->Services->deleteIn($this->configKey);
    $notif = self::NOTIF_SUCCESS;
    $msg   = self::MSG_SUCCESS_DELETE;
  }

  /**
   * @return string
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function getFullName()
  { return $this->getConfigKey(). ' - '.$this->getConfigValue(); }

}
