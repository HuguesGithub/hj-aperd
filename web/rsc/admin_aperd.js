var $hj = jQuery;
$hj(document).ready(function(){
  addAdminEvents();

  addCheckAllEvents();

  $hj('button[data-dismiss="alert"]').on('click', function() {
    $hj(this).parent().remove();
  });

  $hj('div[data-toggle="collapse"]').on('click', function() {
    $hj($hj(this).data('target')).toggleClass('show');
    $hj(this).find('span span').toggleClass('oi-caret-bottom oi-caret-top');
  });

  $hj('#schema-base li.list-group-item-action').on('click', function() {
    var foreignKey = $hj(this).data('foreign-key');
    $hj('#schema-base li.list-group-item-action').removeClass('bg-info text-white border-info');
    $hj('#schema-base .card').removeClass('border-info');

    if (foreignKey) {
      $hj(this).addClass('bg-info text-white');
      $hj(foreignKey).addClass('border-info');
    } else {
      var key = $hj(this).data('key');
      if (key) {
        $hj(this).addClass('bg-info text-white');
        $hj('#schema-base li.list-group-item-action[data-foreign-key="#'+key+'"]').addClass('border-info');
      }
    }
  });
});

function addCheckAllEvents() {
  $hj('input[id="cb-select-all-1"], input[id="cb-select-all-2"]').on('click', function() {
    if ($hj(this).prop('checked')) {
      $hj('#the-list input[type="checkbox"]').each(function() {
        $hj(this).prop('checked', true);
      });
    } else {
      $hj('#the-list input[type="checkbox"]').each(function() {
        $hj(this).prop('checked', false);
      });
    }
  });
}

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


