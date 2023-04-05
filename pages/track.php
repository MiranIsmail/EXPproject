<!DOCTYPE html>
<html lang="en">

<head>
  <title>Track - Rasts</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/x-icon" href="../images/logo_color.png">
  <!--Tre librarys dont remove, Bootstrap 5-->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../styles/stylesheet.css">
  <link rel="stylesheet" href="../styles/login_and_signup.css">
  <link rel="stylesheet" href="../styles/google_maps_api.css">
  <script src="https://kit.fontawesome.com/dbe6ff92a1.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="../scripts/js_scripts.js"></script>
</head>


<body>
  <?php include '../assets/navbar.php'; ?>
  <div class="image_div">
    <img class="w-100 op30" src="../images/indeximage_thinner.png" id="image_run">
  </div>
  <div class="section content_container">
    <h1>Create your own track</h1>
    <div class="form-group">
      <div class="form-group form_group_style mx-auto container">
        <p>Here you can create tracks that can then be accesed during events</p>
        <p>Make sure that all your checkpoints are functional and that their ID is visable</p>
        <label for="InputTrackName" class="clear_text">Track name</label>
        <input type="text" class="form-control" id="InputTrackName" placeholder="My Track">
      </div>
      <!-- Include the Bootstrap 5 CSS file -->
      <!-- Create a table with Bootstrap 5 classes -->
      <div class="form-group form_group_style mx-auto">
        <p>Start by adding the first section!</p>
        <div class="container opacity_background" id="track_input">

          <div class="row" id="0">
            <div class="col-sm-2">
              <label for="numberInput" id="numberInput" class="form-label fw-bold">ID</label>
              <input type="number" class="form-control" name="ID" id="CheckID" min="100" max="200" placeholder="Ex. 101" required>
            </div>

            <div class="col-sm-2">
              <label for="numberInput" id="dist" class="form-label fw-bold">Distance</label>
              <input type="text" class="form-control" placeholder="Ex. 15" name="distance">
            </div>
            <div class="col btn-group-vertical">
              <!-- Button to Open the Modal -->
              <label for="button" id="pin_button" class="form-label fw-bold">Location</label>
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal"><i class="fa-solid fa-map-location-dot"></i>

              </button>
            </div>

            <!-- The Modal -->
            <div class="modal" id="myModal">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header text-center">
                    <h4 class="modal-title w-100">Map</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <div id="map"></div>
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" onclick="deleteMarkers()">Delete</button>
                    <button type="button" class="btn btn-success" disabled id="save_btn" onclick="send_coords()" data-bs-toggle="modal">Save</button>
                  </div>

                </div>
              </div>
            </div>
            <div class="col">
              <label for="dropdown" id="terrain_dropdown" class="form-label fw-bold">Terrain</label>
              <div class="dropdown" name="terrain">

                <button class="btn btn-secondary dropdown-toggle" type="button" name="Terrain" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa-solid fa-person-running"></i>
                  Terrain
                </button>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButtonTerrain1">
                  <li><a class="dropdown-item" onclick='select("Water", event)'>Water</a></li>
                  <li><a class="dropdown-item" onclick='select("Land", event)'>Land</a></li>
                  <li><a class="dropdown-item" onclick='select("Mixed", event)'>Mixed</a></li>
                </ul>
              </div>
            </div>
            <div class="col btn-group-vertical">
              <label for="delete_button" class="form-label fw-bold">Option</label>
              <button class="btn btn-danger" onclick="deleteRow(this.parentNode.parentNode)" name="delete_button"><i class="fa-solid fa-trash"></i></button>
            </div>
          </div>
          <div class="">
            <div class="col mb-3">
              <button id="add_button" class="btn btn-secondary" onclick="addRow(event)"><i class="fa-regular fa-plus"></i></button>
            </div>
            <div class="col mb-3">
              <button type="submit" button id="submit_button" class="btn btn-primary" role="button" onclick='submit()'>Submit</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>

  <?php include '../assets/footer.php'; ?>
</body>

<script>
  // Create template row 
  const template_row = document.getElementById("0")
  const info = template_row.innerHTML

  function select(option, event) {
    // Get the button element and the dropdown menu element
    var row = event.target.closest('div')
    var button = row.querySelector('button[name="Terrain"]');
    var dropdown = document.querySelector('.dropdown-menu');
    
    // Set the button text to the selected option
    button.textContent = option;

    // Change the button color based on the selected option
    switch (option) {
      case 'Water':
        button.style.backgroundColor = 'blue';
        break;
      case 'Land':
        button.style.backgroundColor = 'green';
        break;
      case 'Mixed':
        button.style.backgroundColor = 'turquoise';
        break;
      default:
        button.style.backgroundColor = '';
        break;
    }

    // Close the dropdown menu
    dropdown.classList.remove('show');
  }

  var i = 0
  function addRow(event) {
    i = i + 1 
    // Get the existing grid container
    const gridContainer = document.querySelector(".grid-container");
    // Create a new row element and add the HTML string you provided
    const newRow = document.createElement("div");
    newRow.classList.add("row");
    newRow.id = i
    newRow.innerHTML = info
    // Add row to grid
    var myGrid = document.getElementById("track_input");
    myGrid.appendChild(newRow);
  }

  function deleteRow(row) {
    const index = row.parentNode.parentNode.rowIndex;
    document.getElementById("track_input").deleteRow(index);
  }


  function submit() {
    const rows = document.querySelectorAll('.row');

    const trackInput = rows.querySelector('input[id=InputTrackName]')
  // Loop through each row
    rows.forEach(row => {
      // Get the input fields in the row
      const idInput = row.querySelector('input[id=CheckID]');
      const distanceInput = row.querySelector('input[name=distance]');
      const terrainDropdown = row.querySelector('button[name=Terrain]');
      
      // Get the values of the input fields
      const id = idInput.value;
      const distance = distanceInput.value;
      const terrain = terrainDropdown.textContent;
      
      // Do something with the data
      console.log(`ID: ${id}, Distance: ${distance}, Terrain: ${terrain}`);
    });
  }


  let map;
  let markers_list = [];
  const btn = document.getElementById('save_btn')

  function init_map() {
    const bth_coords = {
      lat: 56.179475,
      lng: 15.595062
    };
    
    map = new google.maps.Map(document.getElementById("map"), {
      zoom: 14,
      center: bth_coords,
      mapTypeId: "terrain",
    });
    
    // This event listener will call add_marker() when the map is clicked.
    map.addListener("click", (event) => {
      console.log("hej")
      var check_point_id = "101"
      setMapOnAll(null)
      add_marker(event.latLng, check_point_id);
    });
  }

  // Adds a marker to the map and push to the array.
  function add_marker(position, check_point_id) {
    const marker = new google.maps.Marker({
      position,
      map,
      label: check_point_id
    });

    markers_list.push(marker);
    btn.disabled = false;

  }

  // Sets the map on all markers in the array.
  function setMapOnAll(map) {
    for (let i = 0; i < markers_list.length; i++) {
      markers_list[i].setMap(map);
    }
  }

  // Deletes all markers in the array by removing references to them.
  function deleteMarkers() {
    setMapOnAll(null);
    markers_list = [];
    btn.disabled = true;
  }
  //figure out how to confirm
  function send_coords() { //needs fixing

    for (let index = 0; index < markers_list.length; index++) {
      let object_string = JSON.stringify(markers_list[index])
      //data base call
    }
  
  function open_map(event) {
    window.init_map = init_map;
    console.log(check_point_id)
  }
  }
</script>
<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkY5KKVjLNfTPCAX17XbClpOpfTQd0cFM&callback=init_map">
</script>

</html>