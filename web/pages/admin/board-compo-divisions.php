<div class="wrap">
  <h1 class="wp-heading-inline">Composition Divisions</h1>
  <hr class="wp-header-end">

  <div class="row">
    <div class="col-8">
      <div class="card-body" style="padding-top: 20px;">
        <form action="#" method="post" id="post-filters" class="md-form">
          <div class="row">
              <div class="card bg-light col-md">
                <div class="card-body row">
                  <div class="col-md">
                    <select name="action" id="bulk-action-selector-top" class="form-control md-select form-control-lg" style="display: inline-block;">
                    <option value="-1">Actions groupées</option>
                    <option value="trash">Supprimer</option>
                    <option value="export">Exporter</option>
                  </select>
                  </div>
                  <div class="col-md">
                    <input type="hidden" name="postAction" value="Bulk"/>
                    <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Appliquer">
                  </div>
                </div>
              </div>

              <div class="card bg-light col-md">
                <div class="card-body">
              %5$s
                </div>
              </div>
          </div>
          <div class="row">
              <div class="card bg-light col-md">
                <div class="card-body row">
                  <div class="col-md">
                    <div class="form-floating">
                      %6$s
                    </div>
                  </div>
                  <div class="col-md">
                    <input type="submit" name="filter_action" class="btn btn-info btn-lg" value="Filtrer">
                  </div>
                </div>
              </div>
          </div>

          <table class="table table-striped table-bordered table-hover table-sm" aria-describedby="Liste des élèves">
            <thead>
              <tr>
                <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox"></td>
                <th scope="col" id="labelDivision" class="manage-column">Division</th>
                <th scope="col" id="nomEnseignant" class="manage-column">Enseignant</th>
                <th scope="col" id="labelMatiere" class="manage-column">Matière</th>
                <th scope="col" id="actionEleve" class="manage-column column-actions">Actions</th>
              </tr>
            </thead>
            <tbody id="the-list">%1$s</tbody>
            <tfoot>
              <tr>
                <td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox"></td>
                <th scope="col" class="manage-column">Division</th>
                <th scope="col" class="manage-column">Enseignant</th>
                <th scope="col" class="manage-column">Matière</th>
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
