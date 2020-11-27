<!DOCTYPE html>
<html dir="ltr" lang="fr">
  <head>
    <title>Aperd - Compte Rendu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php
wp_head();
$PageBean = MainPageBean::getPageBean();
$commonUrl = 'http://aperd.jhugues.fr/wp-content/plugins/mycommon/';
$pluginUrl = 'http://aperd.jhugues.fr/wp-content/plugins/hj-aperd/';
?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
      integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo $commonUrl; ?>web/rsc/css/jquery-ui.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?php echo $commonUrl; ?>web/rsc/css/bootstrap-4.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?php echo $pluginUrl; ?>web/rsc/aperd.css" type="text/css" media="all" />
  </head>
  <body>
    <div id="shell" class="shell <?php echo $PageBean->getShellClass(); ?>">
<?php
  echo $PageBean->displayPublicHeader();
  echo $PageBean->getContentPage();
  echo $PageBean->displayPublicFooter();
?>
    </div>
    <script type='text/javascript' src='<?php echo $commonUrl; ?>web/rsc/js/jquery-ui-min.js'></script>
    <script type='text/javascript' src='<?php echo $pluginUrl; ?>web/rsc/aperd.js'></script>
<?php wp_footer(); ?>
  </body>
</html>
