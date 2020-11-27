<div class="wrap">
  <h1 class="wp-heading-inline">Comptes-Rendus</h1>
  <hr class="wp-header-end">

  <div class="row">
    <div class="col-8">
      <div class="card-body">
        <form action="#" method="post" id="post-filters">
          <div class="tablenav top" style="height: inherit;">
            <div class="actions" style="margin-bottom: 5px;">
              %5$s
              <input type="hidden" name="type" value="ClasseScolaire">
              <input type="submit" name="filter_action" class="btn btn-info" value="Filtrer">
            </div>
          </div>
          <table class="table table-striped table-bordered table-hover table-sm" aria-describeby="Liste des compte-rendus">
            <thead>
              <tr>
                <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox"></td>
                <th scope="col" id="labelMatiere" class="manage-column">Libellé Matière</th>
              </tr>
            </thead>
            <tbody id="the-list">%4$s</tbody>
            <tfoot>
              <tr>
                <td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox"></td>
                <th scope="col" class="manage-column">Libellé Matière</th>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>

    <div class="col-4">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Génération</h4>
          <form action="#" method="post" id="post-add">
            %3$s
            <div class="form-group">
              <label for="anneeScolaireId">Année Scolaire</label>%1$s
            </div>
            <div class="form-group">
              <label for="trimestre">Trimestre</label>%2$s
            </div>
            <br>
            <div class="form-row">
              <input type="hidden" name="postAction" value="generateCdc"/>
              <input type="hidden" name="type" value="generateCdc"/>
              <input type="submit" value="Générer" class="btn btn-info"/>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  var defaultTab = '';
</script>
