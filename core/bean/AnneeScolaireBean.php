<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AnneeScolaireBean
 * @author Hugues
 * @version 1.21.06.21
 * @since 1.21.06.10
 */
class AnneeScolaireBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-annee-scolaire.php';
  /**
   * Class Constructor
   * @param AnneeScolaire $AnneeScolaire
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function __construct($AnneeScolaire='')
  {
    $this->AnneeScolaireServices = new AnneeScolaireServices();
    $this->AnneeScolaire = ($AnneeScolaire=='' ? new AnneeScolaire() : $AnneeScolaire);
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
      self::CST_ONGLET      => self::PAGE_ANNEE_SCOLAIRE,
      self::CST_POSTACTION  => self::CST_EDIT,
      self::FIELD_ID        => $this->AnneeScolaire->getId(),
    );
    $urlEdition = $this->getQueryArg($queryArgs);
    // Création du lien de suppression
    $queryArgs[self::CST_POSTACTION] = self::CST_DELETE;
    $urlSuppression = $this->getQueryArg($queryArgs);

    $attributes = array(
      // Identifiant de l'Année Scolaire
      $this->AnneeScolaire->getId(),
      // Url d'édition de l'Année Scolaire
      $urlEdition,
      // Libellé de l'Année Scolaire - 3
      $this->AnneeScolaire->getAnneeScolaire(),
      // Url de suppression - 4
      $urlSuppression,
      // Checkée ou non - 5
      $checked ? self::CST_BLANK.self::CST_CHECKED : '',
    );
    return $this->getRender($this->urlTemplateRowAdmin, $attributes);
  }





  /**
   * @param array $params
   * @return string
   * @version 1.21.06.21
   * @since 1.21.06.21
   */
  public function getSelect($params = array())
  {
    $params['Objs'] = $this->AnneeScolaireServices->getAnneeScolairesWithFilters();
    return parent::getSelect($params);
  }

  /**
   * @param mixed $selectedId
   * @return string;
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getOption($selectedId=-1)
  { return $this->getLocalOption($this->AnneeScolaire->getAnneeScolaire(), $this->AnneeScolaire->getId(), $selectedId); }
}
