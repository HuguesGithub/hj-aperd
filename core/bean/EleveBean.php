<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EleveBean
 * @author Hugues
 * @version 1.21.07.05
 * @since 1.21.06.01
 */
class EleveBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-eleve.php';
  /**
   * Class Constructor
   * @param Eleve $Eleve
   * @version 1.00.00
   * @since 1.00.00
   */
  public function __construct($Eleve='')
  {
    $this->EleveServices = new EleveServices();
    $this->Eleve = ($Eleve=='' ? new Eleve() : $Eleve);
  }
  /**
   * @return string
   * @version 1.21.07.05
   * @since 1.21.06.29
   */
  public function getRowForPublicPage()
  {
	$content  = '<tr>';
	$content .= $this->getTdStandard($this->Eleve->getNomEleve());
	$content .= $this->getTdStandard($this->Eleve->getPrenomEleve());
	$content .= $this->getTdStandard(($this->Eleve->isDelegue() ? '<span class="badge badge-success">Oui</span>' : ''));
	return $content.'</tr>';
  }
  /**
   */
  public function getRowForAdminPage($checked=false, $argFilters=array())
  {
    $queryArgs = array_merge(
      array(
        self::CST_ONGLET=>self::PAGE_ELEVE,
        self::CST_POSTACTION=>self::CST_EDIT,
        self::FIELD_ID=>$this->Eleve->getId(),
      ),
      $argFilters,
    );
    $urlEdition = $this->getQueryArg($queryArgs);
    // Création du lien de suppression
    $queryArgs[self::CST_POSTACTION] = self::CST_DELETE;
    $urlSuppression = $this->getQueryArg($queryArgs);

    $attributes = array(
      // Identifiant de l'Elève
      $this->Eleve->getId(),
      // Url d'édition de l'Elève
      $urlEdition,
      // Nom de l'Elève - 3
      $this->Eleve->getNomEleve(),
      // Prénom de l'Elève - 4
      $this->Eleve->getPrenomEleve(),
      // Division de l'Elève - 5
      $this->Eleve->getDivision()->getLabelDivision(),
      // Est délégué ? - 6
      ($this->Eleve->isDelegue() ? 'Oui' : 'Non'),
      // Url de suppression de l'Elève - 7
      $urlSuppression,
      // Checkée ou non - 8
      $checked ? self::CST_BLANK.self::CST_CHECKED : '',
    );
    return $this->getRender($this->urlTemplateRowAdmin, $attributes);
  }


  /**
   * @param string $tagId
   * @param mixed $selectedId
   * @return string;
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getSelect($tagId=self::CST_ID, $label=self::CST_DEFAULT_SELECT, $selectedId=-1)
  {
    $Eleves = $this->EleveServices->getElevesWithFilters();
    return $this->getLocalSelect($Eleves, $tagId, $label, $selectedId);
  }
  /**
   * @param mixed $selectedId
   * @return string;
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getOption($selectedId=-1)
  { return $this->getLocalOption($this->Eleve->getNomComplet(), $this->Eleve->getId(), $selectedId); }
}