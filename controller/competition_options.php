<?php
include $_SERVER['DOCUMENT_ROOT'].'/inc/global.inc';

$idx = isset($_GET['idx']) ? (int)$_GET['idx'] : 0;
header('Content-Type: application/json; charset=utf-8');
if ($idx <= 0) {
    echo json_encode(['part'=>[], 'field'=>[], 'event'=>[]]);
    exit;
}
$parts  = $db->query("SELECT f_part FROM df_site_competition_part WHERE competition_idx=:idx ORDER BY idx ASC", ['idx'=>$idx]);
$fields = $db->query("SELECT f_field FROM df_site_competition_field WHERE competition_idx=:idx ORDER BY idx ASC", ['idx'=>$idx]);
$events = $db->query("SELECT f_event FROM df_site_competition_event WHERE competition_idx=:idx ORDER BY idx ASC", ['idx'=>$idx]);
$ret = [
    'part'  => array_map(function($row){ return $row['f_part']; }, $parts),
    'field' => array_map(function($row){ return $row['f_field']; }, $fields),
    'event' => array_map(function($row){ return $row['f_event']; }, $events),
];
echo json_encode($ret);
