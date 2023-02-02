<!-- <form action=""  method="post">
    <input type="text" name="company_edit_id" value="<?php //echo $company->id; ?>" readonly>
    <input type="text" name="company_edit_title" value="<?php //echo $company->title; ?>">
    <input type="textarea" name="company_edit_content" value="<?php //echo $company->content; ?>">
    <?php //echo tr_field_nonce(); ?>
    <button type="submit" name="company_edit_submit">بروزرسانی شرکت</button>
</form> -->

<?php

$EditCompanyForm   = tr_form('company', 'update', $company->id);  
$EditCompanyButton = 'ویرایش شرکت';  

echo $EditCompanyForm->open();
?>

    <!-- <input type="text" name="company_edit_id" value="<?php //echo $company[0]['id']; ?>" readonly>
    <input type="text" name="company_edit_title" value="<?php //echo $company[0]['title']; ?>">
    <input type="textarea" name="company_edit_content" value="<?php //echo $company[0]['content']; ?>"> -->

    <input type="number" name="company_edit_id" value="<?php echo $company->id; ?>" readonly>
    <input type="text" name="company_edit_title" value="<?php echo $company->title; ?>">
    <input type="textarea" name="company_edit_content" value="<?php echo $company->content; ?>">
    

<?php
echo $EditCompanyForm->close($EditCompanyButton);