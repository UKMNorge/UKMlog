<?php

use UKMNorge\Arrangement\Arrangement;

$arrangement = new Arrangement(get_option('pl_id'));

if( isset($_GET['b_id'])) {
    UKMlog::addViewData('searching', true);
    UKMlog::addViewData('element', $arrangement->getInnslag()->get( $_GET['b_id'], true ) );  
} else {
    UKMlog::addViewData('element', $arrangement);
}