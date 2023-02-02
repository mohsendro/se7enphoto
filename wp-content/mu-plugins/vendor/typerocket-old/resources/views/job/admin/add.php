<!-- <form action=""  method="post">
    <input type="text" name="job_add_title">
    <input type="textarea" name="job_add_content">
    <?php //echo tr_field_nonce(); ?>
    <button type="submit" name="job_add_submit">افزودن شرکت</button>
</form> -->

<?php

$AddJobForm   = tr_form('job');  
$AddJobButton = 'ثبت آگهی';  

echo $AddJobForm->open();
?>

  <!-- <input type="number" name="job_add_company_id"> -->
  <select name="job_add_company_id">
    <?php 
      foreach( $company as $company_select) {
        echo "<option value='" . $company_select->id . "'>" . $company_select->title . "</option>";
      }
    ?>
  </select>
  <input type="text" name="job_add_title">
  <input type="textarea" name="job_add_content">

<?php
echo $AddJobForm->close($AddJobButton);
