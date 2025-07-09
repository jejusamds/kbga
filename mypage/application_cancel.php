<?php
include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

$idx = isset($_POST['idx']) ? (int)$_POST['idx'] : 0;
if ($idx <= 0) {
    error('잘못된 접근입니다.');
}

$table = null;
$row = $db->row('SELECT f_applicant_status FROM df_site_application_registration WHERE idx=:idx', ['idx' => $idx]);
if ($row) {
    $table = 'df_site_application_registration';
} else {
    $row = $db->row('SELECT f_applicant_status FROM df_site_edu_registration WHERE idx=:idx', ['idx' => $idx]);
    if ($row) {
        $table = 'df_site_edu_registration';
    }
}

if (!$row) {
    error('신청 정보를 찾을 수 없습니다.');
}

if ($row['f_applicant_status'] === 'ing') {
    $db->query("UPDATE {$table} SET f_applicant_status='cancle' WHERE idx=:idx", ['idx' => $idx]);
    complete('신청취소가 완료되었습니다.', '/mypage/history.html');
} else {
    echo '<script>alert("관리자가 ing 제외한 상태로 이미 수정한 경우에는 취소가 불가능 합니다.");history.back();</script>';
}

