<!-- <form action=""  method="post">
    <input type="text" name="company_add_title">
    <input type="textarea" name="company_add_content">
    <?php //echo tr_field_nonce(); ?>
    <button type="submit" name="company_add_submit">افزودن شرکت</button>
</form> -->

<?php

$AddCompanyForm   = tr_form('company');  
$AddCompanyButton = 'ثبت شرکت';  

echo $AddCompanyForm->open();
?>

    <input type="text" name="company_add_title">
    <input type="textarea" name="company_add_content">

<?php


// CSF::$enqueue = true;
// CSF::add_admin_enqueue_scripts();

// echo '<div class="csf-onload">';

  /**
   *  @field
   *  @value
   *  @unique
  */
//   CSF::field( array(
//     'id'    => 'opt-text',
//     'type'  => 'text',
//     'title' => 'Text',
//   ), 'Hello World', 'my_field' );

//   CSF::field( array(
//     'id'    => 'opt-textarea',
//     'type'  => 'textarea',
//     'title' => 'Textarea',
//   ), 'Lorem Ipsum Dollar...', 'my_field' );

//   CSF::field( array(
//     'id'      => 'opt-switcher',
//     'type'    => 'switcher',
//     'title'   => 'Switcher',
//     'default' => false
//   ), true, 'my_field' );

// echo '</div>';


echo $AddCompanyForm->close($AddCompanyButton);
