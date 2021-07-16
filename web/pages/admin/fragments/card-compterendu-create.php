  <div class="card bg-light">
    <form action="#" method="post" id="post-edit">
      <div class="card-header"><strong>Création</strong></div>
      <div class="card-body">
        <div class="row">
          <div class="col-md">
            <div class="form-floating mb-3">
              %1$s
              <label for="trimestre">Trimestre</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md">
            <div class="form-floating mb-3">
              %2$s
              <label for="divisionId">Division</label>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="btn-group btn-group-toggle">
          <input type="submit" name="action" value="Création" class="btn btn-primary btn-lg"/>
          <input type="hidden" name="postAction" value="Bulk"/>
          <a href="%3$s" class="btn btn-outline-dark btn-lg">Annuler</a>
        </div>
      </div>
    </form>
  </div>
