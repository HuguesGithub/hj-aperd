var $hj = jQuery;
$hj(document).ready(function(){
  addCloseEvent();
  addStepperEvent();
  addTextAreaEvent();
  addBilanEvent();
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
          obj = JSON.parse(response);
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
  });
}
function closeStep(index) {
  var isDone = true;
  $hj('.stepper li:nth-child('+(index+1)+') textarea[required=""]').each(function(){
    if ($hj(this).val()=='') {
      isDone = false;
      $hj(this).addClass('is-invalid');
    } else {
      $hj(this).removeClass('is-invalid');
    }
  });
  $hj('.stepper li:nth-child('+(index+1)+') input[required=""]').each(function(){
    if ($hj(this).val()=='') {
      isDone = false;
      $hj(this).addClass('is-invalid');
    } else {
      $hj(this).removeClass('is-invalid');
    }
  });
  $hj('.stepper li:nth-child('+(index+1)+') select[required=""]').each(function(){
    if ($hj(this).val()=='-1') {
      isDone = false;
      $hj(this).addClass('is-invalid');
    } else {
      $hj(this).removeClass('is-invalid');
    }
  });
  // Ici, on vérifie que les champs de l'étape qui sont requis sont bien tous renseignés.
  // Si c'est le cas, on ajoute la classe "done". Sinon, la classe "wrong".
  if (isDone) {
    $hj('.stepper li:nth-child('+(index+1)+')').addClass('done');
  } else {
    $hj('.stepper li:nth-child('+(index+1)+')').addClass('wrong');
  }
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
