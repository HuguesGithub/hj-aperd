<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * ExportActions
 * @author Hugues
 * @version 1.21.06.11
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

  public static function dealWithStaticExport($exportType, $ids)
  {
    $Act = new ExportActions();
    switch ($exportType) {
      case self::PAGE_ADMINISTRATION :
        return AdministrationActions::dealWithStatic(self::CST_EXPORT, $ids);
      break;
      case self::PAGE_ANNEE_SCOLAIRE :
        return AnneeScolaireActions::dealWithStatic(self::CST_EXPORT, $ids);
      break;
      case self::PAGE_DIVISION :
        return DivisionActions::dealWithStatic(self::CST_EXPORT, $ids);
      break;
      case self::PAGE_ELEVE :
        return EleveActions::dealWithStatic(self::CST_EXPORT, $ids);
      break;
      case self::PAGE_MATIERE :
        return MatiereActions::dealWithStatic(self::CST_EXPORT, $ids);
      break;
      case self::PAGE_PARENT :
        return AdulteActions::dealWithStatic(self::CST_EXPORT, $ids);
      break;


      case self::PAGE_ENSEIGNANT :
        return $Act->exportEnseignant($ids);
      break;
      case self::PAGE_COMPO_DIVISION :
        return $Act->exportCompo($ids);
      break;
    }
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
