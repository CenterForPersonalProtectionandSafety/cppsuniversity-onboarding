<div class="col-sm-8">
  <div class="page-header float-right">
    <div class="page-title">
      <ol class="breadcrumb text-right">
        <li><a href="<?=$us_url_root?>usersc/client_admin.php">Dashboard</a></li>
      </ol>
    </div>
  </div>
</div>
</div>
</header>

<?php
  $learners_query = $db->query("SELECT * FROM users WHERE id IN (SELECT user_id FROM user_permission_matches WHERE permission_id = 1)");
  $userData = $learners_query->results();
?>


<div class="container">
  <h2>All Employee Training</h2>
  <hr />
  <div class="alluinfo">&nbsp;</div>
  <div class="table-responsive">
    <table id="paginate" class='table table-hover table-list-search'>
        <thead class="thead-light">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>All Employee Training</th>
            </tr>
        </thead>
        <tbody>
            <?php
            //Cycle through users
            foreach ($userData as $v1) {
            ?>
            <tr>
                <td><?=$v1->fname?> <?=$v1->lname?></td>
                <td><?=$v1->email?></td>
                <td><?php if($v1->complete_oae==0) {?> <p>incomplete</p> <?php } else {?> <p>complete</p> <?php }?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
  </div>
</div>


<!-- End of main content section -->

<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->
<script src="../users/js/pagination/jquery.dataTables.js" type="text/javascript"></script>
<script src="../users/js/pagination/dataTables.js" type="text/javascript"></script>
<!-- <script>
    $(document).ready(function() {
        $('#paginate').DataTable({"pageLength": 25,"aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]], "aaSorting": []});


        $('[data-toggle="popover"], .pwpopover').popover();
        $('.pwpopover').on('click', function (e) {
            $('.pwpopover').not(this).popover('hide');
        });
        $('.modal').on('hidden.bs.modal', function () {
            $('.pwpopover').popover('hide');
        });
    });
</script> -->

<?php if($settings->auto_assign_un==0) { ?>
<script type="text/javascript">
    $(document).ready(function(){
        var x_timer;
        $("#username").keyup(function (e){
            clearTimeout(x_timer);
            var username = $(this).val();
            if (username.length > 0) {
                x_timer = setTimeout(function(){
                    check_username_ajax(username);
                }, 500);
            }
            else $('#usernameCheck').text('');
        });

        function check_username_ajax(username){
            $("#usernameCheck").html('Checking...');
            $.post('parsers/existingUsernameCheck.php', {'username': username}, function(response) {
                if (response == 'error') $('#usernameCheck').html('There was an error while checking the username.');
                else if (response == 'taken') $('#usernameCheck').html('<i class="glyphicon glyphicon-remove" style="color: red; font-size: 12px"></i> This username is taken.');
                else if (response == 'valid') $('#usernameCheck').html('<i class="glyphicon glyphicon-ok" style="color: green; font-size: 12px"></i> This username is not taken.');
                else $('#usernameCheck').html('');
            });
        }
    });
</script>
<?php } ?>
