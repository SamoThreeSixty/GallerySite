import { staffImagesTemplate, staffGalleryImage } from "./template.js";

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

  $(document).on("click", ".Delete", function (e) {
    e.preventDefault();

    // Get the value Id of the record stored as the value
    id = $(this).attr("id").split("_")[1];

    // Delete the image making sure the value is converted to an int
    deleteStaffImage(parseInt(id));
  });

  $(document).on("click", ".MoveUp", function (e) {
    e.preventDefault();

    id = $(this).attr("value");

    moveStaffImage("MoveUp", id);
  });

  $(document).on("click", ".MoveDown", function (e) {
    e.preventDefault();

    id = $(this).attr("value");

    moveStaffImage("MoveDown", id);
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

function editStaffName() {}
