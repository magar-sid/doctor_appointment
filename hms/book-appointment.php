<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

// Handle the availability check
if (isset($_POST['checkAvailability'])) {
    $appdate = $_POST['appdate'];
    $apptime = $_POST['apptime'];

    // Check if the date and time are already booked
    $query = mysqli_query($con, "SELECT * FROM appointment WHERE appointmentDate='$appdate' AND appointmentTime='$apptime'");
    
    if (mysqli_num_rows($query) > 0) {
        echo 'unavailable';  // Slot is unavailable
    } else {
        echo 'available';  // Slot is available
    }

    exit();
}

// Handle form submission for booking
if (isset($_POST['submit'])) {
    $specilization = $_POST['Doctorspecialization'];
    $doctorid = $_POST['doctor'];
    $userid = $_SESSION['id'];
    $fees = $_POST['fees'];
    $appdate = $_POST['appdate'];
    $time = $_POST['apptime'];
    $userstatus = 1;
    $docstatus = 1;

    // First, re-check availability before final booking (to handle race conditions)
    $query = mysqli_query($con, "SELECT * FROM appointment WHERE appointmentDate='$appdate' AND appointmentTime='$time'");
    
    if (mysqli_num_rows($query) > 0) {
        echo "<script>alert('This time slot is already booked. Please select another time.');</script>";
    } else {
        // Insert the appointment into the database
        $query = mysqli_query($con, "INSERT INTO appointment (doctorSpecialization, doctorId, userId, consultancyFees, appointmentDate, appointmentTime, userStatus, doctorStatus) 
                                     VALUES ('$specilization', '$doctorid', '$userid', '$fees', '$appdate', '$time', '$userstatus', '$docstatus')");
        
        if ($query) {
            echo "<script>alert('Your appointment successfully booked');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<title>User  | Book Appointment</title>
	
		<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
		<link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
		<link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
		<link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
		<link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
		<link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
		<link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
		<link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="assets/css/styles.css">
		<link rel="stylesheet" href="assets/css/plugins.css">
		<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
		<script>
function getdoctor(val) {
	$.ajax({
	type: "POST",
	url: "get_doctor.php",
	data:'specilizationid='+val,
	success: function(data){
		$("#doctor").html(data);
	}
	});
}
</script>	


<script>
function getfee(val) {
	$.ajax({
	type: "POST",
	url: "get_doctor.php",
	data:'doctor='+val,
	success: function(data){
		$("#fees").html(data);
	}
	});
}
</script>	




	</head>
	<body>
		<div id="app">		
<?php include('include/sidebar.php');?>
			<div class="app-content">
			
						<?php include('include/header.php');?>
					
				<!-- end: TOP NAVBAR -->
				<div class="main-content" >
					<div class="wrap-content container" id="container">
						<!-- start: PAGE TITLE -->
						<section id="page-title">
							<div class="row">
								<div class="col-sm-8">
									<h1 class="mainTitle">User | Book Appointment</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span>User</span>
									</li>
									<li class="active">
										<span>Book Appointment</span>
									</li>
								</ol>
						</section>
						<!-- end: PAGE TITLE -->
						<!-- start: BASIC EXAMPLE -->
						<div class="container-fluid container-fullw bg-white">
							<div class="row">
								<div class="col-md-12">
									
									<div class="row margin-top-30">
										<div class="col-lg-8 col-md-12">
											<div class="panel panel-white">
												<div class="panel-heading">
													<h5 class="panel-title">Book Appointment</h5>
												</div>
												<div class="panel-body">
								<p style="color:red;"><?php echo htmlentities($_SESSION['msg1']);?>
								<?php echo htmlentities($_SESSION['msg1']="");?></p>	
													<form role="form" name="book" method="post" >
														


<div class="form-group">
															<label for="DoctorSpecialization">
																Doctor Specialization
															</label>
							<select name="Doctorspecialization" class="form-control" onChange="getdoctor(this.value);" required="required">
																<option value="">Select Specialization</option>
<?php $ret=mysqli_query($con,"select * from doctorspecilization");
while($row=mysqli_fetch_array($ret))
{
?>
																<option value="<?php echo htmlentities($row['specilization']);?>">
																	<?php echo htmlentities($row['specilization']);?>
																</option>
																<?php } ?>
																
															</select>
														</div>




														<div class="form-group">
															<label for="doctor">
																Doctors
															</label>
						<select name="doctor" class="form-control" id="doctor" onChange="getfee(this.value);" required="required">
						<option value="">Select Doctor</option>
						</select>
														</div>





														<div class="form-group">
															<label for="consultancyfees">
																Consultancy Fees
															</label>
					<select name="fees" class="form-control" id="fees"  readonly>
						
						</select>
														</div>
														
<div class="form-group">
															<!-- <label for="AppointmentDate">
																Date
															</label>
<input class="form-control datepicker" name="appdate"  required="required" data-date-format="yyyy-mm-dd">
	
														</div>
														
<div class="form-group">
															<label for="Appointmenttime">
														
														Time
													
															</label>
			<input class="form-control" name="apptime" id="timepicker1" required="required">eg : 10:00 PM
														</div>														 -->
														<div class="form-group">
        <label for="AppointmentDate">Date</label>
        <input class="form-control" name="appdate" id="appdate" required="required" data-date-format="yyyy-mm-dd">
    </div>

    <div class="form-group">
        <label for="Appointmenttime">Time</label>
        <select class="form-control" name="apptime" id="apptime" required="required">
            <option value="">Select Time</option>
            <!-- Time slots from 10:00 AM to 7:00 PM with 30-minute intervals -->
        </select>
    </div>

    <p id="error-message" style="color:red; display:none;">This time slot is unavailable. Please choose another.</p>

    <button type="submit" name="submit" id="submitBtn" class="btn btn-o btn-primary">
        Submit
    </button>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
    $(document).ready(function() {
        // Populate the time slots dropdown
        populateTimeSlots();

        // Set datepicker for date input
        $('#appdate').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '+1d',  // Disable past dates and today
            autoclose: true
        });

        // Check availability when date or time is changed
        $('#appdate, #apptime').on('change', function() {
            checkAvailability();
        });

        function checkAvailability() {
            var appdate = $('#appdate').val();
            var apptime = $('#apptime').val();

            if (appdate && apptime) {
                $.ajax({
                    type: 'POST',
                    url: 'book_appointment.php',  // Specify the current PHP file
                    data: {
                        checkAvailability: true,
                        appdate: appdate,
                        apptime: apptime
                    },
                    success: function(response) {
                        if (response == 'unavailable') {
                            $('#error-message').show();
                            $('#submitBtn').attr('disabled', true);  // Disable submit button
                        } else {
                            $('#error-message').hide();
                            $('#submitBtn').attr('disabled', false); // Enable submit button
                        }
                    },
                    error: function() {
                        console.log("Error in AJAX request.");
                    }
                });
            }
        }

        // Populate time slots from 10:00 AM to 7:00 PM in 30-minute intervals
        function populateTimeSlots() {
            var startTime = 10 * 60;  // 10:00 AM in minutes
            var endTime = 19 * 60;    // 7:00 PM in minutes
            var interval = 30;        // 30 minutes interval
            var options = '';

            for (var time = startTime; time <= endTime; time += interval) {
                var hours = Math.floor(time / 60);
                var minutes = time % 60;
                var ampm = hours >= 12 ? 'PM' : 'AM';
                var formattedTime = (hours % 12 === 0 ? 12 : hours % 12) + ':' + (minutes < 10 ? '0' + minutes : minutes) + ' ' + ampm;
                options += '<option value="' + formattedTime + '">' + formattedTime + '</option>';
            }

            $('#apptime').append(options);
        }
    });
    </script>


														

													</form>
												</div>
											</div>
										</div>
											
											</div>
										</div>
									
									</div>
								</div>
							
						<!-- end: BASIC EXAMPLE -->
			
					
					
						
						
					
						<!-- end: SELECT BOXES -->
						
					</div>
				</div>
			</div>
			<!-- start: FOOTER -->
	<?php include('include/footer.php');?>
			<!-- end: FOOTER -->
		
			<!-- start: SETTINGS -->
	<?php include('include/setting.php');?>
			
			<!-- end: SETTINGS -->
		</div>
		<!-- start: MAIN JAVASCRIPTS -->
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="vendor/modernizr/modernizr.js"></script>
		<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
		<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
		<script src="vendor/switchery/switchery.min.js"></script>
		<!-- end: MAIN JAVASCRIPTS -->
		<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
		<script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
		<script src="vendor/autosize/autosize.min.js"></script>
		<script src="vendor/selectFx/classie.js"></script>
		<script src="vendor/selectFx/selectFx.js"></script>
		<script src="vendor/select2/select2.min.js"></script>
		<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
		<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<!-- start: CLIP-TWO JAVASCRIPTS -->
		<script src="assets/js/main.js"></script>
		<!-- start: JavaScript Event Handlers for this page -->
		<script src="assets/js/form-elements.js"></script>
		<script>
			jQuery(document).ready(function() {
				Main.init();
				FormElements.init();
			});

			$('.datepicker').datepicker({
    format: 'yyyy-mm-dd',
    startDate: '-3d'
});
		</script>
		  <script type="text/javascript">
            $('#timepicker1').timepicker();
        </script>
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>

	</body>
</html>
