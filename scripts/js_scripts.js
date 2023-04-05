var BASE_ULR = "https://rasts.se/api/"

window.onload = function () {
  update_navbar()
};

const get_cookie = (name) => (
  document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)')?.pop() || ''
)

function blobToBase64(blob) {
  return new Promise((resolve, _) => {
    const reader = new FileReader();
    reader.onloadend = () => resolve(reader.result);
    reader.readAsDataURL(blob);
  });
}


function calculate_age(date) {
  if (date != null) {

    var today = new Date();
    var birthDate = new Date(date);
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
      age--;
    }
    return age;
  }

  return "missing"

}

function image_to_blob(inputElement) {
  const file = inputElement.files[0];
  if (!file) {
    return Promise.reject(new Error('No file selected'));
  }
  const reader = new FileReader();
  reader.readAsArrayBuffer(file);
  return new Promise((resolve, reject) => {
    reader.onload = () => {
      const blob = new Blob([reader.result], { type: file.type });
      resolve(blob);
    };
    reader.onerror = () => {
      reject(new Error('Error reading file'));
    };
  });
}

function createAccount() {
  let xemail = document.getElementById('email').value;
  let xfirst_name = document.getElementById('fname').value;
  let xlast_name = document.getElementById('lname').value;
  let xpassword = document.getElementById('pword').value;

  fetch(BASE_ULR + "Account", {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ "email": xemail, "first_name": xfirst_name, "last_name": xlast_name, "password": xpassword })
  })

    .then(response => {
      var test = response.json()
      console.log(test)
    })
    .then((data) => { console.log(data) })
    .catch(error => console.error(error))
  location.href = '../pages/confirmation_account.php'
}


function fill_org_form() {
  // Get all required input fields
  const requiredFields = document.querySelectorAll('input[required]')

  // Check if all required fields are filled in and valid
  const allFieldsValid = Array.from(requiredFields).every(field => field.checkValidity());

  if (allFieldsValid) {
    // get references to form elements
    const orgNameInput = document.getElementById('org_name');
    const orgCountryInput = document.getElementById('org_country');
    const orgEmailInput = document.getElementById('org_email');
    const userEmailInput = document.getElementById('user_email');

    // extract values from form elements
    const orgName = orgNameInput.value;
    const orgCountry = orgCountryInput.value;
    const orgEmail = orgEmailInput.value;
    const userEmail = userEmailInput.value;

    console.log(`Organisation name: ${orgName}`);
    console.log(`Country: ${orgCountry}`);
    console.log(`Email Address for organisation: ${orgEmail}`);
    console.log(`Private Email Address: ${userEmail}`);
  }
}

async function log_in() {
  let femail = document.getElementById('fetchEmail').value;
  let fpword = document.getElementById('fetchPword').value;
  const response = await fetch(BASE_ULR + "Token", {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ "email": femail, "password": fpword })
  })
  const data = await response.json()
  document.cookie = `auth_token=${await data["auth_token"]}`;
  location.href = '../pages/profile.php'
}

async function log_out() {

  const response = await fetch(BASE_ULR + "Token", {
    method: 'PATCH',
    headers: { 'Authorization': get_cookie('auth_token') }
  })
  const data = await response.json()
  console.log(await data)
  location.href = '../pages/'
}

function load_image(indata) {
  var img = document.createElement("img")
  img.setAttribute("id", "profile_image")
  img.setAttribute("class", "img-fluid d-block")
  img.src = indata
  var src = document.getElementById("profile_box")
  src.appendChild(img);
}

async function get_user_info() {

  const response = await fetch(BASE_ULR + "Account", {
    method: 'GET',
    headers: { 'Authorization': get_cookie('auth_token') }
  })
  const data = await response.json()

  //Just getting the source from the span. It was messy in JS.

  document.getElementById("profileName").innerHTML = await data["first_name"] + " " + await data["last_name"]
  document.getElementById("profile_age").innerHTML = await calculate_age(await data["birthdate"])
  document.getElementById("profile_length").innerHTML = await data["height"]
  document.getElementById("profile_weight").innerHTML = await data["weight"]
  load_image(data["pimage"])
}


async function edit_user_info() {
  var parameters = {}
  parameters["first_name"] = document.getElementById('send_f_name').value
  parameters["last_name"] = document.getElementById('send_l_name').value
  parameters["birthdate"] = document.getElementById('send_bday').value
  parameters["height"] = document.getElementById('send_height').value
  parameters["weight"] = document.getElementById('send_weight').value

  if (document.getElementById("send_image").files.length != 0) {
    var blob = await image_to_blob(document.getElementById('send_image'))
    parameters["pimage"] = await blobToBase64(blob)
  }

  for (const [key, value] of Object.entries(parameters)) {
    console.log(key, value);
    if (!value) {
      delete parameters[key];
    }
  }
  console.log(parameters);

  const response = await fetch(BASE_ULR + "Account", {
    method: 'PATCH',
    headers: { 'Content-Type': 'application/json', 'Authorization': get_cookie('auth_token') },
    body: JSON.stringify(parameters)
  })

  location.href = '../pages/profile.php'
}

