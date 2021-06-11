<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageParentsBean
 * @author Hugues
 * @version 1.21.06.10
 * @since 1.21.06.10
 */
class AdminPageParentsBean extends AdminPageBean
{
  protected $urlTemplatePageAdulteAdmin = 'web/pages/admin/board-parents.php';
  protected $urlTemplateForm = 'web/pages/admin/fragments/form-parent.php';

  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
    $this->AdulteServices = new AdulteServices();
    $this->Services       = new AdulteServices();
    // Initialisation du Parent sélectionné s'il y en a une.
    if ($urlParams!=null && isset($urlParams[self::FIELD_ID])) {
      $this->Adulte = $this->AdulteServices->selectLocal($urlParams[self::FIELD_ID]);
    } else {
      $this->Adulte = new Adulte();
    }
    // On stocke les paramètres
    $this->urlParams = $urlParams;
    // On prépare le stockage pour les ids multiples si existants.
    $this->arrIds = array();
  }
  /**
   * Retourne le Parent
   * @return Adulte
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function getObject()
  { return $this->Adulte; }
  /**
   * Retourne le Service
   * @return AdulteService
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function getServices()
  { return $this->AdulteServices; }

  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public static function getStaticContentPage($urlParams)
  {
    ///////////////////////////////////////////:
    // Initialisation des valeurs par défaut
    $Bean = new AdminPageParentsBean($urlParams);
    return $Bean->getContentPage();
  }
  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
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
      switch($this->urlParams[self::CST_POSTACTION]) {
        case self::CST_CREATION :
          // Exécution de la création
          $this->Adulte->setNomParent($this->urlParams[self::FIELD_NOMPARENT]);
          $this->Adulte->setPrenomParent($this->urlParams[self::FIELD_PRENOMPARENT]);
          $this->Adulte->setMailParent($this->urlParams[self::FIELD_MAILPARENT]);
          $this->Adulte->setAdherent(isset($this->urlParams[self::FIELD_ADHERENT]));
          $this->Adulte->insert($notif, $msg);
          $this->Adulte = new Adulte();
        break;
        case self::CST_EDITION :
          // Exécution de la mise à jour
          $this->Adulte->setNomParent($this->urlParams[self::FIELD_NOMPARENT]);
          $this->Adulte->setPrenomParent($this->urlParams[self::FIELD_PRENOMPARENT]);
          $this->Adulte->setMailParent($this->urlParams[self::FIELD_MAILPARENT]);
          $this->Adulte->setAdherent(isset($this->urlParams[self::FIELD_ADHERENT]));
          $this->Adulte->update($notif, $msg);
          $initPanel = self::CST_EDIT;
        break;
        case self::CST_SUPPRESSION :
          // Exécution de la suppression unitaire ou groupée
          $this->delete($notif, $msg);
          $this->Adulte = new Adulte();
        break;
        case self::CST_IMPORT :
          // Exécution de l'import
          $this->import($notif, $msg);
          $this->Adulte = new Adulte();
        break;
        case self::CST_BULK :
          // Gestion des Actions groupées
          switch ($this->urlParams[self::CST_ACTION]) {
            case self::CST_TRASH :
              // Confirmation de la Suppression de masse
              if (empty($this->urlParams[self::CST_POST])) {
                $msg = self::MSG_BULK_DELETE_IMPOSSIBLE;
                $notif = self::NOTIF_WARNING;
              } else {
                $initPanel = self::CST_BULK_TRASH;
              }
            break;
            case self::CST_EXPORT :
              // Exécution de l'exportation
              if (empty($this->urlParams[self::CST_POST])) {
                $msg = self::MSG_BULK_EXPORT_IMPOSSIBLE;
                $notif = self::NOTIF_WARNING;
              } else {
                $msg = ExportActions::dealWithStaticExport(self::PAGE_PARENT, $this->urlParams[self::CST_POST]);
                $notif = self::NOTIF_SUCCESS;
                $initPanel = self::CST_BULK_EXPORT;
              }
            break;
            default :
              // Erreur sur l'action groupée, non reconnue
              $notif = self::NOTIF_WARNING;
              $msg   = sprintf(self::MSG_BULK_ACTION_INDEFINIE, array($this->urlParams[self::CST_ACTION]));
            break;
          }
        break;
        default :
          // Affichage des écrans simples : création ou édition
          $initPanel = $this->urlParams[self::CST_POSTACTION];
        break;
      }
    }

    ///////////////////////////////////////////
    // Si $msg est renseigné, on a une notification à afficher.
    if ($msg!='') {
      $this->createNotification($notif, $msg);
    }
    ///////////////////////////////////////////:
    // On initialise les panneaux latéraux droit
    $this->initPanels($initPanel);
    ///////////////////////////////////////////:
    // On retourne le listing et les panneaux latéraux droit
    return $this->getListingPage();
  }

  /**
   * Intialise les panneaux latéraux à afficher
   * @param string $action
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function initPanels($action)
  {
    switch ($action) {
      case self::CST_DELETE :
        $this->crudType = self::CST_DELETE;
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Message de confirmation à afficher - 1
          sprintf(self::MSG_CONFIRM_SUPPR_PARENT, $this->Adulte->getFullName()),
          // Id de l'objet ou des objets à supprimer - 2
          $this->Adulte->getId(),
          // Url d'annulation de l'opération - 3
          $this->getQueryArg(array(self::CST_ONGLET=>self::PAGE_PARENT)),
        );
      break;
      case self::CST_CREATION :
      case self::CST_EDITION  :
      case self::CST_EDIT     :
        $this->crudType = self::CST_EDIT;
        $attributesForm  = array(
          // Nom du Parent - 1
          $this->Adulte->getNomParent(),
          // Préom du Parent - 2
          $this->Adulte->getPrenomParent(),
          // Mail du Parent - 3
          $this->Adulte->getMailParent(),
          // Est adhérent ? - 4
          ($this->Adulte->isAdherent() ? self::CST_BLANK.self::CST_CHECKED : ''),
        ) ;
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Contenu du Formulaire - 1
          $this->getRender($this->urlTemplateForm, $attributesForm),
          // Id de l'objet ou des objets à supprimer - 2
          $this->Adulte->getId(),
          // Url d'annulation de l'opération - 3
          $this->getQueryArg(array(self::CST_ONGLET=>self::PAGE_PARENT)),
        );
      break;
      case self::CST_BULK_TRASH :
        $this->crudType = self::CST_DELETE;
        // Construction des listings suite à la sélection multiple.
        $arrIds = array();
        $arrLabels = array();
        foreach($this->urlParams[self::CST_POST] as $key=> $value) {
          $Adulte = $this->AdulteServices->selectLocal($value);
          $arrLabels[] = $Adulte->getFullName();
          $arrIds[] = $value;
        }
        $this->arrIds                   = $arrIds;
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Message de confirmation à afficher - 1
          sprintf(self::MSG_CONFIRM_SUPPR_PARENTS, implode(', ', $arrLabels)),
          // Id de l'objet ou des objets à supprimer - 2
          implode(',', $arrIds),
          // Url d'annulation de l'opération - 3
          $this->getQueryArg(array(self::CST_ONGLET=>self::PAGE_PARENT)),
        );
      break;
      case self::CST_BULK_EXPORT :
        foreach($this->urlParams[self::CST_POST] as $key=> $value) {
          $arrIds[] = $value;
        }
        $this->arrIds                   = $arrIds;
      case self::CST_CREATE :
      default :
        $this->crudType = self::CST_CREATE;
        $attributesForm  = array('', '', '', '', '');
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Contenu du Formulaire - 1
          $this->getRender($this->urlTemplateForm, $attributesForm),
          // Url d'annulation de l'opération - 2
          $this->getQueryArg(array(self::CST_ONGLET=>self::PAGE_PARENT)),
        );
      break;
    }
  }

  /**
   * Gestion de l'affichage de la page.
   * @return string
   * @version 1.21.06.10
   * @since 1.21.06.10
   */
  public function getListingPage()
  {
    /////////////////////////////////////////////////////////////////////////////
    // On récupère toutes les matières puis on concatène les rows.
    $strRows = '';
    $Adultes = $this->AdulteServices->getAdultesWithFilters();
    foreach ($Adultes as $Adulte) {
      $Bean = $Adulte->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($Adulte->getId(), $this->arrIds));
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
      $this->getCardImport(self::PAGE_PARENT),
    );
    return $this->getRender($this->urlTemplatePageAdulteAdmin, $attributes);
  }

}
