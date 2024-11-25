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
    <div class="col-lg-12"> <img alt="A&amp;P" class="logo" src="img/ap.svg">
      <label>Add a new image</label>
      <div class="well">
        <form id="UploadImageForm">
          <div class="form-group">
            <label>Upload your image</label>
            <div class="input-group">
              <span class="input-group-btn">
                <span class="btn btn-default btn-file">Browse…<input id="ImageFilename" name="Image_Filename" type="file" required></span>
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
      <ul class="list-group">
        <li class="list-group-item">Francesca Hall <span class="pull-right"><a href="/"><img alt="Move up" class="icon" src="img/up.svg"></a><a href="/"><img alt="Move down" class="icon" src="img/down.svg"></a><a href="/"><img alt="Edit staff member" class="icon" src="img/edit.svg"></a><a href="/"><img alt="Remove staff member" class="icon" src="img/close.svg"></a></span></li>
        <li class="list-group-item">Alison Blackledge<span class="pull-right"><a href="/"><img alt="Move up" class="icon" src="img/up.svg"></a><a href="/"><img alt="Move down" class="icon" src="img/down.svg"></a><a href="/"><img alt="Edit staff member" class="icon" src="img/edit.svg"></a><a href="/"><img alt="Remove staff member" class="icon" src="img/close.svg"></a></span></li>
        <li class="list-group-item">Andy Taylor<span class="pull-right"><a href="/"><img alt="Move up" class="icon" src="img/up.svg"></a><a href="/"><img alt="Move down" class="icon" src="img/down.svg"></a><a href="/"><img alt="Edit staff member" class="icon" src="img/edit.svg"></a><a href="/"><img alt="Remove staff member" class="icon" src="img/close.svg"></a></span></li>
        <li class="list-group-item">David Daglish<span class="pull-right"><a href="/"><img alt="Move up" class="icon" src="img/up.svg"></a><a href="/"><img alt="Close" class="icon" src="img/down.svg"></a><a href="/"><img alt="Edit staff member" class="icon" src="img/edit.svg"></a><a href="/"><img alt="Remove staff member" class="icon" src="img/close.svg"></a></span></li>
        <li class="list-group-item">Michael Kane<span class="pull-right"><a href="/"><img alt="Move up" class="icon" src="img/up.svg"></a><a href="/"><img alt="Close" class="icon" src="img/down.svg"></a><a href="/"><img alt="Edit staff member" class="icon" src="img/edit.svg"></a><a href="/"><img alt="Remove staff member" class="icon" src="img/close.svg"></a></span></li>
        <li class="list-group-item">Mike Bailey<span class="pull-right"><a href="/"><img alt="Move up" class="icon" src="img/up.svg"></a><a href="/"><img alt="Close" class="icon" src="img/down.svg"></a><a href="/"><img alt="Edit staff member" class="icon" src="img/edit.svg"></a><a href="/"><img alt="Remove staff member" class="icon" src="img/close.svg"></a></span></li>
        <li class="list-group-item">Kulbinder Singh<span class="pull-right"><a href="/"><img alt="Move up" class="icon" src="img/up.svg"></a><a href="/"><img alt="Close" class="icon" src="img/down.svg"></a><a href="/"><img alt="Edit staff member" class="icon" src="img/edit.svg"></a><a href="/"><img alt="Remove staff member" class="icon" src="img/close.svg"></a></span></li>
        <li class="list-group-item">John Hyland<span class="pull-right"><a href="/"><img alt="Move up" class="icon" src="img/up.svg"></a><a href="/"><img alt="Close" class="icon" src="img/down.svg"></a><a href="/"><img alt="Edit staff member" class="icon" src="img/edit.svg"></a><a href="/"><img alt="Remove staff member" class="icon" src="img/close.svg"></a></span></li>
      </ul>
    </div>
    <div class="col-lg-12"> <a href="gallery.php" class="btn btn-default pull-right">View Gallery</a> </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> 
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="./libs/js/script.js"></script>
</body>
</html>