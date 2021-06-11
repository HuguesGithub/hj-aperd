<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdulteBean
 * @author Hugues
 * @version 1.21.06.11
 * @since 1.21.06.11
 */
class AdulteBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-adulte.php';
  /**
   * Class Constructor
   * @param Adulte $Adulte
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function __construct($Adulte='')
  {
    $this->AdulteServices = new AdulteServices();
    $this->Adulte = ($Adulte=='' ? new Adulte() : $Adulte);

    $this->ParentDelegueServices = new ParentDelegueServices();
  }
  /**
   * @param boolean $checked
   * @return string
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function getRowForAdminPage($checked=false)
  {
    $queryArgs = array(
      self::CST_ONGLET     => self::PAGE_PARENT,
      self::CST_POSTACTION => self::CST_EDIT,
      self::FIELD_ID       => $this->Adulte->getId(),
    );
    $urlEdition = $this->getQueryArg($queryArgs);
    // Création du lien de suppression
    $queryArgs[self::CST_POSTACTION] = self::CST_DELETE;
    $urlSuppression = $this->getQueryArg($queryArgs);

    ////////////////////////////////////////////////////
    // Récupération des Divisions dans lesquelles l'Adulte est délégué.
    $arrDivisions = array();
    $ParentDelegues = $this->ParentDelegueServices->getParentDeleguesWithFilters(array(self::FIELD_PARENT_ID=>$this->Adulte->getId()));
    foreach ($ParentDelegues as $ParentDelegue) {
      $arrDivisions[] = $ParentDelegue->getDivision()->getLabelDivision();
    }
    $strDivisions = implode(', ', $arrDivisions);


    $attributes = array(
      // Identifiant de l'Adulte - 1
      $this->Adulte->getId(),
      // Url d'édition de l'Adulte - 2
      $urlEdition,
      // Nom de l'Adulte - 3
      $this->Adulte->getNomParent(),
      // Prénom de l'Adulte - 4
      $this->Adulte->getPrenomParent(),
      // Mail de l'Adulte - 5
      $this->Adulte->getMailParent(),
      // Est Adherent ? - 6
      ($this->Adulte->isAdherent() ? 'Oui' : 'Non'),
      // Est Délégué ? - 7
      $strDivisions,
      // Url de suppression de l'Adulte - 8
      $urlSuppression,
      // Checkée ou non - 9
      ($checked ? self::CST_BLANK.self::CST_CHECKED : ''),
    );
    return $this->getRender($this->urlTemplateRowAdmin, $attributes);
  }
  /**
   * @param string $tagId
   * @param string $label
   * @param mixed $selectedId
   * @param boolean $isMandatory
   * @param boolean $isReadOnly
   * @return string;
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function getSelect($tagId=self::CST_ID, $label=self::CST_DEFAULT_SELECT, $selectedId=-1, $isMandatory=false, $isReadOnly=false)
  {
    $Adultes = $this->AdulteServices->getAdultesWithFilters();
    return $this->getLocalSelect($Adultes, $tagId, $label, $selectedId, $isMandatory, false, $isReadOnly);
  }

  /**
   * @param mixed $selectedId
   * @return string
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function getOption($selectedId=-1)
  { return $this->getLocalOption($this->Adulte->getFullName(), $this->Adulte->getId(), $selectedId); }













}
