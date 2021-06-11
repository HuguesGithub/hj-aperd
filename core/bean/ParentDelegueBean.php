<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ParentDelegueBean
 * @author Hugues
 * @version 1.26.06.11
 * @since 1.26.06.11
 */
class ParentDelegueBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-parent-delegue.php';
  /**
   * Class Constructor
   * @param ParentDelegue $ParentDelegue
   * @version 1.26.06.11
   * @since 1.26.06.11
   */
  public function __construct($ParentDelegue='')
  {
    $this->ParentDelegueServices = new ParentDelegueServices();
    $this->ParentDelegue = ($ParentDelegue=='' ? new ParentDelegue() : $ParentDelegue);
  }
  /**
   */
  public function getRowForAdminPage($checked=false)
  {
    $queryArgs = array_merge(
      array(
        self::CST_ONGLET=>self::PAGE_PARENT_DELEGUE,
        self::CST_POSTACTION=>self::CST_EDIT,
        self::FIELD_ID=>$this->ParentDelegue->getId(),
      ),
    );
    $urlEdition = $this->getQueryArg($queryArgs);
    // Création du lien de suppression
    $queryArgs[self::CST_POSTACTION] = self::CST_DELETE;
    $urlSuppression = $this->getQueryArg($queryArgs);

    $attributes = array(
      // Identifiant de l'Objet
      $this->ParentDelegue->getId(),
      // Url d'édition de l'Objet
      $urlEdition,
      // Nom de l'Objet - 3
      $this->ParentDelegue->getAdulte()->getFullName(),
      // Division de l'Objet - 4
      $this->ParentDelegue->getDivision()->getLabelDivision(),
      // Url de suppression de l'Objet - 5
      $urlSuppression,
      // Checkée ou non - 6
      $checked ? self::CST_BLANK.self::CST_CHECKED : '',
    );
    return $this->getRender($this->urlTemplateRowAdmin, $attributes);
  }


}
