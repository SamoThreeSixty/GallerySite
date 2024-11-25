// Here I will add listeners for the various forms making sure
// the request is handled properly through AJAX JQuery

// Used to make sure that the everything on the page is loaded
$(document).ready(function () {
  // Initially load all of the images in the list.
  loadStaffImageList();

  $("#UploadImageForm").on("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    // POST
    // Insert staff image
    $.ajax({
      url: "./libs/php/insertStaffImage.php", // Backend PHP file
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        console.log(response);
        loadStaffImageList();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(errorThrown);
      },
    });
  });

  $(document).on("click", "#Delete", function (e) {
    e.preventDefault();

    // Get the value Id of the record stored as the value
    const id = $(this).attr("value");

    // Delete the image making sure the value is converted to an int
    deleteStaffImage(parseInt(id));
  });
});

function loadStaffImageList() {
  // Clear the existing list
  $("#StaffImagesList").empty();

  // Load the list from the database
  $.ajax({
    url: "./libs/php/getAllStaffImages.php", // Backend PHP file
    type: "GET",
    success: function (response) {
      console.log(response);
      response.data.forEach((staff) => {
        $("#StaffImagesList").append(
          `<li class="list-group-item">
            ${staff.Staff_Name}
            <span class="pull-right">
              <a value="${staff.Id}" id="MoveUp"><img alt="Move up" class="icon" src="img/up.svg"></a>
              <a value="${staff.Id}" id="MoveDown"><img alt="Close" class="icon" src="img/down.svg"></a>
              <a value="${staff.Id}" id="Update"><img alt="Edit staff member" class="icon" src="img/edit.svg"></a>
              <a value="${staff.Id}" id="Delete"><img alt="Remove staff member" class="icon" src="img/close.svg"></a>
            </span>
          </li>`
        );
      });
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error(errorThrown);
    },
  });
}

function deleteStaffImage(id) {
  // Check that it is an int
  if (typeof id != "number") {
    throw Error("Id should be a number");
  }

  // Check that it is above 0
  if (id <= 0) {
    throw Error("This is not a valid id");
  }

  // POST
  // Set the flag of a record to deleted
  $.ajax({
    url: "./libs/php/deleteStaffImage.php",
    type: "POST",
    data: {
      Id: id,
    },
    success: function (e) {
      // Refresh the list when the record has been deleted
      // to show that it is removed
      loadStaffImageList();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error(errorThrown);
    },
  });
}
