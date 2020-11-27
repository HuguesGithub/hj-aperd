<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ProfPrincipalBean
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.00
 */
class ProfPrincipalBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-profprincipal.php';
  /**
   * Class Constructor
   * @param ProfPrincipal $ProfPrincipal
   * @version 1.00.00
   * @since 1.00.00
   */
  public function __construct($ProfPrincipal='')
  {
    parent::__construct();
    $this->ProfPrincipalServices = new ProfPrincipalServices();
    $this->ProfPrincipal = ($ProfPrincipal=='' ? new ProfPrincipal() : $ProfPrincipal);
  }
  /**
   */
  public function getRowForAdminPage($args=array())
  {
    $queryArgs = array(
      self::CST_ONGLET=>self::PAGE_ENSEIGNANT,
      self::CST_POSTACTION=>self::CST_EDIT,
      self::FIELD_ID=>$this->ProfPrincipal->getId(),
      self::ATTR_TYPE=>'ProfsPrincipaux',
    );
    $queryArgs = array_merge($queryArgs, $args);

    $attributes = array(
      // Identifiant du ProfPrincipal
      $this->ProfPrincipal->getId(),
      // Url d'édition du ProfPrincipal
      $this->getQueryArg($queryArgs),
      // Année Scolaire - 3
      $this->ProfPrincipal->getAnneeScolaire()->getAnneeScolaire(),
      // Classe de l'Enseignant - 4
      $this->ProfPrincipal->getClasseScolaire()->getLabelClasse(),
      // Nom de l'Enseignant - 5
      $this->ProfPrincipal->getEnseignant()->getNomEnseignant(),
    );
    return $this->getRender($this->urlTemplateRowAdmin, $attributes);
  }

}
