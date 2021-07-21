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

  if ($hj('#draganddrophandler')) {
    var obj = $hj("#dragandrophandler");
    obj.on('dragenter', function (e)
    {
        e.stopPropagation();
        e.preventDefault();
        $hj(this).addClass('hoveringDragAndDrop');
    });
    obj.on('dragover', function (e)
    {
         e.stopPropagation();
         e.preventDefault();
    });
    obj.on('drop', function (e)
    {

         $hj(this).removeClass('hoveringDragAndDrop');
         e.preventDefault();
         var files = e.originalEvent.dataTransfer.files;

         //We need to send dropped files to Server
         handleFileUpload(files,obj);
    });

    $hj(document).on('dragenter', function (e)
    {
        e.stopPropagation();
        e.preventDefault();
    });
    $hj(document).on('dragover', function (e)
    {
      e.stopPropagation();
      e.preventDefault();
      obj.removeClass('hoveringDragAndDrop');
    });
    $hj(document).on('drop', function (e)
    {
        e.stopPropagation();
        e.preventDefault();
    });
  }
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

function handleFileUpload(files,obj) {
   for (var i = 0; i < files.length; i++) {
        var fd = new FormData();
        fd.append('action', 'dealWithAjax');
        fd.append('ajaxAction', 'importFile');
        fd.append('importType', $hj('#post-import-drag-drop input[name="importType"]').val());
        fd.append('fileToImport', files[i]);

        var status = new createStatusbar(obj); //Using this we can set progress.
        status.setFileNameSize(files[i].name,files[i].size);
        sendFileToServer(fd,status);

   }
}

function sendFileToServer(formData,status)
{
    var jqXHR=$hj.ajax({
            xhr: function() {
            var xhrobj = $hj.ajaxSettings.xhr();
            if (xhrobj.upload) {
                    xhrobj.upload.addEventListener('progress', function(event) {
                        var percent = 0;
                        var position = event.loaded || event.position;
                        var total = event.total;
                        if (event.lengthComputable) {
                            percent = Math.ceil(position / total * 100);
                        }
                        //Set progress
                        status.setProgress(percent);
                    }, false);
                }
            return xhrobj;
        },
        url: ajaxurl,
        type: "POST",
        contentType:false,
        processData: false,
        cache: false,
        data: formData,
        success: function(data){
            status.setProgress(100);

            var obj = JSON.parse(data);
            if (obj['the-list'] != '') {
              $hj('#the-list').html(obj['the-list']);
            }
            if (obj['alertBlock'] != '') {
              $hj('#alertBlock').html(obj['alertBlock']);
              $hj('button.close').unbind().click(function(){
                if ($hj(this).data('dismiss')=='alert') {
                  $hj(this).parent().remove();
                }
              });
            }
        }
    });

    status.setAbort(jqXHR);
}

var rowCount=0;
function createStatusbar(obj)
{
     rowCount++;
     var row="odd";
     if(rowCount %2 ==0) row ="even";
     this.statusbar = $hj("<div class='statusbar "+row+"'></div>");
     this.filename = $hj("<div class='filename'></div>").appendTo(this.statusbar);
     this.size = $hj("<div class='filesize'></div>").appendTo(this.statusbar);
     this.progressBar = $hj("<div class='progressBar'><div></div></div>").appendTo(this.statusbar);
     this.abort = $hj("<div class='abort'>Abort</div>").appendTo(this.statusbar);
     obj.after(this.statusbar);

    this.setFileNameSize = function(name,size)
    {
        var sizeStr="";
        var sizeKB = size/1024;
        if(parseInt(sizeKB) > 1024)
        {
            var sizeMB = sizeKB/1024;
            sizeStr = sizeMB.toFixed(2)+" MB";
        }
        else
        {
            sizeStr = sizeKB.toFixed(2)+" KB";
        }

        this.filename.html(name);
        this.size.html(sizeStr);
    }
    this.setProgress = function(progress)
    {
        var progressBarWidth =progress*this.progressBar.width()/ 100;
        this.progressBar.find('div').animate({ width: progressBarWidth }, 10).html(progress + "% ");
        if(parseInt(progress) >= 100)
        {
            this.abort.hide();
        }
    }
    this.setAbort = function(jqxhr)
    {
        var sb = this.statusbar;
        this.abort.click(function()
        {
            jqxhr.abort();
            sb.hide();
        });
    }
}
