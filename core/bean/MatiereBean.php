<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MatiereBean
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class MatiereBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-matiere.php';
  /**
   * Class Constructor
   * @param Matiere $Matiere
   * @version 1.00.00
   * @since 1.00.00
   */
  public function __construct($Matiere='')
  {
    $this->MatiereServices = new MatiereServices();
    $this->Matiere = ($Matiere=='' ? new Matiere() : $Matiere);
  }
  /**
   */
  public function getRowForAdminPage($checked=false)
  {
    $queryArgs = array(
      self::CST_ONGLET     => self::PAGE_MATIERE,
      self::CST_POSTACTION => self::CST_EDIT,
      self::FIELD_ID       => $this->Matiere->getId()
    );
    $urlEdition = $this->getQueryArg($queryArgs);

    $queryArgs = array(
      self::CST_ONGLET     => self::PAGE_MATIERE,
      self::CST_POSTACTION => self::CST_DELETE,
      self::FIELD_ID       => $this->Matiere->getId()
    );
    $urlSuppression = $this->getQueryArg($queryArgs);

    $attributes = array(
      // Identifiant de la Matière
      $this->Matiere->getId(),
      // Url d'édition de la Matière
      $urlEdition,
      // Libellé de la Matière
      $this->Matiere->getLabelMatiere(),
      // Url de suppression de la Matière
      $urlSuppression,
      // Checkée ou non - 5
      $checked ? ' checked' : '',
    );
    return $this->getRender($this->urlTemplateRowAdmin, $attributes);
  }
  /**
   * @param array $params
   * @return string
   * @version 1.21.06.21
   * @since 1.21.06.20
   */
  public function getSelect($params = array())
  {
    $params['Objs'] = $this->MatiereServices->getMatieresWithFilters();
    return parent::getSelect($params);
  }

  /**
   * @param mixed $selectedId
   * @return string;
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getOption($selectedId=-1)
  { return $this->getLocalOption($this->Matiere->getLabelMatiere(), $this->Matiere->getId(), $selectedId); }
}
