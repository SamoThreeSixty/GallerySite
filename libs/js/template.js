// Here i will contain the template functions that can be used to generate the html on the page

export function staffImagesTemplate(record, edit = false) {
  return `<li class="list-group-item d-flex justify-content-between">
    <input id="${record.Id}" value="${record.Staff_Name}" readonly></input>
  
    <span class="pull-right">
    <a class="MoveUp" id="MoveUp_${
      record.Id
    }"><img alt="Move up" class="icon" src="media/icons/up.svg"></a>
    <a class="MoveDown" id="MoveDown_ ${
      record.Id
    }"><img alt="Close" class="icon" src="media/icons/down.svg"></a>
    <span id="Controls_${record.Id}">
        ${staffImagesControls(record.Id, (edit = false))}
    </span>
    </span>
  </li>`;
}

export function staffImagesControls(recordId, edit = false) {
  let controls;

  if (edit) {
    controls = `    
        <a class="Confirm" id="Confirm_${recordId}"><img alt="Confirm changes to staff record" class="icon" src="media/icons/check.svg"></a>
        <a class="Cancel" id="Cancel_${recordId}"><img alt="Cancel changes to staff record" class="icon" src="media/icons/close.svg"></a>`;
  } else {
    controls = `    
        <a class="Update" id="Update_${recordId}"><img alt="Edit staff member" class="icon" src="media/icons/edit.svg"></a>
        <a class="Delete" id="Delete_${recordId}"><img alt="Remove staff member" class="icon" src="media/icons/close.svg"></a>`;
  }

  return controls;
}

export function staffGalleryImage(record) {
  return `
    <div class="col-md-3 col-sm-4 col-xs-6">
      <div class="profile"> <img alt="Profile image of ${record.Staff_Name}" class="img-responsive" src="${record.Image_FileName}">
        <h5>${record.Staff_Name}</h5>
      </div>
    </div>          
  `;
}
