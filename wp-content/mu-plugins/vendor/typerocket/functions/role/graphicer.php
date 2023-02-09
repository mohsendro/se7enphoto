<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Role && Capability: graphicer - نقش و دسترسی گرافیست

tr_roles()->add(
    'graphicer', 
    ['read' => true],
    'گرافیست'
);