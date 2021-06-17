var $hj = jQuery;
$hj(document).ready(function(){
  addCloseEvent();
  addStepperEvent();
  addTextAreaEvent();
  addBilanEvent();
  addAjaxUploadEvent();

  // Pour éviter que l'année scolaire ou la division ne soient changées
  $hj('select[readonly]').on('click', function(e){
    var selVal = $hj(this).val();
    $hj(this).find('option[value!="'+selVal+'"]').each(function(){
      $hj(this).remove();
    });
    return false;
  });

  // Sur le clic d'un button, on doit afficher le bon panel
  $hj('.btn-group-vertical button').unbind().on('click', function(){
    $hj(this).siblings().removeClass('active');
    $hj(this).addClass('active');
    $hj('#v-pills-tabContent .tab-pane').removeClass('show').removeClass('active');
    $hj($hj(this).data('bs-target')).addClass('show').addClass('active');
    $hj($hj(this).data('bs-target')+' textarea').focus();
  });

});
function addBilanEvent() {
  $hj('select[name^="matiereId"]').unbind().change(function(){
    addChangeMatiereEvent($hj(this));
  });
}
function addChangeMatiereEvent(node) {
  if (node.val()=='' || node.val()==-1) {
    if ($hj('select[name^="matiereId"]').length>1) {
      node.parent().parent().remove();
    }
  } else {
    var data = {'action': 'dealWithAjax', 'ajaxAction': 'getNewMatiere'};
    $hj.post(
      ajaxurl,
      data,
      function(response) {
        try {
          var obj = JSON.parse(response);
          $hj('#divMatieres').append(obj.blocMatiere);
          addBilanEvent();
          addTextAreaEvent();
        } catch (e) {
          console.log("error: "+e);
          console.log(response);
        }
      }
    );
  }
}
function addTextAreaEvent() {
  $hj('.stepper textarea[required=""]').each(function(){
    if ($hj(this).val()!='') {
      $hj(this).addClass('is-valid').removeClass('is-invalid').siblings('label').addClass('active');
    }
  });

  $hj('.md-textarea').unbind().blur(function(){
    if ($hj(this).val()=='') {
      $hj(this).next().removeClass('active')
    } else {
      $hj(this).next().addClass('active')
    }
  });
}
function addCloseEvent() {
  $hj('button.close').unbind().click(function(){
    if ($hj(this).data('dismiss')=='alert') {
      $hj(this).parent().remove();
    }
  });
}
function addStepperEvent() {
  $hj('.stepper li').unbind().click(function(){
    $hj(this).siblings().removeClass('active').removeClass('done').removeClass('wrong');
    $hj(this).removeClass('done').removeClass('wrong');
    var iCurrent = $hj(this).index();
    for (var i=0; i<iCurrent; i++) {
      closeStep(i);
    }
    $hj(this).addClass('active');
    if (iCurrent==5) {
      if ($hj(this).siblings('wrong').length==0) {
        $hj(this).find('button').addClass('disabled');
      } else {
        $hj(this).find('button').removeClass('disabled');
      }
    }
  });
}
function closeStep(index) {
  var isDone = checkStep(index);
  // Ici, on vérifie que les champs de l'étape qui sont requis sont bien tous renseignés.
  // Si c'est le cas, on ajoute la classe "done". Sinon, la classe "wrong".
  if (isDone) {
    $hj('.stepper li:nth-child('+(index+1)+')').addClass('done');
  } else {
    $hj('.stepper li:nth-child('+(index+1)+')').addClass('wrong');
  }
}

function checkStep(index) {
  var isValid = true;
  $hj('.stepper li:nth-child('+(index+1)+') textarea[required=""]').each(
    function(){
      if ($hj(this).val()=='') {
        isValid = false;
        $hj(this).addClass('is-invalid');
      } else {
        $hj(this).removeClass('is-invalid');
      }
    }
  );
  $hj('.stepper li:nth-child('+(index+1)+') input[required=""]').each(
    function(){
      if ($hj(this).val()=='') {
        isValid = false;
        $hj(this).addClass('is-invalid');
      } else {
        $hj(this).removeClass('is-invalid');
      }
    }
  );
  $hj('.stepper li:nth-child('+(index+1)+') select[required=""]').each(
    function(){
      if ($hj(this).val()=='-1') {
        isValid = false;
        $hj(this).addClass('is-invalid');
      } else {
        $hj(this).removeClass('is-invalid');
      }
    }
  );
  return isValid;
}

