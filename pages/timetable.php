<?php include '../assets/head.php'; ?>

<body>
  <?php include '../assets/navbar.php'; ?>

  <script>
    class checkpoint {
      constructor(id, next, starttime, endtime, totaltime, terrain, distance) {
        this.id = id
        this.next = next
        this.starttime = starttime
        this.endtime = null
        this.totaltime = totaltime
        this.terrain = terrain
        this.distance = distance
        this.velocity = null
      };
      CalcVelocity(starttime, endtime, distance){
        this.velocity = distance/(endtime - starttime)
      }
      UpdateNext(prevInd){//I HATE SETTERS
        this.next = prevInd
      }
      UpdateEndTime(next){
        this.endtime = next.starttime
        //last checkpoint/ finish line should have same endtime as starttime?
      }
    };
    class testpoint{
      constructor(station_id, next_id, next_distance, terrain, track_name, coordinates){
        this.station_id = station_id
        this.next_id = next_id
        this.next_distance = next_distance
        this.terrain = terrain,
        this.track_name = track_name
        this.coordinates = coordinates
      }
    };

    var check1 = new checkpoint(101, 102, 2, 3, "land", 45, 300, 0);
    var check2 = new checkpoint(102, 103, 3, 4, "land", 45, 300, 0);
    var check3 = new checkpoint(103, 103, 4, 4, "land", 45, 300, 0);
    var data = [check1, check2, check3];
    
    async function GetChecks(track_name){
      response = await fetch("https://rasts.se/api/Checkpoint?track_name="+track_name, {method:'GET',
      headers: {'Accept': 'Application/json'}})
      data = await response.json();
      checkpts_obj = []
      for(let i = 0; i < data.length; i++){
        if (data[i].track_name === track_name){
          checkpts_obj[i] = new testpoint(data[i].station_id, data[i].next_id,
          data[i].next_distance, data[i].terrain, data[i].track_name, data[i].coordinates)
        }
      }
      console.log(checkpts_obj)
      FillTable(checkpts_obj)
      //async returns promise, fix later
      //may have to put all code in async as jank solution
      //need to make sure it only grabs specific user data, not all to avoid potential privacy problems
    }
  </script>


  <div class="mb-3 mx-auto w-50">
    <h1>Event:</h1>
    <table style="border-color: black;" class="table table-bordered" id="timetable">
      <thead>
        <tr>
          <th scope="col">Checkpoint nr:</th>
          <th scope="col">Starttime:</th>
          <th scope="col">Endtime:</th>
          <th scope="col">Total time:</th>
          <th scope="col">Terrain:</th>
          <th scope="col">Distance:</th>
          <th scope="col">Velocity:</th>

        </tr>
      </thead>
      <tbody>
        <script>
          function FillTable(data){
          for (let i = 0; i < data.length; i++) {
            let row = timetable.insertRow(i + 1)
            let cell1 = row.insertCell(0)
            let cell2 = row.insertCell(1)
            let cell3 = row.insertCell(2)
            let cell4 = row.insertCell(3)
            let cell5 = row.insertCell(4)
            let cell6 = row.insertCell(5)
            let cell7 = row.insertCell(6)
            //let cell7 = row.insertCell(6)
            if (i == 0) {
              cell1.innerHTML = "Start"
            } else if (i == data.length - 1) {
              cell1.innerHTML = "Finish"
            } else {
              cell1.innerHTML = i
            }
            cell2.innerHTML = data[i].station_id
            cell3.innerHTML = data[i].next_id
            cell4.innerHTML = data[i].next_distance
            cell5.innerHTML = data[i].terrain
            cell6.innerHTML = data[i].track_name
            cell7.innerHTML = data[i].coordinates
            //cell2.innerHTML = data[i].starttime
            //cell3.innerHTML = data[i].endtime
            //cell4.innerHTML = data[i].totaltime
            //cell5.innerHTML = data[i].terrain
            //cell6.innerHTML = data[i].distance
            //data[i].CalcVelocity(data[i].starttime, 
            //data[i].endtime, data[i].distance)
            //cell7.innerHTML = data[i].velocity
          }
        }
          GetChecks("validation_test")
        </script>
      </tbody>
    </table>
  </div>
  <?php include '../assets/footer.php'; ?>
  <script type="text/javascript" src="../scripts/timetable.js"></script>

</body>
