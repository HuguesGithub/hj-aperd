<div class="wrap">
  <h1 class="wp-heading-inline">Élèves</h1>
  <hr class="wp-header-end">

  <div class="row" id="tabEleves">
    <div class="col-8">
      <div class="card-body">
        <form action="#" method="post" id="post-filters" class="md-form">
          <div class="tablenav top mb-3" style="display: flex; flex-wrap: wrap;">
            <div class="actions col-6 mb-3">
              <select name="action" id="bulk-action-selector-top" class="form-control md-select form-control-sm">
                <option value="-1">Actions groupées</option>
                <option value="trash">Supprimer</option>
                <option value="export">Exporter</option>
              </select>
              <input type="hidden" name="postAction" value="Bulk"/>
              <input type="submit" name="submit" class="btn btn-primary btn-sm mr-5 float-left" value="Appliquer">
            </div>

            <div class="tablenav-pages col-6" style="text-align: right;">
              %5$s
            </div>

            <div class="actions col-12">
              %6$s
              <input type="submit" name="filter_action" class="btn btn-info btn-sm" value="Filtrer">
            </div>
          </div>

          <table class="table table-striped table-bordered table-hover table-sm" aria-describedby="Liste des élèves">
            <thead>
              <tr>
                <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox"></td>
                <th scope="col" id="nomEleve" class="manage-column">Nom</th>
                <th scope="col" id="prenomEleve" class="manage-column">Prénom</th>
                <th scope="col" id="divisionId" class="manage-column">Division</th>
                <th scope="col" id="delegue" class="manage-column">Délégué</th>
                <th scope="col" id="actionEleve" class="manage-column column-actions">Actions</th>
              </tr>
            </thead>
            <tbody id="the-list">%1$s</tbody>
            <tfoot>
              <tr>
                <td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox"></td>
                <th scope="col" class="manage-column">Nom</th>
                <th scope="col" class="manage-column">Prénom</th>
                <th scope="col" class="manage-column">Division</th>
                <th scope="col" class="manage-column">Délégué</th>
                <th scope="col" class="manage-column column-actions">Actions</th>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>

    <div class="col-4">
      <div>%2$s</div>
      %3$s
      %4$s
    </div>

  </div>
</div>
<script>
  var defaultTab = '';
</script>
