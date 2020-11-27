<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageMatieresBean
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.01
 */
class AdminPageMatieresBean extends AdminPageBean
{
  protected $urlTemplatePageMatiereAdmin = 'web/pages/admin/board-matieres.php';
  /**
   * Class Constructor
   */
  public function __construct($id=null)
  {
    parent::__construct();
    $this->title = 'Matières';
    $this->MatiereServices = new MatiereServices();
    if ($id!=null) {
      $this->Matiere = $this->MatiereServices->selectLocal($id);
    } else {
      $this->Matiere = null;
    }
  }
  /**
   * @param array $urlParams
   * @return $Bean
   */
  public static function getStaticContentPage($urlParams)
  {
    if (isset($urlParams[self::CST_POSTACTION])) {
      $id = $urlParams[self::FIELD_ID];
      $Bean = new AdminPageMatieresBean($id);
      if (isset($urlParams['Edition'])) {
        $Bean->update($urlParams);
      } elseif (isset($urlParams['Création'])) {
        $Bean->insert($urlParams);
      }
    } else {
      $Bean = new AdminPageMatieresBean();
    }
    return $Bean->getListingPage();
    //return $Bean->returnPostActionPage($urlParams);
  }
  public function update($urlParams)
  {
    $this->Matiere->setLabelMatiere($urlParams[self::FIELD_LABELMATIERE]);
    $this->MatiereServices->updateLocal($this->Matiere);
  }
  public function insert($urlParams)
  {
    $this->Matiere = new Matiere();
    $this->Matiere->setLabelMatiere($urlParams[self::FIELD_LABELMATIERE]);
    $this->MatiereServices->insertLocal($this->Matiere);
  }
  /**
   * @return string
   */
  public function getListingPage()
  {
    /////////////////////////////////////////////////////////////////////////////
    // On récupère toutes les matières puis on concatène les rows.
    $strRows = '';
    $Matieres = $this->MatiereServices->getMatieresWithFilters();
    foreach ($Matieres as $Matiere) {
      $Bean = $Matiere->getBean();
      $strRows .= $Bean->getRowForAdminPage();
    }

    /////////////////////////////////////////////////////////////////////////////
    // On restitue le template enrichi.
    $attibutes = array(
      // Liste des matières affichées - 1
      $strRows,
      // Type d'action : Création ou Edition - 2
      $this->Matiere==null ? 'Création' : 'Edition',
      // Libellé de la matière éditée - 3
      $this->Matiere==null ? '' : $this->Matiere->getLabelMatiere(),
      // Url d'annulation - 4
      $Bean->getQueryArg(array(self::CST_ONGLET=>self::PAGE_MATIERE)),
      // Id de la matière - 5
      $this->Matiere==null ? '' : $this->Matiere->getId(),
    );
    return $this->getRender($this->urlTemplatePageMatiereAdmin, $attibutes);
  }
}
