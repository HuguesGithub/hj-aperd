<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageEnseignantsBean
 * @author Hugues
 * @version 1.21.06.17
 * @since 1.21.06.01
 */
class AdminPageEnseignantsBean extends AdminPageBean
{
  protected $urlTemplatePageEnseignantAdmin = 'web/pages/admin/board-enseignants.php';
  protected $urlTemplateForm = 'web/pages/admin/fragments/form-enseignant.php';

  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
    $this->EnseignantServices = new EnseignantServices();
    $this->Services           = new EnseignantServices();
    // Initialisation de l'Enseignant sélectionné s'il y en a un.
    if ($urlParams!=null && isset($urlParams['id'])) {
      $this->Enseignant = $this->EnseignantServices->selectLocal($urlParams['id']);
    } else {
      $this->Enseignant = new Enseignant();
    }
    // On stocke les paramètres
    $this->urlParams = $urlParams;
    // On prépare le stockage pour les ids multiples si existants.
    $this->arrIds = array();
    // Autres Services
    $this->ProfPrincipalServices = new ProfPrincipalServices();
    $this->AnneeScolaireServices = new AnneeScolaireServices();
    $this->DivisionServices = new DivisionServices();
  }
  /**
   * Retourne l'Enseignant
   * @return Enseignant
   * @version 1.21.06.06
   * @since 1.21.06.06
   */
  public function getObject()
  { return $this->Enseignant; }
  /**
   * Retourne le Service
   * @return EnseignantServices
   * @version 1.21.06.06
   * @since 1.21.06.06
   */
  public function getServices()
  { return $this->EnseignantServices; }
  /**
   * @param array $urlParams
   * @return $Bean
   * @version 1.21.06.06
   * @since 1.21.06.06
   */
  public static function getStaticContentPage($urlParams)
  {
    ///////////////////////////////////////////:
    // Initialisation des valeurs par défaut
    $Bean = new AdminPageEnseignantsBean($urlParams);
    return $Bean->getContentPage();
  }
  /**
   * @param array $urlParams
   * @return string
   * @version 1.21.06.17
   * @since 1.21.06.06
   */
  public function getContentPage()
  {
    ///////////////////////////////////////////
    // Initialisation des valeurs par défaut
    $msg = '';
    $initPanel = self::CST_CREATE;

    // Analyse de l'action éventuelle.
    if (!isset($this->urlParams['filter_action']) && isset($this->urlParams[self::CST_POSTACTION])) {
      switch($this->urlParams[self::CST_POSTACTION]) {
        case self::CST_CREATION :
          // Exécution de la création
          $this->Enseignant->setNomEnseignant($this->urlParams[self::FIELD_NOMENSEIGNANT]);
          $this->Enseignant->setMatiereId($this->urlParams[self::FIELD_MATIERE_ID]);
          $this->Enseignant->insert($notif, $msg);
          $this->Enseignant = new Enseignant();
        break;
        case self::CST_EDITION :
          // Exécution de la mise à jour
          $this->Enseignant->setNomEnseignant($this->urlParams[self::FIELD_NOMENSEIGNANT]);
          $this->Enseignant->setMatiereId($this->urlParams[self::FIELD_MATIERE_ID]);
          $this->Enseignant->update($notif, $msg);
          $initPanel = self::CST_EDIT;
        break;
        case self::CST_SUPPRESSION :
          // Exécution de la suppression unitaire ou groupée
          $this->delete($notif, $msg);
          $this->Enseignant = new Enseignant();
        break;
        case self::CST_IMPORT :
          // Exécution de l'import
          $this->import($notif, $msg);
          $this->Enseignant = new Enseignant();
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
                $msg = ExportActions::dealWithStaticExport(self::PAGE_ENSEIGNANT, $this->urlParams[self::CST_POST]);
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
   * @version 1.21.06.06
   * @since 1.21.06.01
   */
  public function initPanels($action)
  {
    $AnneeScolaireBean = new AnneeScolaireBean();
    $DivisionBean = new DivisionBean();
    $MatiereBean = new MatiereBean();

    switch ($action) {
      case self::CST_DELETE :
        $this->crudType = self::CST_DELETE;
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Message de confirmation à afficher - 1
          sprintf(self::MSG_CONFIRM_SUPPR_ENSEIGNANT, $this->Enseignant->getFullName()),
          // Id de l'objet ou des objets à supprimer - 2
          $this->Enseignant->getId(),
          // Url d'annulation de l'opération - 3
          $this->getQueryArg(array(self::CST_ONGLET=>self::PAGE_ENSEIGNANT)),
        );
      break;
      case self::CST_CREATION :
      case self::CST_EDITION  :
      case self::CST_EDIT     :
        $this->crudType = self::CST_EDIT;

        if ($this->Enseignant==null) {
          $this->Enseignant = new Enseignant();
          $ProfPrincipal = new ProfPrincipal();
        } else {
          $ProfPrincipal = $this->ProfPrincipalServices->getProfPrincipalsWithFilters(array(self::FIELD_ENSEIGNANT_ID=>$this->Enseignant->getId()));
          if ($this->Enseignant->getId()=='' || empty($ProfPrincipal)) {
            $ProfPrincipal = new ProfPrincipal();
          } else {
            $ProfPrincipal = array_shift($ProfPrincipal);
          }
        }

        $attributesForm  = array(
          // Genre de l'Enseignant - 1
          $this->Enseignant->getGenre(),
          // Nom de l'Enseignant - 2
          $this->Enseignant->getNomEnseignant(),
          // Prénom de l'Enseignant - 3
          $this->Enseignant->getPrenomEnseignant(),
          // Liste déroulante sur la Matière enseignée par l'Enseignant - 4
          $MatiereBean->getSelect(self::FIELD_MATIERE_ID, self::CST_DEFAULT_SELECT, $this->Enseignant->getMatiereId()),
          // Liste déroulante sur la Division enseignée par l'Enseignant - 5
          $DivisionBean->getSelect(self::FIELD_DIVISION_ID, self::CST_DEFAULT_SELECT, $ProfPrincipal->getDivisionId()),
          // Liste déroulante sur l'Année Scolaire enseignée par l'Enseignant - 6
          $AnneeScolaireBean->getSelect(self::FIELD_ANNEESCOLAIRE_ID, self::CST_DEFAULT_SELECT, $ProfPrincipal->getAnneeScolaireId()),
        ) ;
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Contenu du Formulaire - 1
          $this->getRender($this->urlTemplateForm, $attributesForm),
          // Id de l'objet ou des objets à supprimer - 2
          $this->Enseignant->getId(),
          // Url d'annulation de l'opération - 3
          $this->getQueryArg(array(self::CST_ONGLET=>self::PAGE_ENSEIGNANT)),
        );
      break;
      case self::CST_BULK_TRASH :
        $this->crudType = self::CST_DELETE;
        // Construction des listings suite à la sélection multiple.
        $arrIds = array();
        $arrLabels = array();
        foreach($this->urlParams[self::CST_POST] as $key=> $value) {
          $Enseignant = $this->EnseignantServices->selectLocal($value);
          $arrLabels[] = $Enseignant->getFullName();
          $arrIds[] = $value;
        }
        $this->arrIds                   = $arrIds;
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Message de confirmation à afficher - 1
          sprintf(self::MSG_CONFIRM_SUPPR_ENSEIGNANTS, implode(', ', $arrLabels)),
          // Id de l'objet ou des objets à supprimer - 2
          implode(',', $arrIds),
          // Url d'annulation de l'opération - 3
          $this->getQueryArg(array(self::CST_ONGLET=>self::PAGE_ENSEIGNANT)),
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
        $attributesForm  = array(
          '','','',
          $MatiereBean->getSelect(self::FIELD_MATIERE_ID, self::CST_DEFAULT_SELECT),
          $DivisionBean->getSelect(self::FIELD_DIVISION_ID, self::CST_DEFAULT_SELECT),
          $AnneeScolaireBean->getSelect(self::FIELD_ANNEESCOLAIRE_ID, self::CST_DEFAULT_SELECT),
        );
        // Définition des attributs de la Card CRUD
        $this->attributesCardCRUD = array(
          // Contenu du Formulaire - 1
          $this->getRender($this->urlTemplateForm, $attributesForm),
          // Url d'annulation de l'opération - 2
          $this->getQueryArg(array(self::CST_ONGLET=>self::PAGE_ENSEIGNANT)),
        );
      break;
    }
  }

  /**
   * Gestion de l'affichage de la page.
   * @return string
   * @version 1.21.06.06
   * @since 1.21.06.01
   */
  public function getListingPage()
  {
    $MatiereBean = new MatiereBean();
    /////////////////////////////////////////////////////////////////////////////
    // S'il y a un filtre sur la matière, on le récupère puis on construit la liste déroulante associée
    $args = array();
    if (isset($this->urlParams[self::FIELD_MATIERE_ID]) && $this->urlParams[self::FIELD_MATIERE_ID]!=-1) {
      $filterMatiereId = $this->urlParams[self::FIELD_MATIERE_ID];
      $args[self::FIELD_MATIERE_ID] = $filterMatiereId;
    }
    $strSelectFilters = $MatiereBean->getSelect(self::FIELD_MATIERE_ID, 'Toutes les matières', $filterMatiereId);

    /////////////////////////////////////////////////////////////////////////////
    // On récupère toutes les matières puis on concatène les rows.
    $strRows = '';
    $Enseignants = $this->EnseignantServices->getEnseignantsWithFilters($args);
    foreach ($Enseignants as $Enseignant) {
      $Bean = $Enseignant->getBean();
      $strRows .= $Bean->getRowForAdminPage(in_array($Enseignant->getId(), $this->arrIds));
    }

    /////////////////////////////////////////////////////////////////////////////
    // On restitue le template enrichi.
    $attributes = array(
      // Liste des matières affichées - 1
      $strRows,
      // Filtre - 2
      $strSelectFilters,
      // Notification suite à soumission formulaire - 3
      $this->notifications,
      // Card C(R)UD - 4
      $this->getCardCRUD($this->crudType, $this->attributesCardCRUD),
      // Card Importation - 5
      $this->getCardImport(self::PAGE_ENSEIGNANT),
    );
    return $this->getRender($this->urlTemplatePageEnseignantAdmin, $attributes);
  }


}
