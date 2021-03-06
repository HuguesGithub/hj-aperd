<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LocalDomain
 * @author Hugues
 * @version 1.21.06.17
 * @since 1.21.06.04
 */
class LocalDomain extends GlobalDomain implements ConstantsInterface
{
  /**
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function toJson()
  {
    $classVars = $this->getClassVars();
    $str = '';
    foreach ($classVars as $key => $value) {
      if ($str!='') {
        $str .= ', ';
      }
      $str .= '"'.$key.'":'.json_encode($this->getField($key));
    }
    return '{'.$str.'}';
  }

  public function toCsv($sep=';')
  {
    $classVars = $this->getClassVars();
    $arrValues = array();
    foreach ($classVars as $key => $value) {
      $arrValues[] = $this->getField($key);
    }
    return implode($sep, $arrValues);
  }
  /**
   * @param array $post
   * @return bool
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public function updateWithPost($post)
  {
    $classVars = $this->getClassVars();
    unset($classVars['id']);
    $doUpdate = false;
    foreach ($classVars as $key => $value) {
      if (is_array($post[$key])) {
        $value = stripslashes(implode(';', $post[$key]));
      } else {
        $value = stripslashes($post[$key]);
      }
      if ($this->{$key} != $value) {
        $doUpdate = true;
        $this->{$key} = $value;
      }
    }
    return $doUpdate;
  }
  /**
   * @return int
   * @version 1.21.06.04
   * @since 1.21.06.04
   */
  public static function getWpUserId()
  { return get_current_user_id(); }

  /**
   * @param string $rowContent
   * @param string &$notif
   * @param string &$msg
   * @return boolean
   * @version 1.21.06.09
   * @since 1.21.06.01
   */
  public function controleEntete($rowContent, &$notif, &$msg)
  {
    if ($rowContent!=$this->getCsvEntete()) {
      $notif = self::NOTIF_DANGER;
      $msg = sprintf(self::MSG_ERREUR_CONTROL_ENTETE, $this->getCsvEntete());
      return true;
    }
    return false;
  }
  /**
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.06.08
   * @since 1.21.06.01
   */
  public function delete(&$notif, &$msg)
  {
    $this->Services->deleteLocal($this);
    $notif = self::NOTIF_SUCCESS;
    $msg   = self::MSG_SUCCESS_DELETE;
  }
  /**
   * @param string &$notif
   * @param string &$msg
   * @param array $urlParams
   * @return boolean
   * @version 1.21.06.08
   * @since 1.21.06.01
   */
  public function insert(&$notif, &$msg, $urlParams=array())
  {
    if ($this->controleDonnees($notif, $msg)) {
      $this->Services->insertLocal($this);
      $notif = self::NOTIF_SUCCESS;
      $msg   = self::MSG_SUCCESS_CREATE;
      return true;
    }
    return false;
  }
  /**
   * @param string &$notif
   * @param string &$msg
   * @param array $urlParams
   * @return boolean
   * @version 1.21.06.08
   * @since 1.21.06.01
   */
  public function update(&$notif, &$msg, $urlParams=array())
  {
    if ($this->controleDonnees($notif, $msg)) {
      if ($this->id=='') {
        $notif = self::NOTIF_WARNING;
        $msg   = self::MSG_ERREUR_CONTROL_ID;
      } else {
        $this->Services->updateLocal($this);
        $notif = self::NOTIF_SUCCESS;
        $msg   = self::MSG_SUCCESS_UPDATE;
        return true;
      }
    }
    return false;
  }
  /**
   * @param string $rowContent
   * @param string $sep
   * @param string &$notif
   * @param string &$msg
   * @return boolean
   * @version 1.21.06.17
   * @since 1.21.06.17
   */
  public function controleDonneesAndAct($Obj, &$notif, &$msg)
  {
    if (!$this->controleDonnees($notif, $msg)) {
      return true;
    }
    $id = $Obj->getId();
    // Si les contrôles sont okay, on peut insérer ou mettre à jour
    if ($id=='') {
      // Si id n'est pas renseigné. C'est une création. Il faut vérifier que le label n'existe pas déjà.
      $this->Services->insertLocal($Obj);
    } else {
      $ObjectInBase = $this->Services->selectLocal($id);
      if ($ObjectInBase->getId()=='') {
        // Sinon, si id n'existe pas, c'est une création. Cf au-dessus
        $this->Services->insertLocal($Obj);
      } else {
        // Si id existe, c'est une édition, même contrôle que ci-dessus.
        $this->setId($id);
        $this->Services->updateLocal($Obj);
      }
    }
    return false;
  }
}
