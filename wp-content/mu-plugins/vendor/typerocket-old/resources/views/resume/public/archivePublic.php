<?php

foreach ($resume as $resume_detail) {
    echo $resume_detail['id'] . ' | ' . $resume_detail['title'] . ' | ' . $resume_detail['content'] . ' | ' . '(کاربر ' . $resume_detail['user_id']. ')';
    echo '<a href="'. get_home_url() .'/resume/'.$resume_detail['id'].'"> نمایش </a>';
    echo "<br>";
}

