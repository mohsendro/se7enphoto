<?php

foreach ($company2 as $company_detail) {

    // echo $company_detail['id'] . ' | ' . $company_detail['title'] . ' | ' . $company_detail['content'];
    echo $company_detail->id . ' | ' . $company_detail->title . ' | ' . $company_detail->content;
    ?>
    <a href="<?php echo get_home_url(); ?>/company/<?php echo $company_detail->id; ?>" style="color:blue;"> نمایش </a>
    &nbsp;
    <a href="<?php echo get_admin_url(); ?>admin.php?page=company_edit&route_args%5B0%5D=<?php echo $company_detail->id; ?>" style="color:green;"> ویرایش </a>
    &nbsp;
    <a href="<?php echo get_admin_url(); ?>admin.php?page=company_delete&route_args%5B0%5D=<?php echo $company_detail->id; ?>" style="color:red;"> حذف </a>
    &nbsp;
    <?php
    // echo '<a href="http://localhost/irantax/wp-admin/admin.php?page=company_edit&post=' . $company_detail->id . '"> ویرایش </a>';
    // echo '<a href="http://localhost/irantax/wp-admin/admin.php?page=company_edit&route_args%5B0%5D=' . $company_detail->id . '"> ویرایش </a>';
    echo "<br>";
    echo "لیست آگهی های این شرکت:";
    echo "<br>";
    foreach( $job as $job_detail ) { 
        if( $job_detail->company_id == $company_detail->id ) {
            echo $job_detail->title . " | ";
        }
    }
    echo "<hr>";
    
}
