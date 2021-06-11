<div class="wrap">
  <h1 class="wp-heading-inline">Comptes-Rendus</h1>
  <hr class="wp-header-end">

  <div class="row">
    <div class="col-8">
      <div class="card-body">
        <form action="#" method="post" id="post-filters">
          <div class="tablenav top" style="height: inherit;">
            <div class="actions col-6" style="margin-bottom: 5px; float:left;">
              <select name="action" id="bulk-action-selector-top" class="form-control md-select">
                <option value="-1">Actions groupées</option>
                <option value="trash">Déplacer dans la corbeille</option>
                <option value="definitive">Passer à Définitif</option>
                <option value="published">Passer à Publié</option>
              </select>
              <input type="submit" name="postAction" class="btn btn-info" value="Appliquer">
            </div>

            <div class="tablenav-pages col-6" style="height: 34px; text-align: right;">
              <span class="displaying-num">191 éléments</span>
              <span class="pagination-links">
                <span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                <span class="paging-input">
                  <input class="current-page" id="current-page-selector" type="text" name="paged" value="1" size="1" aria-describedby="table-paging">
                  <span class="tablenav-paging-text"> sur <span class="total-pages">20</span></span>
                </span>
                <a class="next-page button" href="http://zombicide.jhugues.fr/wp-admin/admin.php?onglet=survivor&amp;orderby=name&amp;order=ASC&amp;cur_page=2&amp;page=hj-zombicide/admin_manage.php"><span aria-hidden="true">›</span></a>
                <a class="next-page button" href="http://zombicide.jhugues.fr/wp-admin/admin.php?onglet=survivor&amp;orderby=name&amp;order=ASC&amp;cur_page=20&amp;page=hj-zombicide/admin_manage.php"><span aria-hidden="true">»</span></a>
              </span>
            </div>

            <div class="actions col-12" style="margin-bottom: 5px; clear: both;">
              %5$s
              <input type="submit" name="filter_action" class="btn btn-info" value="Filtrer">
            </div>
          </div>
          <table class="table table-striped table-bordered table-hover table-sm" aria-describedby="Liste des compte-rendus">
            <thead>
              <tr>
                <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox"></td>
                <th scope="col" id="anneeScolaireId" class="manage-column">Année Scolaire</th>
                <th scope="col" id="trimestre" class="manage-column">Trimestre</th>
                <th scope="col" id="divisionId" class="manage-column">Division</th>
                <th scope="col" id="status" class="manage-column">Statut</th>
                <th scope="col" id="dateConseil" class="manage-column">Date</th>
                <th scope="col" id="administrationId" class="manage-column">Président</th>
              </tr>
            </thead>
            <tbody id="the-list">%4$s</tbody>
            <tfoot>
              <tr>
                <td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox"></td>
                <th scope="col" class="manage-column">Année Scolaire</th>
                <th scope="col" class="manage-column">Trimestre</th>
                <th scope="col" class="manage-column">Division</th>
                <th scope="col" class="manage-column">Statut</th>
                <th scope="col" class="manage-column">Date</th>
                <th scope="col" class="manage-column">Président</th>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>

    <div class="col-4">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">%6$s</h4>
          <form action="#" method="post" id="post-add">
            <div class="form-group">
              <label for="nomEleve">Année Scolaire</label> : %7$s
            </div>
            <div class="form-group">
              <label for="prenomEleve">Trimestre</label> : %8$s
            </div>
            <div class="form-group">
              <label for="divisionId">Division</label> : %9$s
            </div>
            <div class="form-group">
              <label for="divisionId">crKey</label> : %10$s
            </div>
            <div class="form-group">
              <label for="dateConseil">Date</label>
              <input id="dateConseil" type="text" class="form-control" placeholder="JJ/MM/YYYY" value="%13$s" name="dateConseil">
            </div>
            <div class="form-group">
              <label for="adminstrationId">Président</label>%14$s
            </div>
            <br>
            <div class="form-row">
              <input type="hidden" name="id" value="%11$s"/>
              <input type="submit" name="postAction" value="%6$s" class="btn btn-info"/>
              <a href="%12$s" class="btn btn-outline-default">Annuler</a>
            </div>
          </form>
        </div>
      </div>
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
