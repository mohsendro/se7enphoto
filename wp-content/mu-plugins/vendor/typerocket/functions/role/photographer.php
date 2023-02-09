<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Role && Capability: photographer - نقش و دسترسی عکاس

tr_roles()->add(
    'photographer', 
    ['read' => true],
    'عکاس'
);