<div class="wrap">
  <h1 class="wp-heading-inline">Composition Classes</h1>
  <hr class="wp-header-end">

  <div class="row">
    <div class="col-8">
      <div class="card-body">
        <form action="#" method="post" id="post-filters" class="md-form">
          <div class="tablenav top mb-3" style="height: inherit;">
            <div class="actions col-6 mb-3" style="float: left;">
              <select name="action" id="bulk-action-selector-top" class="form-control md-select form-control-sm">
                <option value="-1">Actions groupées</option>
                <option value="trash">Supprimer</option>
                <option value="export">Exporter</option>
              </select>
              <input type="hidden" name="postAction" value="Bulk"/>
              <input type="submit" name="submit" class="btn btn-primary btn-sm mr-5 float-left" value="Appliquer">
            </div>

            <div class="tablenav-pages col-6" style="float: right; text-align: right;">
              %15$s
            </div>

            <div class="actions col-12 mb-3" style="float: left;">
              %16$s
              <input type="submit" name="filter_action" class="btn btn-info btn-sm" value="Filtrer">
            </div>

          </div>

          <table class="table table-striped table-bordered table-hover table-sm" aria-describedby="Liste de la composition des classes">
            <thead>
              <tr>
                <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox"></td>
                <th scope="col" id="anneeScolaire" class="manage-column">Année Scolaire</th>
                <th scope="col" id="labelDivision" class="manage-column">Division</th>
                <th scope="col" id="labelMatiere" class="manage-column">Matière</th>
                <th scope="col" id="nomEnseignant" class="manage-column">Enseignant</th>
                <th scope="col" id="actionCompoDiv" class="manage-column column-actions">Actions</th>
              </tr>
            </thead>
            <tbody id="the-list">%1$s</tbody>
            <tfoot>
              <tr>
                <td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox"></td>
                <th scope="col" class="manage-column">Année Scolaire</th>
                <th scope="col" class="manage-column">Division</th>
                <th scope="col" class="manage-column">Matière</th>
                <th scope="col" class="manage-column">Enseignant</th>
                <th scope="col" class="manage-column">Actions</th>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>

    <div class="col-4">
      <div>%17$s</div>
      <div class="card bg-light%9$s">
        <form action="#" method="post" id="post-add">
          <div class="card-header"><strong>%2$s</strong></div>
          <div class="card-body">
            <div class="form-group">
              <label for="anneeScolaireId">Année Scolaire</label>%3$s
            </div>
            <div class="form-group">
              <label for="divisionId">Division</label>%4$s
            </div>
            <div class="form-group">
              <label for="matiereId">Matière</label>%5$s
            </div>
            <div class="form-group">
              <label for="enseignantId">Enseignant</label>%6$s
            </div>
          </div>
          <div class="card-footer">
            <div class="btn-group btn-group-toggle">
              <input type="submit" name="postAction" value="%2$s" class="btn btn-primary btn-sm"/>
              <input type="hidden" name="id" value="%7$s"/>
              <a href="%8$s" class="btn btn-outline-dark btn-sm">Annuler</a>
            </div>
          </div>
        </form>
      </div>

      <div class="card border-danger%10$s">
        <form action="#" method="post" id="post-del">
          <div class="card-header bg-danger text-white"><strong>Suppression</strong></div>
          <div class="card-body text-danger">
            <div class="form-group%11$s">
              Voulez-vous vraiment <strong>supprimer</strong> la Composition <strong>%14$s</strong> ?
            </div>
            <div class="form-group%12$s">
              Voulez-vous vraiment <strong>supprimer</strong> les Compositions <strong>%13$s</strong> ?
            </div>
          </div>
          <div class="card-footer">
            <div class="btn-group btn-group-toggle">
              <input type="submit" name="submit" value="Oui" class="btn btn-danger btn-sm"/>
              <input type="hidden" name="id" value="%14$s"/>
              <input type="hidden" name="postAction" value="Suppression"/>
              <a href="%8$s" class="btn btn-outline-dark btn-sm">Non</a>
            </div>
          </form>
        </div>
      </div>

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
              <input type="hidden" name="postAction" value="Import"/>
              <input type="hidden" name="importType" value="compodivision"/>
              <input type="submit" name="submit" value="Importation" class="btn btn-primary btn-sm"/>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  var defaultTab = '';
</script>
