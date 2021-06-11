<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdministrationBean
 * @author Hugues
 * @version 1.21.06.10
 * @since 1.21.06.10
 */
class AdministrationBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-administration.php';
  /**
   * Class Constructor
   * @param Administration $Administration
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function __construct($Administration='')
  {
    $this->AdministrationServices = new AdministrationServices();
    $this->Administration = ($Administration=='' ? new Administration() : $Administration);
  }
  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
  /**
   * @parame boolean $checked
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function getRowForAdminPage($checked=false)
  {
    // Création du lien d'édition
    $queryArgs = array(
      self::CST_ONGLET     => self::PAGE_ADMINISTRATION,
      self::CST_POSTACTION => self::CST_EDIT,
      self::FIELD_ID       => $this->Administration->getId(),
    );
    $urlEdition = $this->getQueryArg($queryArgs);
    // Création du lien de suppression
    $queryArgs[self::CST_POSTACTION] = self::CST_DELETE;
    $urlSuppression = $this->getQueryArg($queryArgs);

    $attributes = array(
      // Identifiant de l'Administration
      $this->Administration->getId(),
      // Url d'édition de l'Administration
      $urlEdition,
      // Genre de l'Administration - 3
      $this->Administration->getGenre(),
      // Nom de l'Administration - 4
      $this->Administration->getNomTitulaire(),
      // Poste de l'Administration - 5
      $this->Administration->getLabelPoste(),
      // Url de suppression de l'Administration - 6
      $urlSuppression,
      // Checkée ou non - 7
      $checked ? self::CST_BLANK.self::CST_CHECKED : '',
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
    $Administrations = $this->AdministrationServices->getAdministrationsWithFilters();
    return $this->getLocalSelect($Administrations, $tagId, $label, $selectedId, $isMandatory, $isAjaxUpload);
  }
  /**
   * @param mixed $selectedId
   * @return string;
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getOption($selectedId=-1)
  { return $this->getLocalOption($this->Administration->getNomTitulaire(), $this->Administration->getId(), $selectedId); }
}
