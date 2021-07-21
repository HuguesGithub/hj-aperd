<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe DataQuestionnaireBean
 * @author Hugues
 * @version 1.21.07.21
 * @since 1.21.07.21
 */
class DataQuestionnaireBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-data-questionnaire.php';
  /**
   * Class Constructor
   * @param DataQuestionnaire $DataQuestionnaire
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function __construct($DataQuestionnaire='')
  {
    $this->Services = new DataQuestionnaireServices();
    $this->DataQuestionnaire = ($DataQuestionnaire=='' ? new DataQuestionnaire() : $DataQuestionnaire);
  }
  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
  /**
   * @parame boolean $checked
   * @return string
   * @version 1.21.07.21
   * @since 1.21.07.21
   */
  public function getRowForAdminPage($checked=false)
  {
    $strTds  = '';
    $strTds .= $this->getBalise(self::TAG_TD, $this->DataQuestionnaire->getLabelDivision());
    $strTds .= $this->getBalise(self::TAG_TD, $this->DataQuestionnaire->getFullNameEleve());
    $strTds .= $this->getBalise(self::TAG_TD, $this->DataQuestionnaire->getFullNameParent());

    $lienMailTo = 'mailto:'.$this->DataQuestionnaire->getMailParent();

    // Création du lien de visionnage
    $queryArgs = array(
      self::CST_ONGLET       => self::PAGE_DATA_QUESTIONS,
      self::CST_POSTACTION   => self::CST_VIEW,
      self::FIELD_ID         => $this->DataQuestionnaire->getId(),
    );
    $urlView = $this->getQueryArg($queryArgs);

    // Création du lien de suppression
    $queryArgs[self::CST_POSTACTION] = self::CST_DELETE;
    $urlSuppression = $this->getQueryArg($queryArgs);

    // Assignation des données relatives au template
    $attributes = array(
      // Identifiant de la Config - 1
      $this->DataQuestionnaire->getId(),
      // Liste des <td> - 2
      $strTds,
      // Url de suppression du DataQuestionnaire - 3
      $urlSuppression,
      // Checkée ou non - 4
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
