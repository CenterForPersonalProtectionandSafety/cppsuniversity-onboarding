<?php
/*
This is a user-facing page
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
?>
<div class="w3-center">
  <h1><?=lang("JOIN_SUC")?><?=$settings->site_name?></h1>
  <p><?=lang("JOIN_THANKS");?></p>
  <div class="w3-container">
    <button class="w3-button w3-block w3-dark-grey w3-section w3-padding" onclick="window.location.href='<?=$us_url_root?>usersc/login.php'"><?=lang("SIGNIN_TEXT");?></button>
  </div>
  <br /><br />
</div>
