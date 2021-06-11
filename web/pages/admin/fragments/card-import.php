      <div class="card bg-light">
        <div class="card-header"><strong>Importation</strong></div>
        <form action="#" method="post" id="post-import" enctype="multipart/form-data">
          <div class="card-body">
            <div class="input-group mb-2">
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="fileToImport" name="fileToImport">
                <label class="custom-file-label" for="fileToImport">Fichier</label>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="form-row">
              <input type="hidden" name="postAction" value="import"/>
              <input type="hidden" name="importType" value="%1$s"/>
              <input type="submit" name="submit" value="Importation" class="btn btn-primary btn-sm"/>
            </div>
          </form>
        </div>
      </div>
