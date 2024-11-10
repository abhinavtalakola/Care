<?php
session_start();
include('doctor/includes/dbconnection.php');
?>
<!doctype html>
<html lang="en">
    <head>
        <title>Doctor Appointment Management System || Home Page</title>

        <!-- CSS FILES -->        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-icons.css" rel="stylesheet">
        <link href="css/owl.carousel.min.css" rel="stylesheet">
        <link href="css/owl.theme.default.min.css" rel="stylesheet">
        <link href="css/templatemo-medic-care.css" rel="stylesheet">

        <!-- AJAX to get doctors -->
        <script>
            function getdoctors(val) {
                $.ajax({
                    type: "POST",
                    url: "get_doctors.php",
                    data: { sp_id: val },
                    success: function(data) {
                        $("#doctorlist").html(data);
                    }
                });
            }
        </script>
    </head>
    
    <body id="top">
        <main>
            <!-- Navbar -->
            <?php include_once('includes/header.php'); ?>

            <!-- Booking Section -->
            <section class="section-padding" id="booking">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-12 mx-auto">
                            <div class="booking-form">
                                <h2 class="text-center mb-lg-3 mb-2">Search Appointment History by Appointment Number/Name/Mobile No</h2>
                                <form role="form" method="post">
                                    <div class="row">
                                        <div class="col-lg-6 col-12">
                                            <input id="searchdata" type="text" name="searchdata" required class="form-control" placeholder="Appointment No./Name/Mobile No.">
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-6 mx-auto">
                                            <button type="submit" class="form-control" name="search" id="submit-button">Check</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <?php
                            if (isset($_POST['search'])) {
                                $sdata = $_POST['searchdata'];
                                echo '<h4 align="center">Result against "' . htmlentities($sdata) . '" keyword </h4>';
                            ?>
                                <div class="widget-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover js-basic-example dataTable table-custom">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Appointment Number</th>
                                                    <th>Patient Name</th>
                                                    <th>Mobile Number</th>
                                                    <th>Email</th>
                                                    <th>Status</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM tblappointment WHERE AppointmentNumber LIKE :sdata OR Name LIKE :sdata OR MobileNumber LIKE :sdata";
                                                $query = $dbh->prepare($sql);
                                                $query->bindValue(':sdata', "$sdata%");
                                                $query->execute();
                                                $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                $cnt = 1;
                                                if ($query->rowCount() > 0) {
                                                    foreach ($results as $row) {
                                                ?>
                                                        <tr>
                                                            <td><?php echo htmlentities($cnt); ?></td>
                                                            <td><?php echo htmlentities($row->AppointmentNumber); ?></td>
                                                            <td><?php echo htmlentities($row->Name); ?></td>
                                                            <td><?php echo htmlentities($row->MobileNumber); ?></td>
                                                            <td><?php echo htmlentities($row->Email); ?></td>
                                                            <td><?php echo $row->Status ? htmlentities($row->Status) : "Not Updated Yet"; ?></td>
                                                            <td><?php echo $row->Remark ? htmlentities($row->Remark) : "Not Updated Yet"; ?></td>
                                                        </tr>
                                                <?php
                                                        $cnt++;
                                                    }
                                                } else {
                                                    echo '<tr><td colspan="7">No record found against this search</td></tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <?php include_once('includes/footer.php'); ?>

        <!-- JAVASCRIPT FILES -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/owl.carousel.min.js"></script>
        <script src="js/scrollspy.min.js"></script>
        <script src="js/custom.js"></script>
    </body>
</html>
