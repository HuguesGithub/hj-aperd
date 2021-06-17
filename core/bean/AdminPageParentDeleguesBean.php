<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageParentDeleguesBean
 * @author Hugues
 * @version 1.21.06.17
 * @since 1.21.06.11
 */
class AdminPageParentDeleguesBean extends AdminPageBean
{
  protected $urlTemplatePageAdmin = 'web/pages/admin/board-parent-delegues.php';
  protected $urlTemplateForm = 'web/pages/admin/fragments/form-parent-delegue.php';

  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
    $this->ParentDelegueServices  = new ParentDelegueServices();
    $this->Services               = new ParentDelegueServices();
    // Initialisation du Parent Délégué sélectionné s'il y en a un.
    if ($urlParams!=null && isset($urlParams[self::FIELD_ID])) {
      $this->ParentDelegue = $this->ParentDelegueServices->selectLocal($urlParams[self::FIELD_ID]);
    } else {
      $this->ParentDelegue = new ParentDelegue();
    }
    $this->LocalObject    = $this->ParentDelegue;
    // On stocke les paramètres
    $this->urlParams = $urlParams;
    // On prépare le stockage pour les ids multiples si existants.
    $this->arrIds = array();
    $this->subMenuValue = self::PAGE_PARENT_DELEGUE;
    $this->AdulteServices = new AdulteServices();
    $this->DivisionServices = new DivisionServices();
  }
  /**
   * Retourne le Parent Délégué
   * @return ParentDelegue
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function getObject()
  { return $this->ParentDelegue; }
  /**
   * Retourne le Service
   * @return ParentDelegueService
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public function getServices()
  { return $this->ParentDelegueServices; }

  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.11
   * @since 1.21.06.11
   */
  public static function getStaticContentPage($urlParams)
  {
    ///////////////////////////////////////////:
    // Initialisation des valeurs par défaut
    $Bean = new AdminPageParentDeleguesBean($urlParams);
    return $Bean->getContentPage();
  }
  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.17
   * @since 1.21.06.11
   */
  public function getContentPage()
  {
    ///////////////////////////////////////////
    // Initialisation des valeurs par défaut
    $msg = '';
    $initPanel = self::CST_CREATE;

    ///////////////////////////////////////////
    // Analyse de l'action éventuelle.
    if (isset($this->urlParams['filter_action'])) {
      // On ne fait que filtrer, il n'y a pas d'actions associées.
    } elseif (isset($this->urlParams[self::CST_POSTACTION])) {
      switch ($this->urlParams[self::CST_POSTACTION]) {
        case self::CST_CREATION :
          // Exécution de la création
          $this->ParentDelegue->setParentId($this->urlParams[self::FIELD_PARENT_ID]);
          $this->ParentDelegue->setDivisionId($this->urlParams[self::FIELD_DIVISION_ID]);
          $this->ParentDelegue->insert($notif, $msg);
          $this->ParentDelegue = new ParentDelegue();
        break;
        case self::CST_EDITION :
          // Exécution de la création
          $this->ParentDelegue->setParentId($this->urlParams[self::FIELD_PARENT_ID]);
          $this->ParentDelegue->setDivisionId($this->urlParams[self::FIELD_DIVISION_ID]);
          $this->ParentDelegue->update($notif, $msg);
          $initPanel = self::CST_EDIT;
        break;
        case self::CST_SUPPRESSION :
          // Exécution de la suppression unitaire ou groupée
          $this->delete($notif, $msg);
          $this->ParentDelegue = new ParentDelegue();
        break;
        case self::CST_IMPORT :
          // Exécution de l'import
          $this->import($notif, $msg);
          $this->ParentDelegue = new ParentDelegue();
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
                $msg = ExportActions::dealWithStaticExport(self::PAGE_PARENT_DELEGUE, $this->urlParams[self::CST_POST]);
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
    $this->msgConfirmDelete = sprintf(self::MSG_CONFIRM_SUPPR_PARENT_DELEGUE, $this->ParentDelegue->getLabelComplet());
    $this->attributesFormNew = array('','');
    $this->tagConfirmDeleteMultiple = self::MSG_CONFIRM_SUPPR_PARENT_DELEGUES;
    $this->attributesFormEdit  = array(
      // Choix du Parent - 1
      $this->ParentDelegue->getAdulte()->getBean()->getSelect(self::FIELD_PARENT_ID, self::CST_DEFAULT_SELECT, $this->ParentDelegue->getParentId()),
      // Choix de la Division - 2
      $this->ParentDelegue->getDivision()->getBean()->getSelect(self::FIELD_DIVISION_ID, self::CST_DEFAULT_SELECT, $this->ParentDelegue->getDivisionId()),
    ) ;
    $this->initPanels($initPanel);
    ///////////////////////////////////////////:
    // On retourne le listing et les panneaux latéraux droit
    return $this->getListingPage();
  }


  /**
   * Gestion de l'affichage de la page.
   * @return string
   * @version 1.21.06.12
   * @since 1.21.06.11
   */
  public function getListingPage()
  {
    //////////////////////////////////////////////////////////////////
    // On récupère tous les Parents Délégués et on restreint l'affichage
    $ParentDelegues = $this->ParentDelegueServices->getParentDeleguesWithFilters();
    if (empty($ParentDelegues)) {
      $strRows = '<tr><td colspan="5"><em>Aucun résultat</em></td></tr>';
    } else {
      $strRows = '';
      while (!empty($ParentDelegues)) {
        $ParentDelegue = array_shift($ParentDelegues);
        $strRows .= $ParentDelegue->getBean()->getRowForAdminPage(in_array($ParentDelegue->getId(), $this->arrIds), $queryArg);
      }
    }
    //////////////////////////////////////////////////////////////////

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
      $this->getCardImport(self::PAGE_PARENT_DELEGUE),
    );
    return $this->getRender($this->urlTemplatePageAdmin, $attributes);
  }

}
