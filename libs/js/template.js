// Here i will contain the template functions that can be used to generate the html on the page

export function staffImagesTemplate(record) {
  return `<li class="list-group-item d-flex justify-content-between">
    <input id="${record.Id}" value="${record.Staff_Name}" readonly></input>
  
    <span class="pull-right">
    <a class="MoveUp" id="MoveUp_${record.Id}"><img alt="Move up" class="icon" src="media/icons/up.svg"></a>
    <a class="MoveDown" id="MoveDown_ ${record.Id}"><img alt="Close" class="icon" src="media/icons/down.svg"></a>
    <a class="Update" id="Update_${record.Id}"><img alt="Edit staff member" class="icon" src="media/icons/edit.svg"></a>
    <a class="Delete" id="Delete_${record.Id}"><img alt="Remove staff member" class="icon" src="media/icons/close.svg"></a>
    </span>
  </li>`;
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
