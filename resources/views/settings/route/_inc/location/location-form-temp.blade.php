<div id="location_form_temp" class="hidden">
  <div>
      <hr class="hrSector">
      <div class="row">
          <input name="location[id][LD]" type="hidden" class="locationInput" id="location_idLD"
                 autocomplete="off">
          <div class="col-md-11">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group  required">
                          <label for="location_name" class="control-label form-control-label">Location name</label>
                          <input class="form-control" placeholder="enter location name" name="location[name][LD]" type="text" id="location_nameLD"
                                 autocomplete="off">
                          <p class="form-control-feedback"></p>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label for="location_notes" class="control-label form-control-label">Location notes</label>
                          <textarea class="form-control" placeholder="enter location related notes here..." rows="3"
                                    name="location[notes][LD]" cols="50" id="location_notesLD" autocomplete="off"></textarea>
                          <p class="form-control-feedback"></p>
                      </div>
                  </div>
              </div>
          </div>
          <div class="col-md-1">
              <button type="Button" id="removeBtn" class="btn btn-sm btn-danger hidden removeBtn" data-id="ID" onclick="removeForm(this)">Remove</button>
          </div>
      </div>
  </div>
</div>