<?php

foreach ($company as $company_detail) {
    echo $company_detail['id'] . ' | ' . $company_detail['title'] . ' | ' . $company_detail['content'];
}