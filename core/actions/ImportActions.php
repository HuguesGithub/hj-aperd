<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * ImportActions
 * @author Hugues
 * @version 1.21.07.15
 * @since 1.21.06.01
 */
class ImportActions extends LocalActions
{

  /**
   * Constructeur
   */
  public function __construct($post=null)
  {
    parent::__construct();
    $this->AnneeScolaireServices = new AnneeScolaireServices();
    $this->DivisionServices = new DivisionServices();
    $this->EnseignantServices = new EnseignantServices();
    $this->EnseignantMatiereServices = new EnseignantMatiereServices();
    $this->MatiereServices = new MatiereServices();
    $this->ProfPrincipalServices = new ProfPrincipalServices();
    $this->post = $post;
  }

  /**
   * @param string $importType
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.07.15
   * @since 1.21.06.01
   */
  public static function dealWithStaticImport($importType, &$notif, &$msg)
  {
    switch ($importType) {
      case self::PAGE_ADMINISTRATION :
        $theList = AdministrationActions::dealWithStatic(self::CST_IMPORT, $params);
        $notif = $params['notif'];
        $msg   = $params['msg'];
      break;
      case self::PAGE_ANNEE_SCOLAIRE :
        $theList = AnneeScolaireActions::dealWithStatic(self::CST_IMPORT, $params);
        $notif = $params['notif'];
        $msg   = $params['msg'];
      break;
      case self::PAGE_COMPO_DIVISION :
        $theList = CompoDivisionActions::dealWithStatic(self::CST_IMPORT, $params);
        $notif = $params['notif'];
        $msg   = $params['msg'];
      break;
      case self::PAGE_DIVISION :
        $theList = DivisionActions::dealWithStatic(self::CST_IMPORT, $params);
        $notif = $params['notif'];
        $msg   = $params['msg'];
      break;
      case self::PAGE_ELEVE :
        $theList = EleveActions::dealWithStatic(self::CST_IMPORT, $params);
        $notif = $params['notif'];
        $msg   = $params['msg'];
      break;
      case self::PAGE_ENSEIGNANT :
        $theList = EnseignantActions::dealWithStatic(self::CST_IMPORT, $params);
        $notif = $params['notif'];
        $msg   = $params['msg'];
      break;
      case self::PAGE_MATIERE :
        $theList = MatiereActions::dealWithStatic(self::CST_IMPORT, $params);
        $notif = $params['notif'];
        $msg   = $params['msg'];
      break;
      case self::PAGE_PARENT :
        $theList = AdulteActions::dealWithStatic(self::CST_IMPORT, $params);
        $notif = $params['notif'];
        $msg   = $params['msg'];
      break;
      case self::PAGE_PARENT_DELEGUE :
        $theList = ParentDelegueActions::dealWithStatic(self::CST_IMPORT, $params);
        $notif = $params['notif'];
        $msg   = $params['msg'];
      break;
      default :
        return 'Erreur dans ImportActions > dealWithStatic [<strong>'.$importType.'</strong>] non défini.';
      break;
    }
    return '{"the-list": '.json_encode($theList).',"alertBlock": '.json_encode('<div class="alert alert-'.$notif.' alert-dismissible fade show" role="alert">'.$msg.'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>').'}';
  }

}
