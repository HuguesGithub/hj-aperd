<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * ExportActions
 * @author Hugues
 * @version 1.21.06.12
 * @since 1.21.06.01
 */
class ExportActions extends LocalActions
{

  /**
   * Constructeur
   */
  public function __construct($post=null)
  {
    parent::__construct();
    $this->CompoDivisionServices = new CompoDivisionServices();
    $this->DivisionServices   = new DivisionServices();
    $this->EleveServices = new EleveServices();
    $this->EnseignantServices = new EnseignantServices();
    $this->MatiereServices    = new MatiereServices();
    $this->post = $post;
  }

  /**
   * @param string $exportType
   * @param mixed $ids
   * @version 1.21.06.12
   * @since 1.21.06.01
   */
  public static function dealWithStaticExport($exportType, $ids)
  {
    $Act = new ExportActions();
    switch ($exportType) {
      case self::PAGE_ADMINISTRATION :
        $returned = AdministrationActions::dealWithStatic(self::CST_EXPORT, $ids);
      break;
      case self::PAGE_ANNEE_SCOLAIRE :
        $returned = AnneeScolaireActions::dealWithStatic(self::CST_EXPORT, $ids);
      break;
      case self::PAGE_DIVISION :
        $returned = DivisionActions::dealWithStatic(self::CST_EXPORT, $ids);
      break;
      case self::PAGE_ELEVE :
        $returned = EleveActions::dealWithStatic(self::CST_EXPORT, $ids);
      break;
      case self::PAGE_MATIERE :
        $returned = MatiereActions::dealWithStatic(self::CST_EXPORT, $ids);
      break;
      case self::PAGE_PARENT :
        $returned = AdulteActions::dealWithStatic(self::CST_EXPORT, $ids);
      break;
      case self::PAGE_PARENT_DELEGUE :
        $returned = ParentDelegueActions::dealWithStatic(self::CST_EXPORT, $ids);
      break;


      case self::PAGE_ENSEIGNANT :
        $returned = $Act->exportEnseignant($ids);
      break;
      case self::PAGE_COMPO_DIVISION :
        $returned = $Act->exportCompo($ids);
      break;
      default :
        $returned = 'Erreur dans ExportActions > dealWithStatic [<strong>'.$exportType.'</strong>] non défini.';
      break;
    }
    return $returned;
  }

  public function exportCompo($arrIds)
  {
    $arrToExport = array();
    $CompoDivision = new CompoDivision();

    $arrToExport[] = $CompoDivision->getCsvEntete();

    // On récupère les données de tous les objets sélectionnés
    foreach ($arrIds as $id) {
      $CompoDivision = $this->CompoDivisionServices->selectLocal($id);
      $arrToExport[] = $CompoDivision->toCsv();
    }

    return $this->exportFile($arrToExport, ucfirst(self::PAGE_COMPO_DIVISION));
  }

  public function exportEnseignant($arrIds)
  {
    $arrToExport = array();
    $Enseignant = new Enseignant();

    $arrToExport[] = $Enseignant->getCsvEntete(';', true);

    // On récupère les données de tous les objets sélectionnés
    foreach ($arrIds as $id) {
      $Enseignant = $this->EnseignantServices->selectLocal($id);
      $arrToExport[] = $Enseignant->toCsv(';', true);
    }

    return $this->exportFile($arrToExport, ucfirst(self::PAGE_ENSEIGNANT));
  }


  public function exportFile($data, $prefix)
  {
    $dir_name = dirname(__FILE__).'/../../web/rsc/csv-files/';
    $file_name = 'export_'.$prefix.'_'.date('Ymd_His').'.csv';
    $dst = fopen($dir_name.$file_name, 'w');
    fputs($dst, implode("\r\n", $data));
    fclose($dst);
    $file_name = '/wp-content/plugins/hj-aperd/web/rsc/csv-files/'.$file_name;
    return 'Exportation réussie. Le fichier peut être téléchargé <a href="'.$file_name.'">ici</a>.';
  }

}
