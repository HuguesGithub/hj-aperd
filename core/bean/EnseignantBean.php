<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EnseignantBean
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.00
 */
class EnseignantBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-enseignant.php';
  /**
   * Class Constructor
   * @param Enseignant $Enseignant
   * @version 1.00.00
   * @since 1.00.00
   */
  public function __construct($Enseignant='')
  {
    $this->EnseignantServices = new EnseignantServices();
    $this->ProfPrincipalServices = new ProfPrincipalServices();
    $this->Enseignant = ($Enseignant=='' ? new Enseignant() : $Enseignant);
  }
  /**
   */
  public function getRowForAdminPage($checked=false)
  {
    $queryArgs = array(
      self::CST_ONGLET     => self::PAGE_ENSEIGNANT,
      self::CST_POSTACTION => self::CST_EDIT,
      self::FIELD_ID       => $this->Enseignant->getId(),
    );
    $urlEdition = $this->getQueryArg($queryArgs);

    $queryArgs = array(
      self::CST_ONGLET     => self::PAGE_ENSEIGNANT,
      self::CST_POSTACTION => self::CST_DELETE,
      self::FIELD_ID       => $this->Enseignant->getId(),
    );
    $urlSuppression = $this->getQueryArg($queryArgs);

    $ProfPrincipals = $this->ProfPrincipalServices->getProfPrincipalsWithFilters(array(self::FIELD_ENSEIGNANT_ID=>$this->Enseignant->getId()));
    $ProfPrincipal  = (empty($ProfPrincipals) ? new ProfPrincipal() : array_shift($ProfPrincipals));

    $attributes = array(
      // Identifiant de l'Enseignant
      $this->Enseignant->getId(),
      // Url d'édition de l'Enseignant
      $urlEdition,
      // Nom de l'Enseignant - 3
      $this->Enseignant->getFullName(),
      // Matière de l'Enseignant - 4
      $this->Enseignant->getMatiere()->getLabelMatiere(),
      // Url de Suppression - 5
      $urlSuppression,
      // Checked or not checked - 6
      $checked ? ' checked' : '',
      // Division de l'enseignant s'il est Prof Principal - 7
      $ProfPrincipal->getDivision()->getLabelDivision(),
      // Année Scolaire de l'enseignant s'il est Prof Principal - 8
      $ProfPrincipal->getAnneeScolaire()->getAnneeScolaire(),
    );
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
  public function getSelect($tagId=self::CST_ID, $label=self::CST_DEFAULT_SELECT, $selectedId=-1, $isMandatory=false, $isAjaxUpload=false)
  {
    $Enseignants = $this->EnseignantServices->getEnseignantsWithFilters();
    return $this->getLocalSelect($Enseignants, $tagId, $label, $selectedId, $isMandatory, $isAjaxUpload);
  }
  /**
   * @param mixed $selectedId
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getOption($selectedId=-1)
  { return $this->getLocalOption($this->Enseignant->getNomEnseignant(), $this->Enseignant->getId(), $selectedId); }
}
