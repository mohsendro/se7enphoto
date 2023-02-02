<?php

foreach ($job as $job_detail) {
    echo $job_detail['id'] . ' | ' . $job_detail['title'] . ' | ' . $job_detail['content'] . ' | ' . '(شرکت ' . $job_detail['company_id']. ')';
    echo '<a href="'. get_home_url() .'/job/'.$job_detail['id'].'"> نمایش </a>';
    echo "<br>";
}

