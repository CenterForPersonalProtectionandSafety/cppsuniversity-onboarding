<?php
/*
BL Module
*/
?>
<div class="card">
    <input type="checkbox" id="card3" class="more" aria-hidden="true">
    <div class="content">
        <?php if ($user->data()->complete_oea == 0){ ?>
        <div class="front" style="background-image: url('/usersc/images/ott_comingsoon.png')">
        <?php } ?>
        <?php if ($user->data()->complete_oea == 1){ ?>
        <div class="front" style="background-image: url('/usersc/images/ott_comingsoon.png')">
        <?php } ?>
            <div class="inner">
                <h2></h2>
                <label for="card3" class="button" aria-hidden="true">
                    Details
                </label>
            </div>
        </div>
        <div class="back">
            <div class="inner">
                <div class="description">
                  <h4>Trainer Training</h4>
                  <p>This course has been developed to help CPPS Trainers get a baseline guidance on our in person training courses. It will provide them with an understanding of what in-person trainings we provide and how to properly train a class on each of the courses.</p>
                </div>
                <label for="card3" class="button return" aria-hidden="true">
                    <i class="fa fa-arrow-left"></i>
                </label>
                <a href="/usersc/viewOTT.php" class="button return button-play" aria-hidden="true">
                  <i class="fa fa-play"></i>
                </a>
            </div>
        </div>
    </div>
</div>
