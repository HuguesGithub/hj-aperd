<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MainPageBean
 * @author Hugues
 * @version 1.00.00
 * @since 1.00.00
 */
class MainPageBean extends UtilitiesBean implements ConstantsInterface
{
  /**
   * Template pour afficher le header principal
   * @var $tplMainHeaderContent
   */
  protected $tplMainHeaderContent  = 'web/pages/public/public-main-header.php';
  /**
   * Template pour afficher le footer principal
   * @var $tplMainFooterContent
   */
  protected $tplMainFooterContent  = 'web/pages/public/public-main-footer.php';
  /**
   * La classe du shell pour montrer plus ou moins le haut de l'image de fond.
   * @var $shellClass
   */
  protected $shellClass;

  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function displayPublicFooter()
  {
    $args = array(admin_url('admin-ajax.php'));
    return $this->getRender($this->tplMainFooterContent, $args);
  }
  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function displayPublicHeader()
  {
    $args = array();
    return $this->getRender($this->tplMainHeaderContent, $args);
  }
  /**
   * @return Bean
   * @version 1.00.00
   * @since 1.00.00
   */
  public static function getPageBean()
  {
    if (is_front_page()) {
      $returned = new WpPageHomeBean();
    } else {
      $post = get_post();
      if (empty($post)) {
        // On a un problème (ou pas). On pourrait être sur une page avec des variables, mais qui n'est pas prise en compte.
        $slug = str_replace('/', '', $_SERVER['REDIRECT_SCRIPT_URL']);
        $args = array(
            'name'=>$slug,
            'post_type'=>'page',
            'numberposts'=>1
      );
        $my_posts = get_posts($args);
        $post = array_shift($my_posts);
      }
      if ($post->post_type == 'page') {
        $returned = new WpPageBean($post);
      } elseif ($post->post_type == 'post') {
        $returned = new WpPostBean($post);
      } else {
        $returned = new WpPageError404Bean();
      }
    }
    return $returned;
  }
  /**
   * @param array $addArg
   * @param array $remArg
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getQueryArg($addArg, $remArg=array())
  {
    $addArg['page'] = 'hj-aperd/admin_manage.php';
    $remArg[] = 'form';
    $remArg[] = 'id';
    return add_query_arg($addArg, remove_query_arg($remArg, 'http://aperd.jhugues.fr/wp-admin/admin.php'));
  }
  /**
   * @return bool
   * @version 1.00.00
   * @since 1.00.00
   */
  public static function isAdmin()
  { return current_user_can('manage_options'); }
  /**
   * @return string
   * @version 1.00.00
   * @since 1.00.00
   */
  public function getShellClass()
  { return $this->shellClass; }
  /**
   * @param string $id
   * @param string $default
   * @return mixed
   * @version 1.00.00
   * @since 1.00.00
   */
  public function initVar($id, $default='')
  {
    if (isset($_POST[$id])) {
      return $_POST[$id];
    }
    if (isset($_GET[$id])) {
      return $_GET[$id];
    }
    return $default;
  }

}
