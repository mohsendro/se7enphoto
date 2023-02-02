<?php

foreach ($resume as $resume_detail) {
    echo $resume_detail['id'] . ' | ' . $resume_detail['title'] . ' | ' . $resume_detail['content'] . ' | ' . '(کاربر ' . $resume_detail['user_id']. ')';

}

echo "<hr>";

echo "شناسه کاربری: " . $user->ID . "<br>";
echo "نام کاربری: " . $user->user_login . "<br>";
echo "نام نمایشی: " . $user->user_nicename . "<br>";
echo "ایمیل کاربری: " . $user->user_email . "<br>";
echo "آدرسی وب سایت: " . $user->user_url . "<br>";
$user = get_userdata($user->ID); 
foreach( $user->roles as $user_role ) {
    echo "سطح کاربری: " . $user_role . " ";
}

?>
