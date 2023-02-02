<?php

$EditResumeForm   = tr_form('Resume', 'update', $resume->id);  
$EditResumeButton = 'ویرایش آگهی';  

echo $EditResumeForm->open();
?>

    <!-- <input type="text" name="resume_edit_id" value="<?php //echo $Resume[0]['id']; ?>" readonly>
    <input type="text" name="resume_edit_title" value="<?php //echo $Resume[0]['title']; ?>">
    <input type="textarea" name="resume_edit_content" value="<?php //echo $Resume[0]['content']; ?>"> -->

    <input type="number" name="resume_edit_id" value="<?php echo $resume->id; ?>" readonly>
    <input type="number" name="resume_edit_user_id" value="<?php echo $resume->user_id; ?>">
    <input type="text" name="resume_edit_title" value="<?php echo $resume->title; ?>">
    <input type="textarea" name="resume_edit_content" value="<?php echo $resume->content; ?>">

    
<?php
echo $EditResumeForm->close($EditResumeButton);