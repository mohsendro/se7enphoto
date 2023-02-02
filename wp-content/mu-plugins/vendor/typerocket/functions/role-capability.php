<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

tr_roles()->add(
    'photographer', 
    ['read' => true],
    'عکاس'
);

tr_roles()->add(
    'graphicer', 
    ['read' => true],
    'گرافیست'
);