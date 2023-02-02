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
        // $ToJobForm   = tr_form('to_job', 'request');  
        // $ToJobForm   = tr_form('to_job', 'create');  
        // $ToJobButton = 'ارسال درخواست';  
        // echo $ToJobForm->open();
    ?>
    <span>درخواست معمولی</span>
    <form action="/irantax/request" method="get">
        <?php echo tr_field_nonce(); ?>
        <input type="hidden" name="toJobUserID" value="<?php if(isset($user_info->ID)) {echo $user_info->ID;} ?>">
        <?php foreach ($job as $job_detail): ?>
            <input type="hidden" name="toJobJobID" value="<?php if(isset($job_detail['id'])) {echo $job_detail['id'];} ?>">
        <?php endforeach; ?>

        <lable for="toJobUserName">نام و نام خانوادگی</lable><br>
        <input type="text" name="toJobUserName" id="to-job-user-name" placeholder="<?php if(isset($user_info->user_login)) {echo $user_info->user_login;} ?>" readonly><br>

        <lable for="toJobUserEmail">پست الکترونیکی</lable><br>
        <input type="email" name="toJobUserEmail" id="to-job-user-email" placeholder="<?php if(isset($user_info->user_email)) {echo $user_info->user_email;} ?>" readonly><br>

        <lable for="toJobContent">توضیحات و درخواست</lable><br>
        <textarea name="toJobContetn" id="to-job-contetn" cols="30" rows="10"></textarea><br>
        <input type="submit" value="ارسال درخواست">
    </form>
    <?php //echo $ToJobForm->close($ToJobButton); ?>


<span>درخواست آجاکس</span>
<form  method="post" id="toJob">
    <?php echo tr_field_nonce(); ?>
    <input type="hidden" name="toJobUserID" id="to-job-user-id" value="<?php if(isset($user_info->ID)) {echo $user_info->ID;} ?>">
    <?php foreach ($job as $job_detail): ?>
        <input type="hidden" name="toJobJobID" id="to-job-job-id" value="<?php if(isset($job_detail['id'])) {echo $job_detail['id'];} ?>">
    <?php endforeach; ?>

    <lable for="toJobUserName">نام و نام خانوادگی</lable><br>
    <input type="text" name="toJobUserName" id="to-job-user-name" placeholder="<?php if(isset($user_info->user_login)) {echo $user_info->user_login;} ?>" readonly><br>

    <lable for="toJobUserEmail">پست الکترونیکی</lable><br>
    <input type="email" name="toJobUserEmail" id="to-job-user-email" placeholder="<?php if(isset($user_info->user_email)) {echo $user_info->user_email;} ?>" readonly><br>

    <lable for="toJobContent">توضیحات و درخواست</lable><br>
    <textarea name="toJobContent" id="to-job-content" cols="30" rows="10"></textarea><br>

    <button type="button" name="toJobSubmit" onclick="toJobAjaxJs()" disabled>ارسال درخواست</button>
    <!-- <input type="submit" name="toJobSubmit" onclick="toJobAjaxJs()" value="ارسال درخواست"> -->
</form>






<?php endif; ?>

<?php get_footer(); ?>