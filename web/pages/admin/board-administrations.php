<div class="wrap">
  <h1 class="wp-heading-inline">Administratifs</h1>
  <hr class="wp-header-end">

  <div class="row">
    <div class="col-8">
      <div class="card-body" style="padding-top: 20px;">
        <form action="#" method="post" id="post-filters" class="md-form">
          <div class="row">
              <div class="card bg-light col-md" style="margin: 0 15px 10px;">
                <div class="card-body">
                  <select name="action" id="bulk-action-selector-top" class="form-control md-select form-control-lg" style="display: inline-block;">
                    <option value="-1">Actions groupées</option>
                    <option value="trash">Supprimer</option>
                    <option value="export">Exporter</option>
                  </select>
                  <input type="hidden" name="postAction" value="Bulk"/>
                  <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Appliquer">
                </div>
              </div>
          </div>

          <table class="table table-striped table-bordered table-hover table-sm" aria-describedby="Liste des Administratifs">
            <thead>
              <tr>
                <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox"></td>
                <th scope="col" id="genre" class="manage-column">Genre</th>
                <th scope="col" id="nomTitulaire" class="manage-column">Nom Titulaire</th>
                <th scope="col" id="labelPoste" class="manage-column">Libellé Poste</th>
                <th scope="col" id="actionAdministration" class="manage-column column-actions">Actions</th>
              </tr>
            </thead>
            <tbody id="the-list">%1$s</tbody>
            <tfoot>
              <tr>
                <td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox"></td>
                <th scope="col" class="manage-column">Genre</th>
                <th scope="col" class="manage-column">Nom Titulaire</th>
                <th scope="col" class="manage-column">Libellé Poste</th>
                <th scope="col" class="manage-column column-actions">Actions</th>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>

    <div class="col-4">
      <div id="alertBlock" style="margin-top: 20px; max-width: 520px;">%2$s</div>
      %3$s
      %4$s
    </div>

  </div>
</div>
<script>
  var defaultTab = '';
</script>
