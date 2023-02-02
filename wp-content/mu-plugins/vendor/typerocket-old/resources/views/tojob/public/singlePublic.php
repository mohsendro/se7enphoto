<?php
    get_header();
    foreach ($job as $job_detail) {
        echo $job_detail['id'] . ' | ' . $job_detail['title'] . ' | ' . $job_detail['content'] . ' | ' . '(شرکت ' . $job_detail['company_id']. ')';

    }
?>

<hr>

<?php if ( is_user_logged_in() ): ?>

    <?php 
        $user_info = wp_get_current_user();
        $ToJobForm   = tr_form('to_job', 'create');  
        $ToJobButton = 'ارسال درخواست';  
        echo $ToJobForm->open();
    ?>

    <input type="hidden" name="toJobUserID" placeholder="<?php if(isset($user_info->ID)) {echo $user_info->ID;} ?>">

    <lable for="toJobUserName">نام و نام خانوادگی</lable><br>
    <input type="text" name="toJobUserName" id="to-job-user-name" placeholder="<?php if(isset($user_info->user_login)) {echo $user_info->user_login;} ?>" readonly><br>

    <lable for="toJobUserEmail">پست الکترونیکی</lable><br>
    <input type="email" name="toJobUserEmail" id="to-job-user-email" placeholder="<?php if(isset($user_info->user_email)) {echo $user_info->user_email;} ?>" readonly><br>

    <lable for="toJobDescription">توضیحات و درخواست</lable><br>
    <textarea name="toJobDescription" id="to-job-description" cols="30" rows="10"></textarea><br>

    <?php echo $ToJobForm->close($ToJobButton); ?>

<?php endif; ?>

<?php get_footer(); ?>