<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AnneeScolaire
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class AnneeScolaire extends LocalDomain
{
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Libellé de l'année scolaire
   * @var string $anneeScolaire
   */
  protected $anneeScolaire;
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
  public function getAnneeScolaire()
  { return $this->anneeScolaire; }
  /**
   * @param int $id
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setId($id)
  { $this->id=$id; }
  /**
   * @param string $anneeScolaire
   * @version 1.00.00
   * @since 1.00.00
   */
  public function setAnneeScolaire($anneeScolaire)
  { $this->anneeScolaire=$anneeScolaire; }
  /**
   * @return array
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getClassVars()
  { return get_class_vars('AnneeScolaire'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return AnneeScolaire
   * @version 1.00.00
   * @since 1.00.00
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new AnneeScolaire(), self::getClassVars(), $row); }
  /**
   * @return AnneeScolaireBean
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getBean()
  { return new AnneeScolaireBean($this); }
}
