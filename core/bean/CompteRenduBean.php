<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CompteRenduBean
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class CompteRenduBean extends LocalBean
{
  protected $urlTemplateRowAdmin = 'web/pages/admin/fragments/row-compte-rendu.php';
  /**
   * Class Constructor
   * @param CompteRendu $CompteRendu
   * @version 1.00.00
   * @since 1.00.00
   */
  public function __construct($CompteRendu='')
  {
    parent::__construct();
    $this->CompteRenduServices = new CompteRenduServices();
    $this->CompteRendu = ($CompteRendu=='' ? new CompteRendu() : $CompteRendu);
  }

  /**
   */
  public function getRowForAdminPage()
  {
    $queryArgs = array(
      self::CST_ONGLET=>self::PAGE_COMPTE_RENDU,
      self::CST_POSTACTION=>self::CST_EDIT,
      self::FIELD_ID=>$this->CompteRendu->getId(),
      self::ATTR_TYPE=>'',
    );

    $status = $this->CompteRendu->getStatus();
    switch ($status) {
      case 'archived' :
      // Il faudrait un lien vers le PDF;
        $linkToCr = $status;
      break;
      default :
        $linkToCr = '<a href="/compte-rendu/?crKey='.$this->CompteRendu->getCrKey().'">'.$status.'</a>';
      break;
    }


    $attributes = array(
      // Identifiant du Compte-Rendu - 1
      $this->CompteRendu->getId(),
      // Url d'édition du Compte-Rendu - 2
      $this->getQueryArg($queryArgs),
      // Année scolaire - 3
      $this->CompteRendu->getAnneeScolaire()->getAnneeScolaire(),
      // Trimestre - 3
      'T'.$this->CompteRendu->getTrimestre(),
      // Libellé de la Classe - 4
      $this->CompteRendu->getClasseScolaire()->getLabelClasse(),
      // Statut du compte rendu - 4
      $linkToCr,
    /*
      // Matière - 5
      $this->CompoClasse->getMatiere()->getLabelMatiere(),
      // Enseignant - 6
      $this->CompoClasse->getEnseignant()->getNomEnseignant(),
      */
      '','','','','','','','','',
    );
    return $this->getRender($this->urlTemplateRowAdmin, $attributes);
  }

}
