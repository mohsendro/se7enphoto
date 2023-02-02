<?php

$AddResumeForm   = tr_form('resume');  
$AddResumeButton = 'ثبت رزومه';  

echo $AddResumeForm->open();
?>

  <select name="resume_add_user_id">
    <?php //print_r($user);
      foreach($user as $user_select) {
        echo "<option value='" . $user_select->ID . "'>" . $user_select->ID . " | " . $user_select->user_login . "</option>";
      }
    ?>
  </select>
  <input type="text" name="resume_add_title">
  <input type="textarea" name="resume_add_content">

<?php
echo $AddResumeForm->close($AddResumeButton);
