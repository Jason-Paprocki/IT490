<?php
// if login

// if (!isset($_COOKIE['id'])) {
//     header("/login.php");
//     exit();
// }

// creaating table if not exist
// if form submited 


$vaccine = array(
    array('name' => "DHLPPC", "dosage" => "First ", "duration" => "6 to 8 week", "days" => "+ 6 weeks"),
    array('name' => "DHLPPC", "dosage" => "Second ", "duration" => "9 to 11 week", "days" => "+9 weeks"),
    array('name' => "DHLPPC", "dosage" => "First ", "duration" => "12 to 14 week", "days" => "+ 12 weeks"),
    array('name' => "DHLPPC", "dosage" => "First ", "duration" => "16 to 17 week", "days" => "+ 16 weeks"),
    array('name' => "DHLPPC", "dosage" => "Boster ", "duration" => "every 12 Months", "days" => "+1 year"),
    array('name' => "Bordetella", "dosage" => "First ", "duration" => "14 weeks", "days" => "+ 14 weeks"),
    array('name' => "Bordetella", "dosage" => "Second ", "duration" => "6 to 12 Months", "days" => "+1 years"),
    array('name' => "Rabies", "dosage" => "First ", "duration" => "16 weeks", "days" => "+16 weeks"),
    array('name' => "Rabies", "dosage" => "Booster  ", "duration" => "every 12 to 36 months", "days" => "+2 year"),
    array('name' => "Rabies", "dosage" => "First ", "duration" => "16 weeks", "days" => "+16 weeks"),
    array('name' => "Lyme", "dosage" => "First ", "duration" => "14 weeks", "days" => "+14 weeks"),
    array('name' => "Lyme", "dosage" => "Second  ", "duration" => "17 weeks", "days" => "+17 weeks"),
    array('name' => "Lyme", "dosage" => "Booster  ", "duration" => "every 12 month", "days" => "+1 years"),
);


$sql = "CREATE TABLE IF NOT EXISTS OrderItems(
    id int AUTO_INCREMENT PRIMARY KEY,
    pet_name varchar(50), 
    pet_type varchar(50), 
    checkup varchar(50), 
    checkup_date varchar(50), 
    category varchar(50), 
    category varchar(50), 
    category varchar(50), 
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  )";

?>

<!DOCTYPE html>
<html>

<head>
    <title>Medical Services</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="icon" href="https://cloudfront-us-east-1.images.arcpublishing.com/coindesk/XA6KIXE6FBFM5EWSA25JI5YAU4.jpg" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

</head>

<body>

    <div class="navbar">
        <ul class="navbar-container">
            <li><a href="index.html" class="left-underline nav-button brand-logo">Pet Adoption Service</a></li>
            <li class="nav-item active"><a href="login.php" class="left-underline nav-button" data-scroll>Account</a></li>
            <li class="nav-item"><a href="#section-3" class="left-underline nav-button" data-scroll>Wallet</a></li>
            <li class="nav-item"><a href="#section-2" class="left-underline nav-button" data-scroll>Services</a></li>
            <li class="nav-item active"><a href="forum.php" class="left-underline nav-button" data-scroll>Forums</a></li>
        </ul>
    </div>


    <div class="container">
        <div class="row">
            <h1>Calculate your dog vaccination schedule </h1>
            <form action="">
                <div class="form-group">
                    <label for="dob">Dog Date of birth:</label>
                    <input type="date" class="form-control" name="dob" placeholder="dob" id="dob">
                </div>

                <button type="submit" class="btn btn-primary">Get Scheduled</button>
            </form>
        </div>
        <table class="table table-bordered table-striped table-dark">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Vaccine Name</th>
                    <th>Dosage</th>
                    <th>Duration</th>
                    <?php if (isset($_GET['dob'])) { ?> <th>Your Date</th>
                        <th>Action</th> <?php  } ?>
                </tr>

            </thead>
            <tbody>
                <?php $i = 0;

                foreach ($vaccine as $v) { ?>
                    <tr>
                        <td><strong><?php echo ++$i; ?> </strong></td>
                        <td><?php echo $v['name'] ?></td>
                        <td><?php echo $v['dosage'] ?></td>
                        <td><?php echo $v['duration'] ?></td>
                        <?php if (isset($_GET['dob'])) { ?> <td> <?php echo date('D, Y-M-d', strtotime($_GET['dob'] . " " . $v['days'])); ?> </td>

                            <td> <button class="btn btn-primary">Notify me</button></td>
                        <?php } ?>

                    </tr>


                <?php } ?>
            </tbody>
        </table>
    </div>
    <!-- <div class="parallax p1" id="section-1">
        <hgroup>
            <h1>Login</h1>
            <form name="med_form" id="med_form" method="POST">
                <fieldset>
                    <legend><strong>Pet Information</strong></legend>
                    <label for="pet_name">Pet Name:</label><br>
                    <input type="text" id="pet_name" name="pet_name" placeholder="Enter Pet Name"><br><br>
                    <label for="pet_name">Pet Type:</label><br>
                    <input list="pet_type" name="pet_type" /></label>
                    <datalist id="pet_type">
                        <option value="Cat">
                        <option value="Dog">
                        <option value="Birds">
                        <option value="Rabbit">
                        <option value="Fish">
                        <option value="Pet Livestock">
                        <option value="Pet Poultry">
                    </datalist><br><br>
                </fieldset><br>
                <fieldset>
                    <label for="checkup">Checkup Type:</label><br>
                    <legend><strong>Schedule</strong></legend>
                    <select name="checkup" id="checkup">
                        <option value="">Select Checkup type</option>
                        <option value="routine">Routine</option>
                        <option value="annual">Annual</option>
                        <option value="emergency">Emergency</option>
                    </select><br><br>



                    <label for="checkup_date">Check up Date:</label><br>
                    <input type="date" id="checkup_date" name="checkup_date" placeholder="Select A Date"><br><br>

                    <label for="checkup_time">Check up Time:</label><br>
                    <input type="time" id="checkup_time" name="checkup_time" placeholder="Select A Time"><br><br>


                </fieldset><br>

                <fieldset>
                    <legend><strong>Contact Info</strong></legend>
                    <label for="address">Address:</label><br>
                    <input type="text" name="address" id="address" placeholder="Enter Address"><br><br>
                    <label for="contact">Contact Info:</label><br>
                    <input type="text" name="contact" id="contact" placeholder="Enter Mobile Number"><br>
                </fieldset><br>

                <input type="submit" name="submit" value="submit">
            </form>
        </hgroup>
    </div> -->
</body>

</html>
