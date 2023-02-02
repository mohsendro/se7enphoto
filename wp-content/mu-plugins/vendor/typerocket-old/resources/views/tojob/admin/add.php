<?php

$AddToJobForm   = tr_form('to_job');  
$AddToJobButton = 'ثبت درخواست';  

echo $AddToJobForm->open();
?>

<select name="tojob_add_user_id">
    <?php
      foreach($user as $user_select) {
        echo "<option value='" . $user_select->ID . "'>" . $user_select->ID . " | " . $user_select->user_login . "</option>";
      }
    ?>
  </select>
  <select name="tojob_add_job_id">
    <?php 
      foreach( $job as $job_select) {
        echo "<option value='" . $job_select->id . "'>" . $job_select->title . "</option>";
      }
    ?>
  </select>
  <textarea name="tojob_add_content" cols="30" rows="10"></textarea>
  <input type="checkbox" name="tojob_add_status">

<?php
echo $AddToJobForm->close($AddToJobButton);
