<?php
/**
 * @author Hugues
 * @version 1.00.01
 * @since 1.00.00
 */
  define('APERD_SITE_URL', 'http://aperd.jhugues.fr/');
  define('PLUGINS_MYCOMMON', APERD_SITE_URL.'wp-content/plugins/mycommon');
  define('PLUGINS_APERD', APERD_SITE_URL.'wp-content/plugins/hj-aperd/');
  if (!defined('ABSPATH')) {
    die('Forbidden');
  }
?>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
  integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="<?php echo PLUGINS_MYCOMMON; ?>/web/rsc/css/jquery-ui.min.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo PLUGINS_APERD; ?>web/rsc/admin_aperd.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo PLUGINS_APERD; ?>web/rsc/open-iconic-bootstrap.css" type="text/css" media="all" />
<?php
  global $Aperd;
  if (empty($Aperd)) {
    $Aperd = new Aperd();
  }
  $AdminPageBean = new AdminPageBean();
  echo $AdminPageBean->getContentPage();
?>
<script type='text/javascript' src='<?php echo PLUGINS_MYCOMMON; ?>/web/rsc/js/jquery-ui-min.js'></script>
<script type='text/javascript' src='<?php echo PLUGINS_APERD; ?>web/rsc/admin_aperd.js'></script>
