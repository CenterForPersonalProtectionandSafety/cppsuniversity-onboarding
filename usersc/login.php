<?php
// This is a user-facing page
/*
UserSpice 5
An Open Source PHP User Management System
by the UserSpice Team at http://UserSpice.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
ini_set("allow_url_fopen", 1);
if(isset($_SESSION)){session_destroy();}
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
$hooks =  getMyHooks();
includeHook($hooks,'pre');
if($settings->twofa == 1){
  $google2fa = new PragmaRX\Google2FA\Google2FA();
}
?>
<?php
if(ipCheckBan()){Redirect::to($us_url_root.'usersc/scripts/banned.php');die();}
$errors = [];
$successes = [];
if (@$_REQUEST['err']) $errors[] = $_REQUEST['err']; // allow redirects to display a message
$reCaptchaValid=FALSE;
if($user->isLoggedIn()) Redirect::to($us_url_root.'index.php');

if (!empty($_POST['login_hook'])) {
  $token = Input::get('csrf');
  if(!Token::check($token)){
    include($abs_us_root.$us_url_root.'usersc/scripts/token_error.php');
  }

  //Check to see if recaptcha is enabled
  if($settings->recaptcha == 1){
    //require_once $abs_us_root.$us_url_root.'users/includes/recaptcha.config.php';

    //reCAPTCHA 2.0 check
    $response = null;

    // check secret key
    $reCaptcha = new \ReCaptcha\ReCaptcha($settings->recap_private);

    // if submitted check response
    if ($_POST["g-recaptcha-response"]) {
      $response = $reCaptcha->verify($_POST["g-recaptcha-response"],$_SERVER["REMOTE_ADDR"]);
    }
    if ($response != null && $response->isSuccess()) {
      $reCaptchaValid=TRUE;
    }else{
      $reCaptchaValid=FALSE;
      $errors[] = lang("CAPTCHA_ERROR");
      $reCapErrors = $response->getErrorCodes();
      foreach($reCapErrors as $error) {
        logger(1,"Recapatcha","Error with reCaptcha: ".$error);
      }
    }
  }else{
    $reCaptchaValid=TRUE;
  }

  if($reCaptchaValid || $settings->recaptcha == 0){ //if recaptcha valid or recaptcha disabled

    $validate = new Validate();
    $validation = $validate->check($_POST, array(
      'username' => array('display' => 'Username','required' => true),
      'password' => array('display' => 'Password', 'required' => true)));
      //plugin goes here with the ability to kill validation
      includeHook($hooks,'post');
      if ($validation->passed()) {
        //Log user in
        $remember = (Input::get('remember') === 'on') ? true : false;
        $user = new User();
        $login = $user->loginEmail(Input::get('username'), trim(Input::get('password')), $remember);
        if ($login) {
          $dest = sanitizedDest('dest');
              # if user was attempting to get to a page before login, go there
              $_SESSION['last_confirm']=date("Y-m-d H:i:s");

              //check for need to reAck terms of service
              if($settings->show_tos == 1){
                if($user->data()->oauth_tos_accepted == 0){
                  Redirect::to($us_url_root.'users/user_agreement_acknowledge.php');
                }


              if (!empty($dest)) {
                $redirect=htmlspecialchars_decode(Input::get('redirect'));
                if(!empty($redirect) || $redirect!=='') Redirect::to($redirect);
                else Redirect::to($dest);
              } elseif (file_exists($abs_us_root.$us_url_root.'usersc/scripts/custom_login_script.php')) {

                # if site has custom login script, use it
                # Note that the custom_login_script.php normally contains a Redirect::to() call
                require_once $abs_us_root.$us_url_root.'usersc/scripts/custom_login_script.php';
              } else {
                if (($dest = Config::get('homepage')) ||
                ($dest = 'account.php')) {
                  #echo "DEBUG: dest=$dest<br />\n";
                  #die;
                  Redirect::to($dest);
                }
              }
            }
          } else {
            $msg = lang("SIGNIN_FAIL");
            $msg2 = lang("SIGNIN_PLEASE_CHK");
            $errors[] = '<strong>'.$msg.'</strong>'.$msg2;
          }
        }
      }
    }
    if (empty($dest = sanitizedDest('dest'))) {
      $dest = '';
    }
    $token = Token::generate();
    ?>

<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="templates/cpps/assets/css/logged_out.css">
<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function(){
      document.getElementById('loginModal').style.display='block'
    });
</script>

<?=resultBlock($errors,$successes);?>
<div class="row">
  <div class="col-sm-12">
    <?php
      includeHook($hooks,'body');
    ?>
  </div>
</div>

<div class="w3-container">
  <div id="loginModal" class="w3-modal" data-keyboard="false" data-backdrop="static">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:600px">

      <div class="w3-center"><br>
        <img src="/usersc/images/onboarding_logo.png" alt="Avatar" style="width:50%" class=" w3-margin-top">
      </div>

      <form name="login" id="login-form" class="w3-container" action="login.php" method="post">
        <div class="w3-section">
          <!-- <h2 class="form-signin-heading"></i> <?=lang("SIGNIN_TITLE","");?></h2> -->
          <input type="hidden" name="dest" value="<?= $dest ?>" />


          <label for="username"><?=lang("SIGNIN_UORE")?></label>
          <input  class="w3-input w3-border w3-margin-bottom" type="text" name="username" id="username" placeholder="<?=lang("SIGNIN_UORE")?>" required autofocus autocomplete="username">



          <label for="password"><?=lang("SIGNIN_PASS")?></label>
          <input type="password" class="w3-input w3-border"  name="password" id="password"  placeholder="<?=lang("SIGNIN_PASS")?>" required autocomplete="current-password">


          <?php   includeHook($hooks,'form');?>

          <input type="hidden" name="login_hook" value="1">
          <input type="hidden" name="csrf" value="<?=$token?>">
          <input type="hidden" name="redirect" value="<?=Input::get('redirect')?>" />
          <button class="w3-button w3-block w3-dark-grey w3-section w3-padding" id="next_button" type="submit"><i class="fa fa-sign-in"></i> <?=lang("SIGNIN_BUTTONTEXT","");?></button>
          <?php if($settings->recaptcha == 1){ ?>
            <div class="g-recaptcha" data-sitekey="<?=$settings->recap_public; ?>" data-bind="next_button" data-callback="submitForm"></div>
          <?php } ?>
        </div>
      </form>

      <div class="w3-bar">
        <button class="w3-bar-item w3-button w3-dark-grey w3-mobile" style="width:33.3%" onclick="window.location.href='<?=$us_url_root?>usersc/login.php'"><i class="fa fa-sign-in"></i> Login</button>
        <button class="w3-bar-item w3-button w3-dark-grey w3-mobile" style="width:33.3%" onclick="window.location.href='<?=$us_url_root?>usersc/forgot_password.php'"><i class="fa fa-info-circle"></i> Forgot Password</button>
        <button class="w3-bar-item w3-button w3-dark-grey w3-mobile" style="width:33.3%" onclick="window.location.href='<?=$us_url_root?>usersc/join.php'"><i class="fa fa-user-plus"></i> Register</button>
      </div>

    </div>
  </div>
</div>

<!-- <div class="row">
  <div class="col-sm-6"><br>
    <a class="pull-left" href='../users/forgot_password.php'><i class="fa fa-wrench"></i> <?= lang("SIGNIN_FORGOTPASS","");?></a>
    <br><br>
  </div>
  <?php  if($settings->registration==1) {?>
  <div class="col-sm-6"><br>
    <a class="pull-right" href='../users/join.php'><i class="fa fa-plus-square"></i> <?= lang("SIGNUP_TEXT","");?></a><br><br>
  </div><?php } ?>
  <?php   includeHook($hooks,'bottom');?>
  <?php languageSwitcher();?>
</div> -->


<?php require_once $abs_us_root.$us_url_root.'usersc/templates/'.$settings->template.'/container_close.php'; //custom template container ?>


<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
<?php if($settings->recaptcha == 1){ ?>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script>
    function submitForm() {
      document.getElementById("login-form").submit();
    }
  </script>
<?php } ?>
<?php require_once $abs_us_root.$us_url_root.'usersc/templates/'.$settings->template.'/footer.php'; //custom template footer?>
