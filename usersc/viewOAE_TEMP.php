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

<script type="text/javascript" src="../users/js/scorm-api/src/init.js"></script>
<script type="text/javascript" src="../users/js/scorm-api/src/constants.js"></script>
<script type="text/javascript" src="../users/js/scorm-api/src/baseAPI.js"></script>
<script type="text/javascript" src="../users/js/scorm-api/src/scormAPI.js"></script>
<script>
    function scormIsComplete() {
        //my code goes here
        $.ajax({
            url:"iscomplete/oae_iscomplete.php",
            method:"POST",
            success: function() {
                console.log('success');
                window.close();
            },
            error: function(xhr, error, thrownError) {
                console.log(xhr, error, thrownError);
            }
        });

        console.log('scormIsComplete function fired');
    }

    function scormSaveTime(mytime) {
        $.ajax({
            url:"savetime/oae_savetime.php",
            method:"POST",
            data:{mybookmark:mytime},
            success: function() {
                console.log('success');
            },
            error: function(xhr, error, thrownError) {
                console.log(xhr, error, thrownError);
            }
        });
    }

    function scormGetTime() {
        //my code goes here
        var mytime = $.ajax({
            url:"gettime/oae_gettime.php",
            method:"POST",
            async: false,
            success: function() {
                console.log('success');
            },
            error: function(xhr, error, thrownError) {
                console.log(xhr, error, thrownError);
            }
        });

        console.log('scormGetTime function fired');

        return mytime;
    }

    function leave() {
        //window.top.location = 'http://34.217.67.239/';
        window.close();
    }

    //scormIsComplete();
</script>
<script>


  window.API.on("LMSFinish", function() {
    var isCompleted = LMSGetValue('cmi.core.lesson_status');
    console.log(isCompleted);

    if (isCompleted == 'passed' || isCompleted == 'completed')
    {
        scormIsComplete();
    }
  });

  window.API.on("LMSGetValue", function(name) {
    //my code
    if (name == 'cmi.core.session_time') {
        //var value = '0000:00:00.00';
        var value = scormGetTime();
    }
    return value;
  });

  window.API.on("LMSSetValue", function(name, value) {
    if (name == 'cmi.core.session_time') {
        //scormSaveTime(value);
        var mytime = value;
        scormSaveTime(mytime);
    }
  });



  this.Utils = {
    launchSCO:function()
    {
        var wToolbar = false;
        var wTitlebar = false;
        var wLocation = true;
        var wStatus = true;
        var wScrollbars = true;
        var wResizable = true;
        var wMenubar = false;

        var launchFileAltVal = '../SCORM/OAE/index_lms.html';

        if(launchFileAltVal.length > 0)
        {
            launchFile = launchFileAltVal;
            if(launchFileAltVal.indexOf(":") == 1)
            {
                launchFile = "file:///"+launchFile;
            }
        }

        var w = 1024;
        var h = 768;

        var embedParam = '';

        var opts = '';
        opts += (wToolbar) ? 'toolbar=yes,' : '';
        opts += (wTitlebar) ? 'titlebar=yes,' : '';
        opts += (wLocation) ? 'location=yes,' : '';
        opts += (wStatus) ? 'status=yes,' : '';
        opts += (wScrollbars) ? 'scrollbars=yes,' : '';
        opts += (wResizable) ? 'resizable=yes,' : '';
        opts += (wMenubar) ? 'menubar=yes,' : '';
        opts = opts.substring(0, opts.length-1)

        this.openWindow(launchFile+embedParam,"SCOwindow",w,h,opts);
    },
    openWindow:function(winURL,winName,winW,winH,winOpts)
    {
        winOptions = winOpts+",width=" + winW + ",height=" + winH;
        newWin = window.open(winURL,winName,winOptions);
        //newWin.moveTo(0,0);
        //newWin.focus();
        return newWin;
    }
  };
</script>

<script>
    Utils.launchSCO();
</script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
