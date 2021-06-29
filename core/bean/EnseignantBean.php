<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EnseignantBean
 * @author Hugues
 * @version 1.21.06.29
 * @since 1.21.06.01
 */
class EnseignantBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-enseignant.php';
  /**
   * Class Constructor
   * @param Enseignant $Enseignant
   */
  public function __construct($Enseignant='')
  {
    $this->EnseignantServices = new EnseignantServices();
    $this->ProfPrincipalServices = new ProfPrincipalServices();
    $this->Enseignant = ($Enseignant=='' ? new Enseignant() : $Enseignant);
    $this->EnseignantMatiereServices = new EnseignantMatiereServices();
  }
  /**
   * @param boolean $checked
   * @return string
   * @version 1.21.06.29
   * @since 1.21.06.01
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

    ////////////////////////////////////////////////////////
    // Gestion des Matières
    $EnseignantMatieres = $this->EnseignantMatiereServices->getEnseignantMatieresWithFilters(array(self::FIELD_ENSEIGNANT_ID=>$this->Enseignant->getId()));
    $arrEnseignantMatieres = array();
    while (!empty($EnseignantMatieres)) {
      $EnseignantMatiere = array_shift($EnseignantMatieres);
      array_push($arrEnseignantMatieres, $EnseignantMatiere->getMatiere()->getLabelMatiere());
    }
    ////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////
    // On enrichi le template et on le retourne
    $attributes = array(
      // Identifiant de l'Enseignant
      $this->Enseignant->getId(),
      // Url d'édition de l'Enseignant
      $urlEdition,
      // Nom de l'Enseignant - 3
      $this->Enseignant->getFullName(),
      // Matière de l'Enseignant - 4
      implode(', ', $arrEnseignantMatieres),
      // Url de Suppression - 5
      $urlSuppression,
      // Checked or not checked - 6
      $checked ? ' checked' : '',
      // Division de l'enseignant s'il est Prof Principal - 7
      $ProfPrincipal->getDivision()->getLabelDivision(),
    );
    return $this->getRender($this->urlTemplateRowAdmin, $attributes);
  }
  /**
   * @param array $params
   * @return string
   * @version 1.21.06.22
   * @since 1.21.06.01
   */
  public function getSelect($params = array())
  {
    $params['Objs'] = $this->EnseignantServices->getEnseignantsWithFilters();
    return parent::getSelect($params);
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
