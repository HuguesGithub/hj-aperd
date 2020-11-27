<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ClasseScolaire
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class ClasseScolaire extends LocalDomain
{
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Libellé de la Classe
   * @var string $labelClasse
   */
  protected $labelClasse;
  /**
   * @return int
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getId()
  { return $this->id; }
  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getLabelClasse()
  { return $this->labelClasse; }
  /**
   * @param int $id
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setId($id)
  { $this->id=$id; }
  /**
   * @param string $labelClasse
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setLabelClasse($labelClasse)
  { $this->labelClasse=$labelClasse; }
  /**
   * @return array
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getClassVars()
  { return get_class_vars('ClasseScolaire'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return ClasseScolaire
   * @version 1.00.00
   * @since 1.00.00
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new ClasseScolaire(), self::getClassVars(), $row); }
  /**
   * @return ClasseScolaireBean
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getBean()
  { return new ClasseScolaireBean($this); }
}
