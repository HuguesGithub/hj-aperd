<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe QuestionnaireBean
 * @author Hugues
 * @version 1.21.06.09
 * @since 1.21.06.09
 */
class QuestionnaireBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-questionnaire.php';
  /**
   * Class Constructor
   * @param Questionnaire $Questionnaire
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function __construct($Questionnaire='')
  {
    $this->QuestionnaireServices = new QuestionnaireServices();
    $this->Questionnaire = ($Questionnaire=='' ? new Questionnaire() : $Questionnaire);
  }
  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
  /**
   * @parame boolean $checked
   * @return string
   * @version 1.21.06.09
   * @since 1.21.06.09
   */
  public function getRowForAdminPage($checked=false)
  {
    // Création du lien d'édition
    $queryArgs = array(
      self::CST_ONGLET       => self::PAGE_QUESTIONNAIRE,
      self::CST_POSTACTION   => self::CST_EDIT,
      self::FIELD_CONFIG_KEY => $this->Questionnaire->getConfigKey(),
    );
    $urlEdition = $this->getQueryArg($queryArgs);
    // Création du lien de suppression
    $queryArgs[self::CST_POSTACTION] = self::CST_DELETE;
    $urlSuppression = $this->getQueryArg($queryArgs);
    // Assignation des données relatives au template
    $attributes = array(
      // Identifiant de la Config
      $this->Questionnaire->getConfigKey(),
      // Url d'édition du Questionnaire
      $urlEdition,
      // Valeur de la clé
      $this->Questionnaire->getConfigValue(),
      // Url de suppression du Questionnaire
      $urlSuppression,
      // Checkée ou non - 5
      $checked ? self::CST_BLANK.self::CST_CHECKED : '',
    );
    // Restitution du template.
    return $this->getRender($this->urlTemplateRowAdmin, $attributes);
  }















  /**
   * @param string $tagId
   * @param mixed $selectedId
   * @param boolean $isMandatory
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getSelect($tagId=self::CST_ID, $label=self::CST_DEFAULT_SELECT, $selectedId=-1, $isMandatory=false, $isReadOnly=false)
  {
    $Divisions = $this->DivisionServices->getDivisionsWithFilters();
    return $this->getLocalSelect($Divisions, $tagId, $label, $selectedId, $isMandatory, false, $isReadOnly);
  }
  /**
   * @param mixed $selectedId
   * @return string;
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getOption($selectedId=-1)
  { return $this->getLocalOption($this->Division->getLabelDivision(), $this->Division->getId(), $selectedId); }
}
