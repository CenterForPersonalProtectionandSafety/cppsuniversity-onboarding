<?php
/*
SCORM Player - Tier 3
*/
?>

<?php
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'usersc/includes/navigation.php';
?>

<?php if (!securePage($_SERVER['PHP_SELF'])){die();} ?>


<!-- Page Content -->
<div id="page-wrapper" class="modulePage" onload="Utils.launchSCO(); return false;">
  <div class="container-fluid">
      <div class="text-center">
          <img src="/usersc/images/universitylogo.png" alt="..." class="">
      </div>
  </div>
  <div class="container-fluid">
      <div class="text-center">
          <h2 class="viewcoursep">Please do not close this tab until finished with course.</h2>
          <h2 class="viewcoursep">If the course does not begin, please ensure your popup blocker has been disabled. <br> Once disabled, please click the refresh button below to re-launch the course.</h2>
      </div>
  </div>
  <div class="container-fluid">
    <div class="text-center">
      <a href="../index.php" class="btn btn-primary" role="button">Return Home</a>
      <a href="#" class="btn btn-primary" role="button" onClick="window.location.href=window.location.href">Refresh Page</a>
    </div>
  </div>
</div>

<!-- Javascript to link times to databse (ie isComplete, getTime, saveTime) -->
<!-- Place any per-page javascript here -->



<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
