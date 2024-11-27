import {
  staffImagesTemplate,
  staffGalleryImage,
  staffImagesControls,
} from "./template.js";

// Here I will add listeners for the various forms making sure
// the request is handled properly through AJAX JQuery

// Used to make sure that the everything on the page is loaded
$(document).ready(function () {
  // Initial load based on which page you are on.
  // If on Admin
  loadStaffImageList();

  // If on Gallery
  loadGalleryImages();

  $("#UploadImageForm").on("submit", function (e) {
    e.preventDefault();

    // Check to see if a record is already being edited.
    // If it is, the rest of the function will be executed.
    if (isEditMode()) {
      return;
    }

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
        clearSelectedStaffImage();

        // Load the data again so it displays the new record.
        loadStaffImageList();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(errorThrown);
      },
    });
  });

  $(document).on("change", "#ImageFilename", function (e) {
    const input = e.target;

    if (input.files && input.files.length > 0) {
      const fileName = input.files[0].name;

      $(`#SelectedImageFileName`).val(fileName);
      $(`#SelectedImageFileName`).text(fileName);
    } else {
      console.log("No file selected.");
    }
  });

  $(document).on("click", "#ClearUploadInputs", function (e) {
    e.preventDefault();

    clearSelectedStaffImage();
  });

  $(document).on("click", ".Delete", function (e) {
    e.preventDefault();

    // Check to see if a record is already being edited.
    // If it is, the rest of the function will be executed.
    if (isEditMode()) {
      return;
    }

    // Get the value Id of the record stored as the value
    const id = $(this).attr("id").split("_")[1];

    // Delete the image making sure the value is converted to an int
    deleteStaffImage(parseInt(id));
  });

  $(document).on("click", ".MoveUp", function (e) {
    e.preventDefault();

    // Check to see if a record is already being edited.
    // If it is, the rest of the function will be executed.
    if (isEditMode()) {
      return;
    }

    const id = getStaffId(this);

    moveStaffImage("MoveUp", id);
  });

  $(document).on("click", ".MoveDown", function (e) {
    e.preventDefault();

    // Check to see if a record is already being edited.
    // If it is, the rest of the function will be executed.
    if (isEditMode()) {
      return;
    }

    const id = getStaffId(this);

    moveStaffImage("MoveDown", id);
  });

  $(document).on("click", ".Update", function (e) {
    // Check to see if a record is already being edited.
    // If it is, the rest of the function will be executed.
    if (isEditMode()) {
      return;
    } else {
      setEditMode();
    }

    // Get the Id of the record
    const id = getStaffId(this);

    // Replace the record with the controls
    $(`#Controls_${id}`).html(staffImagesControls(id, true));

    // Save the current value to be returned if cancelled
    $(`#${id}`).data("original", $(`#${id}`).attr("value"));

    // Change to edit on input
    $(`#${id}`).removeAttr("readonly");
  });

  $(document).on("click", ".Confirm", function (e) {
    // Get the Id of the record
    const id = getStaffId(this);

    // Get the new name
    const newName = $(`#${id}`).val();

    editStaffName(newName, id);
  });

  $(document).on("click", ".Cancel", function (e) {
    // Get the Id of the record
    const id = getStaffId(this);

    // Replace the record with the controls
    $(`#Controls_${id}`).html(staffImagesControls(id, false));

    // Return the original data
    removeEditMode();
    const originalValue = $(`#${id}`).data("original");
    $(`#${id}`).val(originalValue);
    $(`#${id}`).text(originalValue);

    // Make it readonly again
    $(`#${id}`).attr("readonly", true);
  });
});

function moveStaffImage(action, Id) {
  const formData = new FormData();

  formData.append("action", action);
  formData.append("Id", Id);

  $.ajax({
    url: "./libs/php/moveStaffImage.php", // Backend PHP file
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      loadStaffImageList();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error(errorThrown);
    },
  });
}

function clearSelectedStaffImage() {
  // Reset the display to blank
  $(`#SelectedImageFileName`).val("");
  $(`#SelectedImageFileName`).text("");

  // Remove the image selected
  $("#ImageFilename").val("");

  // Clear the name inout
  $(`#StaffName`).val("");
  $(`#StaffName`).text("");
}

function loadStaffImageList() {
  // Clear the existing list
  $("#StaffImagesList").empty();

  // Load the list from the database
  $.ajax({
    url: "./libs/php/getAllStaffImages.php", // Backend PHP file
    type: "GET",
    success: function (response) {
      response.data.forEach((staff) => {
        $("#StaffImagesList").append(staffImagesTemplate(staff));
      });
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error(errorThrown);
    },
  });
}

function loadGalleryImages() {
  // Clear the existing list
  $("#GalleryImages").empty();

  // Load the list from the database
  $.ajax({
    url: "./libs/php/getAllStaffImages.php", // Backend PHP file
    type: "GET",
    success: function (response) {
      response.data.forEach((staff) => {
        $("#GalleryImages").append(staffGalleryImage(staff));
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

function getStaffId(element) {
  // Functional Id records have the function_Id
  // This will break it down and just return the Id
  const id = parseInt(element.attributes.id.value.split("_")[1]);

  if (typeof id == "number") {
    return id;
  } else {
    throw Error("Value is not a number, it is a " + typeof id);
  }
}

function isEditMode() {
  // Function to be used to prevent editing when a record is being edited.
  const isEditing = $("#StaffImagesList").data("editMode") ? true : false;

  return isEditing;
}

function setEditMode() {
  // Function to be used to set edit mode when you start editing.
  $("#StaffImagesList").data("editMode", true);
}

function removeEditMode() {
  // Function to be used to remove edit mode when you start editing.
  $("#StaffImagesList").data("editMode", false);
}

function editStaffName(newName, id) {
  const formData = new FormData();

  formData.append("newName", newName);
  formData.append("id", id);

  $.ajax({
    url: "./libs/php/updateStaffImage.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      // Remove the edit mode flag
      removeEditMode();

      // Set the field to readonly
      $(`#${id}`).attr("readonly", true);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error(errorThrown);

      // Return the original data
      removeEditMode();
      const originalValue = $(`#${id}`).data("original");
      $(`#${id}`).val(originalValue);
      $(`#${id}`).text(originalValue);
    },
  });
}
