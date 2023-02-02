<?php

foreach ($job as $job_detail) {
    echo  $job_detail->id . ' | ' . $job_detail->title . ' | ' . $job_detail->content;
    ?>
    <a href="<?php echo get_home_url(); ?>/job/<?php echo $job_detail->id; ?>" style="color:blue;"> نمایش </a>
    &nbsp;
    <a href="<?php echo get_admin_url(); ?>admin.php?page=job_edit&route_args%5B0%5D=<?php echo $job_detail->id; ?>" style="color:green;"> ویرایش </a>
    &nbsp;
    <a href="<?php echo get_admin_url(); ?>admin.php?page=job_delete&route_args%5B0%5D=<?php echo $job_detail->id; ?>" style="color:red;"> حذف </a>
    &nbsp;
    <a href="<?php echo get_admin_url(); ?>admin.php?page=company_edit&route_args%5B0%5D=<?php echo $job_detail->company_id; ?>" style="color:orange;" target="_blank"> شرکت </a>
    &nbsp;
    <?php
    echo "<br>";
    foreach( $company as $company_detail) {
        if( $company_detail['id'] == $job_detail->company_id ) {
            echo "(شرکت: ";
            echo $company_detail["title"];
            echo " -> با آیدی " . $job_detail->company_id . ")";
        }
    }
    echo "<hr>";

}