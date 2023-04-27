<?php include '../assets/head.php'; ?>

<body>
    <?php include '../assets/navbar.php'; ?>
    <a type="button" style="float:right; padding: 0.5rem;" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRightSetting"><img class="op30" src="../images/settings.svg" id="settings" alt="Gear"></a>
    <div id="container-img">
        <div class="row">

            <div class="col-sm-6" id="profile_box">
                <img class="img-fluid d-block" alt="profile image" id="profile_image" src="<?= $user_data->pimage ?>">
            </div>
            <div class="col-sm-6" style="vertical-align: middle;">
                <div class="accout-piture">
                    <div class="reslut"></div>
                </div>

                <div>
                    <h1 class="text-shift">
                        <span id="profileName"><?= $user_data->first_name . " " . $user_data->last_name ?></span>
                    </h1>
                    <h3 class="text-shift">Username: <span id="profile_length"></span> <?= $user_data->username ?></h3>
                    <h3 class="text-shift">Age: <span id="profile_age"></span> <?= date_diff(date_create($user_data->birthdate), date_create('today'))->y; ?> years old</h3>

                    <h3 class="text-shift">Length: <span id="profile_length"></span> <?= $user_data->height ?> cm</h3>
                    <h3 class="text-shift">Weight: <span id="profile_weight"></span><?= $user_data->weight ?> kg</h3>
                    <h3 class="text-shift">Private Chip: <span id="profile_chip_id"></span><?= ($user_data->chip_id == null) ? 'dont have' : $user_data->chip_id ?></h3>
                    <?php if ($is_organization) { ?>

                        <h3 class="text-shift">Organization name: <span id="profile_weight"></span><?= $organization_data->org_name ?> </h3>
                        <h3 class="text-shift">Organization number: <span id="profile_weight"></span><?= $organization_data->org_number ?> </h3>
                        <h3 class="text-shift">Organization email: <span id="profile_weight"></span><?= $organization_data->org_email ?> </h3>
                        <h3 class="text-shift">Address: <span id="profile_weight"></span><?= $organization_data->address ?> </h3>
                        <h3 class="text-shift">Description: <span id="profile_weight"></span><?= $organization_data->description ?> </h3>

                    <?php } ?>
                    <form action="../assets/organization_request.php" class="orgform" method="GET">
                        <div class=" form-group form_group_style mx-auto">
                            <button class="button-modular" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRightOrg" aria-controls="offcanvasRight">Want to register your organisation?</button>

                            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRightOrg" aria-labelledby="offcanvasRightLabel">
                                <div class="offcanvas-header">
                                    <h5 id="offcanvasRightLabel">Register your organisation</h5>
                                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body canvas_container">
                                    To make sure you are an organisation, we would like you till fill this form:
                                    <div class="form-group form_group_style mx-auto needs-validation" novalidate>
                                        <div class="form-group form_group_style mx-auto"></div>
                                        <p>Organisation name</p>

                                        <input type="hidden" name="user_name" value="<?= $user_data->username ?>">
                                        <input type="text" name="org_name" class="form-control input_field_style" placeholder="Name" id="org_name" required>

                                    </div>
                                    <div class="form-group form_group_style mx-auto">
                                        <p>Address</p>
                                        <input type="text" name="address" class="form-control input_field_style" placeholder="Region of domain" id="org_country" required>

                                    </div>
                                    <div class="form-group form_group_style mx-auto">
                                        <p>Email Address for organisation </p>
                                        <input type="email" name="org_email" class="form-control input_field_style" placeholder="expproject@gmail.com" id="org_email" required>
                                    </div>
                                    <div class="form-group form_group_style mx-auto">
                                        <p>Organisation number </p>
                                        <input type="text" name="org_number" class="form-control input_field_style" placeholder="111111-0000" id="org_email" required>
                                    </div>
                                    <h2>Contact information</h2>
                                    <div class="form-group form_group_style mx-auto">
                                        <p>Private Email Address </p>
                                        <input type="text" name="contact_email" class="form-control input_field_style" placeholder="expproject@gmail.com" id="user_email" required>
                                    </div>
                                    <div class="form-group form_group_style mx-auto">
                                        <p>Phone Number </p>
                                        <input type="tel" name=" number" class="form-control input_field_style" placeholder="+46" id="user_email" required>
                                    </div>
                                    <div id="success-alert" class="alert alert-success alert-dismissible fade show d-none" role="alert">
                                        Form submitted successfully!
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    <div class="form-group form_group_style mx-auto">
                                        <button class="btn btn-primary" button id="submit_org_form" type="submit" onclick="fill_org_form()" value="Submit">Submit form</button>
                                    </div>
                                    After submission we will as soon as possible get back to you to give you more information on how to set up your organisational account. We will be in touch!
                                </div>
                            </div>
                        </div>
                    </form>

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
        <h2 class="underline_text">Results</h2>
        <table class="table table-bordered result_table" id="event_user_results">
        <thead>
            <tr>
            <th scope="col">User1</th>
            <th scope="col">User2</th>
            <th scope="col">Date</th>
            <th scope="col">Time</th>
            <th scope="col">Details</th>
            </tr>
        </thead>
        <tbody>
            <!-- <script src="../scripts/js_scripts.js">
                event_display_peeps()
            </script> -->
        </tbody>
        </table>
    </div>

    <!--Edit profile popup-->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRightSetting" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body canvas_container">
            <h4>Edit your profile</h4>
            <div class="form-group form_group_style mx-auto needs-validation" novalidate>

                <div class="mb-3 mx-auto">
                    <label for="InputTrackName" class="form-label">First Name</label>
                    <input type="text" class="form-control" placeholder="Input Your Name" id="send_f_name">
                </div>

                <div class="mb-3 mx-auto">
                    <label for="InputTrackName" class="form-label">Surname</label>
                    <input type="text" class="form-control" placeholder="Input Your Name" id="send_l_name">
                </div>

                <div class="mb-3 mx-auto">
                    <label for="InputTrackName" class="form-label">Birth day</label>
                    <input class="form-control" type="date" id="send_bday">
                    <span id="b_day_selected"></span>

                </div>

                <div class="mb-3 mx-auto">
                    <label for="InputTrackName" class="form-label">Height</label>
                    <input type="number" class="form-control" placeholder="Input Your Height" id="send_height">
                </div>

                <div class="mb-3 mx-auto">
                    <label for="InputTrackName" class="form-label">Weight</label>
                    <input type="number" class="form-control" placeholder="Input Your Weight" id="send_weight">
                </div>

                <div class="mb-3 mx-auto">
                    <label for="InputTrackName" class="form-label">Personal ChipID</label>
                    <input type="number" class="form-control" placeholder="Input Your Personal ChipID" id="send_chip">
                </div>

                <div class="mb-3 mx-auto">
                    <label for="InputTrackName" class="form-label">Upload Profile Picture</label>
                    <input type="file" name="filename" id="send_image">
                </div>

            </div>

            <div id="root">
                <p>Upload an image and see the result</p>
                <!-- <input id="img-input" type="file" accept="image/*" style="display:block" /> -->
                </div>

            <div id="success-alert" class="alert alert-success alert-dismissible fade show d-none" role="alert">
                Form submitted successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div class="form-group form_group_style mx-auto">
                <button class="btn btn-primary" button id="submit_org_form" type="submit" onclick="edit_user_info()">Save changes</button>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../scripts/js_scripts.js"></script>
    <script>


        // Add event listener for form submission
        //generate_user_results();
        // document.getElementsByClassName("orgform").addEventListener("submit", function(event) {
        //     // Prevent default form submission behavior
        //     event.preventDefault();
        //     // Show success alert
        //     document.getElementById("success-alert").classList.remove("d-none");

        //     submit_button = document.getElementById("submit_org_form");
        //     submit_button.setAttribute('disabled', '')
        // });
    </script>
    <?php include '../assets/footer.php'; ?>
</body>

</html>