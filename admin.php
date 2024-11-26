<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>A&amp;P Gallery</title>
  <link href="https://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-lg-12"> <img alt="A&amp;P" class="logo" src="media/logo/ap.svg">
        <label>Add a new image</label>
        <div class="well">
          <form id="UploadImageForm">
            <div class="form-group">
              <label>Upload your image</label>
              <div class="input-group">
                <span class="input-group-btn">
                  <span class="btn btn-default btn-file">Browse…<input id="ImageFilename" name="Image_Filename"
                      type="file" required></span>
                </span>
                <input class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label for="StaffName">Staff name:</label>
              <input class="form-control" id="StaffName" name="Staff_Name" type="text" required>
            </div>
            <button class="btn btn-default" type="submit">Upload</button>
          </form>
        </div>
      </div>
      <div class="col-lg-12">
        <label>Edit/Re-order existing staff</label>
        <ul id="StaffImagesList" class="list-group"></ul>
      </div>
      <div class="col-lg-12"> <a href="gallery.php" class="btn btn-default pull-right">View Gallery</a> </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
  <script src="./libs/js/script.js" type="module"></script>
</body>

</html>