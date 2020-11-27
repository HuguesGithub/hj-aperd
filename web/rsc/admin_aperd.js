var $hj = jQuery;
$hj(document).ready(function(){
  addAdminEvents();
});

function addAdminEvents() {
  $hj('.nav-link').on('click', function() {
    $hj('.nav-link').removeClass('active');
    $hj(this).addClass('active');
    $hj('.wrap > .row').hide();
    $hj($hj(this).attr('href')).show();
  });
  if (defaultTab!=undefined) {
    $hj('.nav-link[href="'+defaultTab+'"]').trigger('click');
  }
}