function addAjaxUploadEvent() {
  $hj('.ajaxUpload').on('change', function(){
    var obj;
    var data;
    var value = $hj(this).val();
    var name  = $hj(this).attr('name');
    var crKey = $hj('#formCr input[name="crKey"]').val();
    var extraTreats = false;
    var bilanMatiereId = -1;
    var bilanMatiereTextarea = '';
    var bilanMatiereSelEns = '';
    var bilanMatiereSelStatus = '';
    var buttonForPanel = '';
    if (name=='observations[]' || name=='status[]' || name=='enseignantIds[]') {
      bilanMatiereId = $hj(this).closest('.tab-pane').data('bilan-matiere-id');
      data = {'action': 'dealWithAjax', 'ajaxAction': 'ajaxUpload', 'value': value, 'name': name, 'crKey': crKey, 'bilanMatiereId': bilanMatiereId};
      extraTreats = true;
      bilanMatiereTextarea = $hj(this).closest('.tab-pane').find('textarea').val();
      bilanMatiereSelEns = $hj(this).closest('.tab-pane').find('select[name="enseignantIds[]"]').val();
      bilanMatiereSelStatus = $hj(this).closest('.tab-pane').find('select[name="status[]"]').val();
      var id = $hj(this).closest('.tab-pane').attr('id');
      buttonForPanel = $hj('#'+id+'-tab');
    } else {
      data = {'action': 'dealWithAjax', 'ajaxAction': 'ajaxUpload', 'value': value, 'name': name, 'crKey': crKey};
    }
    $hj.post(
      ajaxurl,
      data,
      function(response) {
        try {
          obj = JSON.parse(response);
          if (obj['renduStep1'] != '' && obj['renduStep1'] != undefined) {
            $hj('#renduStep1').html(obj['renduStep1']);
          } else if (obj['renduStep2'] != '' && obj['renduStep2'] != undefined) {
            $hj('#renduStep2').html(obj['renduStep2']);
          } else if (obj['renduStep3'] != '' && obj['renduStep3'] != undefined) {
            $hj('#renduStep3').html(obj['renduStep3']);
          } else if (obj['renduStep4'] != '' && obj['renduStep4'] != undefined) {
            $hj('#renduStep4').html(obj['renduStep4']);
          } else if (obj['renduStep5'] != '' && obj['renduStep5'] != undefined) {
            $hj('#renduStep5').html(obj['renduStep5']);
          }
          $hj('button.close').on('click', function(){
            $hj(this).parent().remove();
          });
          if (obj['renduStep6'] != '' && obj['renduStep6'] != undefined) {
            $hj('#renduStep6').html(obj['renduStep6']);
            /*
             * TODO : ne fonctionne pas pour le moment. Mettre en évidence si un cadre a trop de contenu
            if ($hj('.apercuPdf > div').prop('scrollHeight') > $hj('.apercuPdf > div').prop('offsetHeight')) {
              $hj('.apercuPdf > div').addClass('border-danger');
            } else {
              $hj('.apercuPdf > div').addClass('border-success');
            }
            if ($hj('.apercuPdf > div+div').prop('scrollHeight') > $hj('.apercuPdf > div+div').prop('offsetHeight')) {
              $hj('.apercuPdf > div+div').addClass('border-danger');
            } else {
              $hj('.apercuPdf > div+div').addClass('border-success');
            }
            */
          }
          if (extraTreats) {
            var newBadge = 'success';
            if (bilanMatiereTextarea=='') {
              newBadge = 'warning';
            }
            if (bilanMatiereSelEns=='' || bilanMatiereSelStatus=='') {
              newBadge = 'danger';
            }
            buttonForPanel.find('.badge-display').removeClass('success danger warning').addClass(newBadge);
          }
        } catch (e) {
          console.log("error: "+e);
          console.log(response);
        }
      }
    );
  });
}


/*
function() {
  var i = $(t.children(".step:visible")).index($(this));
  if (t.data("settings").parallel && validation) $(this).addClass("temp-active"), t.validatePreviousSteps(), t.openStep(i + 1), $(this).removeClass("temp-active");
  else if (t.hasClass("linear")) {
    if (e.linearStepsNavigation) {
      var s = t.find(".step.active");
      $(t.children(".step:visible")).index($(s)) + 1 == i ? t.nextStep(void 0, !0, void 0) : $(t.children(".step:visible")).index($(s)) - 1 == i && t.prevStep(void 0)
    }
  } else t.openStep(i + 1)
}

*/
