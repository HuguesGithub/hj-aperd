<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Matiere
 * @author Hugues
 * @version 1.21.06.17
 * @since 1.21.06.04
 */
class Matiere extends LocalDomain
{
  //////////////////////////////////////////////////:
  // ATTRIBUTES
  //////////////////////////////////////////////////:
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Libellé de la Matière
   * @var string $labelMatiere
   */
  protected $labelMatiere;

  //////////////////////////////////////////////////:
  // GETTERS & SETTERS
  //////////////////////////////////////////////////:
  /**
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getLabelMatiere()
  { return $this->labelMatiere; }
  /**
   * @param string $labelMatiere
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function setLabelMatiere($labelMatiere)
  { $this->labelMatiere=$labelMatiere; }

  //////////////////////////////////////////////////:
  // CONSTRUCT - CLASSVARS - CONVERT - BEAN
  //////////////////////////////////////////////////:
  /**
   * @param array $attributes
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->MatiereServices = new MatiereServices();
    $this->Services        = new MatiereServices();
  }
  /**
   * @return array
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getClassVars()
  { return get_class_vars('Matiere'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return Matiere
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new Matiere(), self::getClassVars(), $row); }
  /**
   * @return MatiereBean
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function getBean()
  { return new MatiereBean($this); }

  //////////////////////////////////////////////////
  // METHODES
  //////////////////////////////////////////////////
  /**
   * @param string $sep
   * @return string
   * @version 1.21.06.08
   * @since 1.21.06.01
   */
  public function getCsvEntete($sep=self::SEP)
  { return implode($sep, array(self::FIELD_ID, self::FIELD_LABELMATIERE)); }
  /**
   * @param string $sep
   * @return string
   * @version 1.21.06.08
   * @since 1.21.06.08
   */
  public function toCsv($sep=self::SEP)
  { return implode($sep, array($this->id, $this->labelMatiere)); }
  /**
   * @param string $rowContent
   * @param string $sep
   * @param string &$notif
   * @param string &$msg
   * @return boolean
   * @version 1.21.06.17
   * @since 1.21.06.08
   */
  public function controleImportRow($rowContent, $sep, &$notif, &$msg)
  {
    list($id, $importedLabelMatiere) = explode($sep, $rowContent);
    $this->setId($id);
    $importedLabelMatiere = trim(str_replace(self::EOL, '', $importedLabelMatiere));
    $this->setLabelMatiere($importedLabelMatiere);

    if (!$this->controleDonnees($notif, $msg)) {
      return true;
    }
    // Si les contrôles sont okay, on peut insérer ou mettre à jour
    if ($id=='') {
      // Si id n'est pas renseigné. C'est une création. Il faut vérifier que le label n'existe pas déjà.
      $this->Services->insertLocal($this);
    } else {
      $DivisionInBase = $this->Services->selectLocal($id);
      if ($DivisionInBase->getId()=='') {
        // Sinon, si id n'existe pas, c'est une création. Cf au-dessus
        $this->Services->insertLocal($this);
      } else {
        // Si id existe, c'est une édition, même contrôle que ci-dessus.
        $this->setId($id);
        $this->Services->updateLocal($this);
      }
    }
    return false;
  }
  /**
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.06.08
   * @since 1.21.06.08
   */
  public function controleDonnees(&$notif, &$msg)
  {
    // Le libellé doit être renseigné
    if (empty($this->labelMatiere)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_EXISTENCE;
      return false;
    }
    // Le libellé doit être unique et donc, ne pas exister en base
    $Divisions = $this->MatiereServices->getMatieresWithFilters(array(self::FIELD_LABELMATIERE=>$this->labelMatiere));
    if (!empty($Divisions)) {
      $notif = self::NOTIF_DANGER;
      $msg   = self::MSG_ERREUR_CONTROL_UNICITE;
      return false;
    }
    return true;
  }

  /**
   * @return string
   * @version 1.21.06.17
   * @since 1.21.06.17
   */
  public function getFullName()
  { return $this->getLabelMatiere(); }

}
