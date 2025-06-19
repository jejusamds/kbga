<?php
include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

$table = 'df_site_application';
$mode  = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$page  = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;

// 공통 필드 목록
$fields = [
    'f_category',
    'f_year',
    'f_round',
    'f_registration_period_written',
    'f_exam_date_written',
    'f_pass_announce_written',
    'f_registration_period_practical',
    'f_exam_date_practical',
    'f_pass_announce_practical',
    'f_cert_application'
];

switch ($mode) {
    case 'insert':
        $cols = [];
        $vals = [];
        $params = [];
        foreach ($fields as $f) {
            $cols[] = $f;
            $vals[] = ':' . $f;
            $params[$f] = $_POST[$f] ?? '';
        }
        $sql = "INSERT INTO {$table} (" . implode(',', $cols) . ", wdate) " .
               "VALUES (" . implode(',', $vals) . ", NOW())";
        $db->query($sql, $params);
        complete('등록되었습니다.', "/Madmin/application/app_list.php?page={$page}");
        break;

    case 'update':
        $idx = isset($_POST['idx']) ? (int)$_POST['idx'] : 0;
        if ($idx <= 0) {
            error('잘못된 접근입니다.');
            exit;
        }
        $sets = [];
        $params = [];
        foreach ($fields as $f) {
            $sets[] = "$f = :$f";
            $params[$f] = $_POST[$f] ?? '';
        }
        $params['idx'] = $idx;
        $sql = "UPDATE {$table} SET " . implode(',', $sets) . " WHERE idx = :idx";
        $db->query($sql, $params);
        complete('수정되었습니다.', "/Madmin/application/app_list.php?page={$page}");
        break;

    case 'delete':
        $selidx = isset($_REQUEST['selidx']) ? $_REQUEST['selidx'] : '';
        $ids = array_filter(array_map('intval', explode('|', $selidx)));
        foreach ($ids as $id) {
            $db->query("DELETE FROM {$table} WHERE idx = :id", ['id' => $id]);
        }
        complete('삭제되었습니다.', "/Madmin/application/app_list.php?page={$page}");
        break;

    default:
        error('잘못된 모드입니다.');
        break;
}

