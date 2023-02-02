<!-- <form action=""  method="post">
    <input type="text" name="job_edit_id" value="<?php //echo $job->id; ?>" readonly>
    <input type="text" name="job_edit_title" value="<?php //echo $job->title; ?>">
    <input type="textarea" name="job_edit_content" value="<?php //echo $job->content; ?>">
    <?php //echo tr_field_nonce(); ?>
    <button type="submit" name="job_edit_submit">بروزرسانی شرکت</button>
</form> -->

<?php

$EditJobForm   = tr_form('job', 'update', $job->id);  
$EditJobButton = 'ویرایش آگهی';  

echo $EditJobForm->open();
?>

    <!-- <input type="text" name="job_edit_id" value="<?php //echo $job[0]['id']; ?>" readonly>
    <input type="text" name="job_edit_title" value="<?php //echo $job[0]['title']; ?>">
    <input type="textarea" name="job_edit_content" value="<?php //echo $job[0]['content']; ?>"> -->

    <input type="number" name="job_edit_id" value="<?php echo $job->id; ?>" readonly>
    <input type="number" name="job_edit_company_id" value="<?php echo $job->company_id; ?>">
    <input type="text" name="job_edit_title" value="<?php echo $job->title; ?>">
    <input type="textarea" name="job_edit_content" value="<?php echo $job->content; ?>">
    

<?php
echo $EditJobForm->close($EditJobButton);