async function generate_table() {
  /**/
  res = await fetch(BASE_ULR + "event?key=host_email&search_text=")

  text = await res.json()
  var dataString = String(text[1].replace(/[(')]/g, '').replace(/datetime.date/g, '')).split(',')
  console.log(dataString)
  let amount_event = dataString.length / 9


  const tbl = document.createElement("table");
  tbl.setAttribute("id", "profile_table")
  const tbl_head = document.createElement("thead");
  const row = document.createElement("tr");
  const cellText1 = document.createTextNode(`Tävling`);
  const cellText2 = document.createTextNode(`Organisatör`);
  const cellText3 = document.createTextNode(`Sport`);
  const cellText4 = document.createTextNode(`StartDatum`);
  const cellText5 = document.createTextNode(`SlutDatum`);

  const tblBody = document.createElement("tbody");

  // creating all cells
  for (let i = 0; i < amount_event; i++) {
    var startdate = dataString[i * 9 + 3].trim() + "-" + dataString[i * 9 + 4].trim() + "-" + dataString[i * 9 + 5].trim()
    var enddate = dataString[i * 9 + 6].trim() + "-" + dataString[i * 9 + 7].trim() + "-" + dataString[i * 9 + 8].trim()

    // creates a table row
    const row = document.createElement("tr");

    for (let j = 0; j < 5; j++) {
      // Create a <td> element and a text node, make the text
      // node the contents of the <td>, and put the <td> at
      // the end of the table row
      const cell = document.createElement("td");
      let cellText = ''
      if (j < 3) {
        cellText = document.createTextNode(dataString[i * 9 + j]);
      }
      else if (j == 3) {
        cellText = document.createTextNode(startdate);
      }
      else {
        cellText = document.createTextNode(enddate);

      }

      cell.appendChild(cellText);
      row.appendChild(cell);
    }

    // add the row to the end of the table body
    tblBody.appendChild(row);
  }

  // put the <tbody> in the <table>
  tbl.appendChild(tbl_head)
  tbl.appendChild(tblBody);
  // appends <table> into <body>
  document.getElementById("event").appendChild(tbl)

  // sets the border attribute of tbl to '2'
  tbl.setAttribute("border", "4");
  tbl.setAttribute("class", "mx-auto w-75")
}


function search_event() {
  let input = document.getElementById('searchQueryInput').value
  input = input.toLowerCase();
  let x = document.getElementsByClassName('card-title');
  let xcard = document.getElementsByClassName('eventCards');

  for (i = 0; i < x.length; i++) {
    if (!xcard[i].innerHTML.toLowerCase().includes(input)) {
      xcard[i].style.display = "none";
    }
    else {
      xcard[i].style.display = "list-item";
    }
  }
}


function include_HTML() {
  var z, i, element, file, xhttp;
  /* Loop through a collection of all HTML elements: */
  z = document.getElementsByTagName("div");


  for (i = 0; i < z.length; i++) {
    element = z[i];
    /*search for elements with a certain atrribute:*/
    file = element.getAttribute("include-html");

    if (file) {
      /* Make an HTTP request using the attribute value as the file name: */
      xhttp = new XMLHttpRequest();
      console.log(file)
      xhttp.onreadystatechange = function () {
        if (this.readyState == 4) {
          if (this.status == 200) { element.innerHTML = this.responseText; }
          if (this.status == 404) { element.innerHTML = "Page not found."; }
          /* Remove the attribute, and call this function once more: */
          element.removeAttribute("include-html");
          include_HTML();
        }
      }
      xhttp.open("GET", file, true);
      xhttp.send();
      /* Exit the function: */
      return;
    }
  }
}


/*CREATE EVENT */

// function create_event() {
//   let startDate = document.getElementById('startDate')
//   let endDate = document.getElementById('endDate')
//   let b_day = document.getElementById('b_day')

//   startDate.addEventListener('change', (e) => {
//     let startDateVal = e.target.value
//     document.getElementById('startDateSelected').innerText = startDateVal
//   })

//   endDate.addEventListener('change', (e) => {
//     let endDateVal = e.target.value
//     document.getElementById('endDateSelected').innerText = endDateVal
//   })

//   b_day.addEventListener('change', (e) => {
//     let b_day_val = e.target.value
//     document.getElementById('b_day_selected').innerText = b_day_val
//   })
// }

async function update_navbar() {


  status_code = 401
  if (get_cookie("auth_token")) {

    const response = await fetch(BASE_ULR + "Token", {
      method: 'GET',
      headers: { 'Content-Type': 'application/json', 'Authorization': get_cookie('auth_token') }
    })
    status_code = await response.status

  }


  if (status_code == 200) {
    document.getElementById("navbar-log-out").classList.remove("d-none")
    document.getElementById("navbar-profile").classList.remove("d-none")

  }
  else {
    document.getElementById("navbar-log-in").classList.remove("d-none")
  }
}

/* EVENT PAGE*/
async function get_event_info(event_id) {
  const response = await fetch(BASE_ULR + "Event/" + event_id, {
    method: 'GET',
  })
  const data = await response.json()
  console.log(data)
  //Just getting the source from the span. It was messy in JS.
  document.getElementById("event_name").innerHTML = await data["event_name"]
  document.getElementById("event_sport").innerHTML = await data["sport"]
  document.getElementById("event_sdate").innerHTML = await data["startdate"]
  document.getElementById("event_edate").innerHTML = await data["enddate"]
  document.getElementById("event_org").innerHTML = await data["host_email"]
  document.getElementById("event_desc").innerHTML = await data["description"]
  load_image_event(data["eimage"])

  let container = document.getElementById('myTableContainer');
  let myTable = await generate_event_results(event_id);
  container.appendChild(myTable);

}

function load_image_event(indata) {
  var img = document.createElement("img")
  img.setAttribute("id", "event_image_display")
  img.setAttribute("class", "img-fluid d-block")
  img.src = indata
  var src = document.getElementById("image_box")
  src.appendChild(img);
}

function CreateTrack(track_input, start_station, end_station){
    //var track_name = document.getElementById("InputTrackName")
    fetch("rasts.se/api/Track",{method: 'POST',
    body:JSON.stringify({
      "track_id": track_input, //most of these attributes are set to 0 for now because the inputs on the site and the attributes in the database aren't the same
      "track_name": track_name,
      "start_station": 0,
      "end_station": end_station
    }), headers:{"Content-Type":"application/json; charset=UTF-8"}
    })
}

async function create_event() {
  var parameters = {}
  parameters["event_name"] = document.getElementById('send_event_name').value
  parameters["track_id"] = document.getElementById('send_track_name').value
  parameters["host_email"] = document.getElementById('send_host_email').value
  parameters["startdate"] = document.getElementById('send_start_date').value
  parameters["enddate"] = document.getElementById('send_end_date').value
  parameters["eimage"] = document.getElementById('send_image').value
  parameters["description"] = document.getElementById('send_description').value
  // parameters["open_for_entry"] = document.getElementById('send_open').value
  // parameters["public_view"] = document.getElementById('send_public').value
  parameters["open_for_entry"] = 1
  parameters["public_view"] = 1

  console.log(parameters["open_for_entry"])
  console.log(parameters["public_view"])

  if (document.getElementById("send_image").files.length != 0) {
    var blob = await image_to_blob(document.getElementById('send_image'))
    parameters["eimage"] = await blobToBase64(blob)
  }

  for (const [key, value] of Object.entries(parameters)) {
    console.log(key, value);
    if (!value) {
      delete parameters[key];
    }
  }


  const response = await fetch(BASE_ULR + "Event", {
    method: 'POST',
    body: JSON.stringify(parameters)
  })

  location.href = '../pages/confirmation_event.php'
}

function preview_event(){
  let event_name = document.getElementById('send_event_name').value;
  let host_name = document.getElementById('send_description').value;
  let start_date = document.getElementById('send_start_date').value;
  let end_date = document.getElementById('send_end_date').value;
  let imageInput = document.getElementById('send_image');
  let image = '';

  // check if an image was selected
  if (imageInput.files && imageInput.files[0]) {
    let reader = new FileReader(); // create a FileReader object
    reader.onload = function() {
      image = reader.result; // set image to the result of the FileReader
      generate_card_wide(event_name, 'Date: '+start_date+'\n - '+end_date, host_name, image);
    }
    reader.readAsDataURL(imageInput.files[0]); // read the selected file as a data URL
  } else {
    generate_card_wide(event_name, 'Date: '+start_date+'\n - '+end_date, host_name, image);
  }
}

async function TrackDropdown(){
  response = await fetch("https://rasts.se/api/Track", {method:'GET',
   headers: {'Accept': 'Application/json'}})
  let dropdown = document.getElementById('dropdown');
  data = await response.json();
  //var data = GetTrack();
  for(let i = 0; i < data.length; i++){
    dropdown.add(new Option(data[i].track_name))
}}


// Function to generate table
async function generate_event_results(event_id) {

  const response = await fetch(BASE_ULR + "Results/?event_id="+event_id, {
    method: 'GET',
  })
  const data = await response.json()


  let table = document.createElement('table');
  table.setAttribute('class','table')

  // create table header row
  let headerRow = document.createElement('tr');
  for (let key in await data.results[0]) {
    let headerCell = document.createElement('th');
    headerCell.textContent = key;
    headerRow.appendChild(headerCell);
  }
  table.appendChild(headerRow);

  // create table rows
  for (let i = 0; i < await data.results.length; i++) {
    let row = document.createElement('tr');
    for (let key in  await data.results[i]) {
      let cell = document.createElement('td');
      cell.textContent = await data.results[i][key];
      console.log(await data.results[i][key])
      row.appendChild(cell);
    }
    table.appendChild(row);
  }

  return table;
}

function register_on_event(event_id){
  console.log(event_id)
  var parameters = {}
  parameters["chip_id"] = document.getElementById('send_chip').value
  parameters["event_id"] = event_id
  console.log(parameters)
  const response = fetch(BASE_ULR + "Registration", {
    method: 'POST',
    body: JSON.stringify(parameters)
  })
}