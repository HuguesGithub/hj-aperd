<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageMatieresBean
 * @author Hugues
 * @version 1.21.06.29
 * @since 1.21.06.01
 */
class AdminPageMatieresBean extends AdminPageBean
{
  protected $urlTemplatePageMatiereAdmin = 'web/pages/admin/board-matieres.php';
  protected $urlTemplateForm = 'web/pages/admin/fragments/form-matiere.php';

  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
    $this->MatiereServices = new MatiereServices();
    $this->Services        = new MatiereServices();
    // Initialisation de la Matière sélectionnée s'il y en a une.
    $this->LocalObject = ($urlParams!=null && isset($urlParams[self::FIELD_ID]) ? $this->MatiereServices->selectLocal($urlParams[self::FIELD_ID]) : new Matiere());
   // On stocke les paramètres
    $this->urlParams = $urlParams;
    // On prépare le stockage pour les ids multiples si existants.
    $this->arrIds = array();
    $this->subMenuValue = self::PAGE_MATIERE;
  }
  /**
   * Retourne la Matière
   * @return Matiere
   * @version 1.21.06.29
   * @since 1.21.06.06
   */
  public function getObject()
  { return $this->LocalObject; }
  /**
   * Retourne le Service
   * @return MatiereService
   * @version 1.21.06.29
   * @since 1.21.06.06
   */
  public function getServices()
  { return $this->Services; }

  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.06
   * @since 1.21.06.01
   */
  public static function getStaticContentPage($urlParams)
  {
    ///////////////////////////////////////////:
    // Initialisation des valeurs par défaut
    $Bean = new AdminPageMatieresBean($urlParams);
    return $Bean->getContentPage();
  }
  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.29
   * @since 1.21.06.06
   */
  public function getContentPage()
  {
    ///////////////////////////////////////////
    // Initialisation des valeurs par défaut
    $msg = '';
    $initPanel = self::CST_CREATE;

    ///////////////////////////////////////////
    // Analyse de l'action éventuelle.
    if (isset($this->urlParams[self::CST_POSTACTION])) {
      $this->parseUrlParams($initPanel, $notif, $msg);
    }

    ///////////////////////////////////////////
    // Si $msg est renseigné, on a une notification à afficher.
    if ($msg!='') {
      $this->createNotification($notif, $msg);
    }
    ///////////////////////////////////////////:
    // On initialise les panneaux latéraux droit
    $this->msgConfirmDelete = sprintf(self::MSG_CONFIRM_SUPPR_MATIERE, $this->Matiere->getLabelMatiere());
    $this->attributesFormNew = array('');
    $this->tagConfirmDeleteMultiple = self::MSG_CONFIRM_SUPPR_MATIERES;
    $this->attributesFormEdit  = array(
      // Libellé de la Matière - 1
      $this->Matiere->getLabelMatiere(),
    ) ;
    $this->initPanels($initPanel);

    ///////////////////////////////////////////:
    // On retourne le listing et les panneaux latéraux droit
    return $this->getListingPage();
  }

  /**
   * @version 1.21.06.29
   * @since 1.21.06.29
   */
  public function setLocalObject()
  {
    $this->LocalObject->setLabelMatiere($this->urlParams[self::FIELD_LABELMATIERE]);
  }
  /**
   * @version 1.21.06.29
   * @since 1.21.06.29
   */
  public function initLocalObject()
  { $this->LocalObject = new Matiere(); }

  /**
   * Gestion de l'affichage de la page.
   * @return string
   * @version 1.21.06.06
   * @since 1.21.06.01
   */
  public function getListingPage()
  {
    /////////////////////////////////////////////////////////////////////////////
    // On récupère toutes les matières puis on concatène les rows.
    $strRows = '';
    $Matieres = $this->MatiereServices->getMatieresWithFilters();
    foreach ($Matieres as $Matiere) {
      $Bean = $Matiere->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($Matiere->getId(), $this->arrIds));
    }

    /////////////////////////////////////////////////////////////////////////////
    // On restitue le template enrichi.
    $attributes = array(
      // Liste des matières affichées - 1
      $strRows,
      // Notification suite à soumission formulaire - 2
      $this->notifications,
      // Card C(R)UD - 3
      $this->getCardCRUD($this->crudType, $this->attributesCardCRUD),
      // Card Importation - 4
      $this->getCardImport(self::PAGE_MATIERE),
    );
    return $this->getRender($this->urlTemplatePageMatiereAdmin, $attributes);
  }

}
