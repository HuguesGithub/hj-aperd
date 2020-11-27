<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdminPageBean
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.01
 */
class AdminPageBean extends MainPageBean
{
  /**
   * @param string $tag
   */
  public function __construct()
  {
    parent::__construct();
    $this->analyzeUri();
  }

  /**
   * @return string
   */
  public function analyzeUri()
  {
    $uri = $_SERVER['REQUEST_URI'];
    $pos = strpos($uri, '?');
    if ($pos!==false) {
      $arrParams = explode('&', substr($uri, $pos+1, strlen($uri)));
      if (!empty($arrParams)) {
        foreach ($arrParams as $param) {
          list($key, $value) = explode('=', $param);
          $this->urlParams[$key] = $value;
        }
      }
      $uri = substr($uri, 0, $pos-1);
    }
    $pos = strpos($uri, '#');
    if ($pos!==false) {
      $this->anchor = substr($uri, $pos+1, strlen($uri));
    }
    if (isset($_POST)) {
      foreach ($_POST as $key => $value) {
        $this->urlParams[$key] = $value;
      }
    }
    return $uri;
  }
  /**
   * @return string
   */
  public function getContentPage()
  {
    if (self::isAdmin()) {
      switch ($this->urlParams['onglet']) {
        case self::PAGE_CLASSE   :
          $returned = AdminPageClassesBean::getStaticContentPage($this->urlParams);
        break;
        case self::PAGE_COMPTE_RENDU :
          $returned = AdminPageCompteRendusBean::getStaticContentPage($this->urlParams);
        break;
        case self::PAGE_CONFIGURATION :
          $returned = AdminPageConfigurationsBean::getStaticContentPage($this->urlParams);
        break;
        case self::PAGE_ENSEIGNANT   :
          $returned = AdminPageEnseignantsBean::getStaticContentPage($this->urlParams);
        break;
        case self::PAGE_MATIERE      :
          $returned = AdminPageMatieresBean::getStaticContentPage($this->urlParams);
        break;
        default       :
          $returned = "Need to add <b>".$this->urlParams['onglet']."</b> to AdminPageBean > getContentPage().";
        break;
      }
    }
    return $returned;
  }
}
