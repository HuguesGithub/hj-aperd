<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CompoClasseBean
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class CompoClasseBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-compo-classe.php';
  /**
   * Class Constructor
   * @param CompoClasse $CompoClasse
   * @version 1.00.00
   * @since 1.00.00
   */
  public function __construct($CompoClasse='')
  {
    parent::__construct();
    $this->CompoClasseServices = new CompoClasseServices();
    $this->CompoClasse = ($CompoClasse=='' ? new CompoClasse() : $CompoClasse);
  }
  /**
   */
  public function getRowForAdminPage()
  {
    $queryArgs = array(
      self::CST_ONGLET=>self::PAGE_CLASSE,
      self::CST_POSTACTION=>self::CST_EDIT,
      self::FIELD_ID=>$this->CompoClasse->getId(),
      self::ATTR_TYPE=>'ClasseScolaire',
    );

    $attributes = array(
      // Identifiant de la Classe - 1
      $this->CompoClasse->getId(),
      // Url d'édition de la Classe - 2
      $this->getQueryArg($queryArgs),
      // Année scolaire - 3
      $this->CompoClasse->getAnneeScolaire()->getAnneeScolaire(),
      // Libellé de la Classe - 4
      $this->CompoClasse->getClasseScolaire()->getLabelClasse(),
      // Matière - 5
      $this->CompoClasse->getMatiere()->getLabelMatiere(),
      // Enseignant - 6
      $this->CompoClasse->getEnseignant()->getNomEnseignant(),
    );
    return $this->getRender($this->urlTemplateRowAdmin, $attributes);
  }
}
