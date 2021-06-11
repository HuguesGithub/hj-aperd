<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Questionnaire
 * @author Hugues
 * @version 1.21.06.09
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
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function getCsvEntete($sep=self::SEP)
  { return implode($sep, array(self::FIELD_CONFIG_KEY, self::FIELD_CONFIG_VALUE)); }
  /**
   * @param string $sep
   * @return string
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function toCsv($sep=self::SEP)
  { return implode($sep, array($this->configKey, $this->configValue)); }
  /**
   * @param string $rowContent
   * @param string $sep
   * @param string &$notif
   * @param string &$msg
   * @return boolean
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function controleImportRow($rowContent, $sep=self::SEP, &$notif, &$msg)
  {
    // TODO
    /*
    list($id, $labelDivision) = explode($sep, $rowContent);
    $this->setId($id);
    $labelDivision = trim(str_replace(self::EOL, '', $labelDivision));
    $this->setLabelDivision($labelDivision);

    if (!$this->controleDonnees($notif, $msg)) {
      $notif = self::NOTIF_WARNING;
      $msg  .= self::MSG_SUCCESS_PARTIEL_IMPORT;
      return true;
    }
    // Si les contrôles sont okay, on peut insérer ou mettre à jour
    if ($id=='') {
      // Si id n'est pas renseigné. C'est une création. Il faut vérifier que le label n'existe pas déjà.
      $this->Services->insertLocal($this);
    } else {
      $DivisionInBase = $this->Services->selectLocal($id);
      if ($DivisionInBase->getId()=='') {
        // Sinon, si id n'existe pas, c'est une création. Cf au-dessus
        $this->Services->insertLocal($this);
      } else {
        // Si id existe, c'est une édition, même contrôle que ci-dessus.
        $this->setId($id);
        $this->Services->updateLocal($this);
      }
    }
    * */
    return false;
  }
  /**
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function controleDonnees(&$notif, &$msg)
  {
    // La clé doit être alphabétique sans accent
    $pattern = "/^[a-zA-Z]+$/";
    if (!preg_match($pattern, $this->configKey)) {
      $notif = self::NOTIF_DANGER;
      $msg   = sprintf(self::MSG_ERREUR_CONTROL_FORMAT, $pattern);
      return false;
    }
    // La clé doit être unique
    $Questionnaires = $this->QuestionnaireServices->getQuestionnairesWithFilters(array(self::FIELD_CONFIG_KEY=>$this->configKey));
    if (!empty($Questionnaires)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_UNICITE;
      return false;
    }
    // La valeur de la config doit être renseigné
    if (empty($this->configValue)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_EXISTENCE;
      return false;
    }
    // La valeur doit être unique
    $Questionnaires = $this->QuestionnaireServices->getQuestionnairesWithFilters(array(self::FIELD_CONFIG_VALUE=>$this->configValue));
    if (!empty($Questionnaires)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_UNICITE;
      return false;
    }
    return true;
  }
  /**
   * @param string &$notif
   * @param string &$msg
   * @return boolean
   * @version 1.21.06.08
   * @since 1.21.06.01
   */
  public function update(&$notif, &$msg)
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
    $this->Services->deleteIn("'".$this->configKey."'");
    $notif = self::NOTIF_SUCCESS;
    $msg   = self::MSG_SUCCESS_DELETE;
  }

}
