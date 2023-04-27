<?php include '../assets/head.php'; ?>
<body>
    <?php include '../assets/navbar.php'; ?>
    <div id="container-img">
        <div class="row">
            <div class="col-sm-6" id="profile_box">
            </div>
            <div class="col-sm-6" style="vertical-align: middle;">
                <div class="accout-piture">
                    <div class="reslut"></div>
                </div>
                <div>
                    <h1 class="text-shift">
                        <span id="profileName">Profile name</span>
                    </h1>
                    <h3 class="text-shift">Username: <span id="profile_username"></span></h3>
                    <h3 class="text-shift">Age: <span id="profile_age"></span> years</h3>
                    <h3 class="text-shift">Length: <span id="profile_length"></span> cm</h3>
                    <h3 class="text-shift">Weight: <span id="profile_weight"></span> kg</h3>
                </div>
            </div>
        </div>
    </div>

    <img class="w-100 op30" style="padding-top:2rem;" src="../images/indeximage_thinner.png" id="image_run" alt="Running figures">

    <div id="previousEventes">
        <h1 class="text-center">Previous Events</h1>
    </div>

    <div class="events" id="event">
        <div id="myTableContainerResults"></div>
    </div>

    <script type="text/javascript" src="../scripts/js_scripts.js"></script>
    <script>
        get_friend_info();
    </script>
    <?php include '../assets/footer.php'; ?>
</body>

</html>