<?php

foreach ($to_job as $to_job_detail) {
    echo $to_job_detail->id . ' | از طرف کاربر با شناسه ' . $to_job_detail->user_id . ' | به آگهی با شناسه ' . $to_job_detail->job_id . "<br>";
    echo "( " .$to_job_detail->content. " )";
    ?>
    <!-- <a href="<?php //echo get_home_url(); ?>/tojob/<?php //echo $to_job_detail->id; ?>" style="color:blue;"> نمایش </a> -->
    &nbsp;
    <a href="<?php echo get_admin_url(); ?>admin.php?page=tojob_edit&route_args%5B0%5D=<?php echo $to_job_detail->id; ?>" style="color:green;"> ویرایش </a>
    &nbsp;
    <a href="<?php echo get_admin_url(); ?>admin.php?page=tojob_delete&route_args%5B0%5D=<?php echo $to_job_detail->id; ?>" style="color:red;"> حذف </a>
    &nbsp;
    <a href="<?php echo get_admin_url(); ?>user-edit.php?user_id=<?php echo $to_job_detail->user_id; ?>" style="color:orange;" target="_blank"> کاربر </a>
    &nbsp;
    <a href="<?php echo get_admin_url(); ?>admin.php?page=job_edit&route_args%5B0%5D=<?php echo $to_job_detail->job_id; ?>" style="color:orange;" target="_blank"> آگهی </a>
    &nbsp;
    <?php
    echo "<br>";
    // foreach( $company as $company_detail) {
    //     if( $company_detail['id'] == $to_job_detail->company_id ) {
    //         echo "(شرکت: ";
    //         echo $company_detail["title"];
    //         echo " -> با آیدی " . $to_job_detail->company_id . ")";
    //     }
    // }
    echo "<hr>";

}