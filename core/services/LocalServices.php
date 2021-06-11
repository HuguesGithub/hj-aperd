<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LocalServices
 * @author Hugues
 * @version 1.21.06.04
 * @since 1.21.06.04
 */
class LocalServices extends GlobalServices implements ConstantsInterface
{
  //////////////////////////////////////////////////
  // ATTRIBUTES
  //////////////////////////////////////////////////
  /**
   * Texte par défaut du Select
   * @var string $labelDefault
   */
  protected $labelDefault = '';
  /**
   * Valeur par défaut de la classe du Select
   * @var string $classe
   */
  protected $classe = 'form-control';
  /**
   * Le Select est-il multiple ?
   * @var boolean $multiple
   */
  protected $multiple = false;

  //////////////////////////////////////////////////
  // CONSTRUCT
  //////////////////////////////////////////////////
  /**
   * Class Constructor
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function __construct()
  {
  }

  //////////////////////////////////////////////////
  // LOCAL CRUD
  //////////////////////////////////////////////////
  /**
   * @param int $id
   * @return mixed
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function selectLocal($id)
  { return $this->select(__FILE__, __LINE__, $id); }
  /**
   * @param mixed $Obj
   * @return mixed
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function updateLocal($Obj)
  { return $this->update(__FILE__, __LINE__, $Obj); }
  /**
   * @param mixed $Obj
   * @return mixed
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function insertLocal($Obj)
  { return $this->insert(__FILE__, __LINE__, $Obj); }
  /**
   * @param mixed $Obj
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function deleteLocal($Obj)
  { $this->delete(__FILE__, __LINE__, $Obj); }









  /**
   * @param array $arrSetLabels
   * @param string $name
   * @param string $value
   * @return string
   */
  protected function getSetSelect($arrSetLabels, $name, $value)
  {
    $strSelect = '';
    $selName = $name;
    if ($this->labelDefault!='') {
      $strSelect .= '<label class="screen-reader-text" for="'.$name.'">'.$this->labelDefault.'</label>';
    }
    // On créé la base du select
    $strSelect .= '<select id="'.$name.'" name="'.$selName.'" class="'.$this->classe.'"'.($this->multiple?' multiple':'').'>';
    // S'il n'est pas multiple et qu'il a une valeur par défaut, on la met.
    if (!$this->multiple && $this->labelDefault!='') {
      $strSelect .= '<option value="">'.$this->labelDefault.'</option>';
    }
    // On parcourt l'ensemble des couples $key/$value de la liste
    if (!empty($arrSetLabels)) {
      foreach ($arrSetLabels as $key => $labelValue) {
        // Visiblement, la $key peut parfois être nulle et c'est mal.
        if ($key=='') {
          continue;
        }
        // On construit l'option.
        $strSelect .= '<option value="'.$key.'"';
        $strSelect .= ($this->isKeySelected($key, $value) ? ' selected="selected"' : '');
        $strSelect .= '>'.$labelValue.'</option>';
      }
    }
    return $strSelect.'</select>';
  }
  /**
   * @param string $key
   * @param mixed $values
   * @return boolean
   */
  protected function isKeySelected($key, $values)
  {
    // Si on ne cherche pas dans un tableau, on teste juste l'égalité.
    if (!is_array($values)) {
      return trim($key)==trim($values);
    }
    $isSelected = false;
    // Sinon, on parcourt la liste pour essayer de trouver la valeur cherchée.
    while (!empty($values)) {
      $value = array_shift($values);
      if ($key==$value) {
        $isSelected = true;
      }
    }
    return $isSelected;
  }
  /**
   * Vérifie qu'un élément du tableau n'est ni vide ni un tableau.
   * @param array $arrFilters
   * @param string $tag
   * @return boolean
   * @version 1.03.00
   */
  protected function isNonEmptyAndNoArray($arrFilters, $tag)
  { return !empty($arrFilters[$tag]) && !is_array($arrFilters[$tag]); }
  /**
   * @param array $arrFilters
   * @param string $tag
   * @param string $default
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  protected function getValueToSearch($arrFilters, $tag, $default=self::JOKER_SEARCH)
  { return ($this->isNonEmptyAndNoArray($arrFilters, $tag) ? $arrFilters[$tag] : $default); }
  /**
   * @return int
   */
  public static function getWpUserId()
  { return get_current_user_id(); }

}
