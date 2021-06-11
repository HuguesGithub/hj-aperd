<div class="wrap">
  <h1 class="wp-heading-inline">Enseignants</h1>
  <hr class="wp-header-end">

  <div class="row">
    <div class="col-8">
      <div class="card-body">
        <form action="#" method="post" id="post-filters" class="md-form">
          <div class="tablenav top mb-3" style="height: inherit;">
            <div class="actions mb-3">
              <select name="action" id="bulk-action-selector-top" class="form-control md-select form-control-sm">
                <option value="-1">Actions groupées</option>
                <option value="trash">Supprimer</option>
                <option value="export">Exporter</option>
              </select>
              <input type="hidden" name="postAction" value="Bulk"/>
              <input type="submit" name="submit" class="btn btn-primary btn-sm mr-5 float-left" value="Appliquer">

              %2$s
              <input type="submit" name="filter_action" class="btn btn-info btn-sm" value="Filtrer">
            </div>
          </div>

          <table class="table table-striped table-bordered table-hover table-sm" aria-describedby="Liste des Enseignants avec leurs Matières et un éventuel rôle de Professeur Principal.">
            <thead>
              <tr>
                <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox"></td>
                <th scope="col" id="nomEnseignant" class="manage-column">Nom Enseignant</th>
                <th scope="col" id="matiereId" class="manage-column">Matière</th>
                <th scope="col" id="division" class="manage-column">Division</th>
                <th scope="col" id="anneeScolaire" class="manage-column">Année Scolaire</th>
                <th scope="col" id="actionEnseignant" class="manage-column column-actions">Actions</th>
              </tr>
            </thead>
            <tbody id="the-list">%1$s</tbody>
            <tfoot>
              <tr>
                <td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox"></td>
                <th scope="col" class="manage-column">Nom Enseignant</th>
                <th scope="col" class="manage-column">Matière</th>
                <th scope="col" class="manage-column">Division</th>
                <th scope="col" class="manage-column">Année Scolaire</th>
                <th scope="col" class="manage-column">Actions</th>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>

    <div class="col-4">
      <div>%3$s</div>
      %4$s
      %5$s
    </div>
  </div>

</div>
<script>
  var defaultTab = '';
</script>
