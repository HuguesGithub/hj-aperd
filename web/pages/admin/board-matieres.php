<div class="wrap">
  <h1 class="wp-heading-inline">Matières</h1>
  <hr class="wp-header-end">

  <div class="row">
    <div class="col-8">
      <div class="card-body">
        <form action="#" method="post" id="post-filters">
          <table class="table table-striped table-bordered table-hover table-sm">
            <thead>
              <tr>
                <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox"></td>
                <th scope="col" id="labelMatiere" class="manage-column">Libellé Matière</th>
              </tr>
            </thead>
            <tbody id="the-list">%1$s</tbody>
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
          <h4 class="card-title">%2$s</h4>
          <form action="#" method="post" id="post-add">
            <div class="form-group">
              <label for="labelMatiere">Libellé Matière</label>
              <input id="labelMatiere" type="text" class="form-control" placeholder="Libellé Matière" value="%3$s" name="labelMatiere">
            </div>
            <br>
            <div class="form-row">
              <input type="hidden" name="id" value="%5$s" class="btn btn-info"/>
              <input type="hidden" name="postAction" value="" class="btn btn-info"/>
              <input type="submit" name="%2$s" value="%2$s" class="btn btn-info"/>
              <a href="%4$s" class="btn btn-outline-default">Annuler</a>
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
