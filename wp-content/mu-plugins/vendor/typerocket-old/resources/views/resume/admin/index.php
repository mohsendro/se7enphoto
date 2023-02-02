<?php

foreach ($resume as $resume_detail) {
    echo  $resume_detail->id . ' | ' . $resume_detail->title . ' | ' . $resume_detail->content;
    ?>
    <a href="<?php echo get_home_url(); ?>/resume/<?php echo $resume_detail->id; ?>" style="color:blue;"> نمایش </a>
    &nbsp;
    <a href="<?php echo get_admin_url(); ?>admin.php?page=resume_edit&route_args%5B0%5D=<?php echo $resume_detail->id; ?>" style="color:green;"> ویرایش </a>
    &nbsp;
    <a href="<?php echo get_admin_url(); ?>admin.php?page=resume_delete&route_args%5B0%5D=<?php echo $resume_detail->id; ?>" style="color:red;"> حذف </a>
    &nbsp;
    <a href="<?php echo get_admin_url(); ?>user-edit.php?user_id=<?php echo $resume_detail->user_id; ?>" style="color:orange;" target="_blank"> کاربر </a>
    &nbsp;
    <?php
    echo "<br>";//var_dump($user );
    foreach( $user as $user_detail) { 
        if( $user_detail['ID'] == $resume_detail->user_id ) {
            echo "(کاربر: ";
            echo $user_detail['user_login'];
            echo " -> با آیدی " . $resume_detail->user_id . ")";
        }
    }
    echo "<hr>";

}