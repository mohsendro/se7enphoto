<?php

$EditJobForm   = tr_form('job', 'destroy', $job->id);  
$EditJobButton = 'حذف آگهی';  

echo $EditJobForm->open();
?>

    <!-- <input type="text" name="job_edit_id" value="<?php //echo $job[0]['id']; ?>" readonly>
    <input type="text" name="job_edit_title" value="<?php //echo $job[0]['title']; ?>">
    <input type="textarea" name="job_edit_content" value="<?php //echo $job[0]['content']; ?>"> -->

    <input type="number" name="job_edit_id" value="<?php echo $job->id; ?>" readonly>
    <input type="number" name="job_edit_company_id" value="<?php echo $job->company_id; ?>" readonly>
    <input type="text" name="job_edit_title" value="<?php echo $job->title; ?>" readonly>
    <input type="textarea" name="job_edit_content" value="<?php echo $job->content; ?>" readonly>
    

<?php
echo $EditJobForm->close($EditJobButton);