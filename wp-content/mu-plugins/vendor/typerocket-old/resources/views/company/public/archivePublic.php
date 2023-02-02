<?php

foreach ($company as $company_detail) {
    echo $company_detail['id'] . ' | ' . $company_detail['title'] . ' | ' . $company_detail['content'];
    echo '<a href="'. get_home_url() .'/company/'.$company_detail['id'].'"> نمایش </a>';
    echo "<br>";
}