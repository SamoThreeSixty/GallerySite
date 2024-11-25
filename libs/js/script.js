// Here I will add listeners for the various forms making sure
// the request is handled properly through AJAX JQuery

// Used to make sure that the everything on the page is loaded
$(document).ready(function () {
  $("#UploadImageForm").on("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Send the AJAX request
    $.ajax({
      url: "./libs/php/insertStaffImage.php", // Backend PHP file
      type: "POST",
      data: formData,
      processData: false, // Prevent jQuery from processing the data
      contentType: false, // Prevent jQuery from setting contentType
      success: function (response) {
        console.log(response);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(errorThrown);
      },
    });
  });
});
