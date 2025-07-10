<?php
include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

function auto_filter_input(string $data)
{
    return SQL_Injection(RemoveXSS($data));
}

function return_json(array $ret)
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($ret);
    exit;
}

function upload_file(array $file, string $dir): array
{
    $orig = $file['name'];
    $tmp = $file['tmp_name'];
    $err = $file['error'];
    if ($err !== UPLOAD_ERR_OK) {
        return_json(['result' => 'error', 'msg' => '파일 업로드 중 오류가 발생했습니다.']);
    }
    $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $new = uniqid('', true) . '.' . $ext;
    $dest = $dir . '/' . $new;
    if (!move_uploaded_file($tmp, $dest)) {
        return_json(['result' => 'error', 'msg' => '파일 저장에 실패했습니다.']);
    }
    return ['saved' => $new, 'original' => $orig];
}

function upload_files(array $files, string $dir): array
{
    $saved = [];
    if (!isset($files['name']) || !is_array($files['name'])) {
        return $saved;
    }
    $cnt = count($files['name']);
    for ($i = 0; $i < $cnt; $i++) {
        if (empty($files['name'][$i])) {
            continue;
        }
        $info = [
            'name' => $files['name'][$i],
            'tmp_name' => $files['tmp_name'][$i] ?? '',
            'error' => $files['error'][$i] ?? UPLOAD_ERR_NO_FILE,
        ];
        $saved[] = upload_file($info, $dir);
    }
    return $saved;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return_json(['result' => 'error', 'msg' => '잘못된 요청입니다.']);
}

$mode = $_POST['mode'] ?? '';
$allowed = ['update_license', 'update_education', 'update_contest'];
if (!in_array($mode, $allowed, true)) {
    return_json(['result' => 'error', 'msg' => '잘못된 요청입니다.']);
}

$filtered = [];
foreach ($_POST as $k => $v) {
    $filtered[$k] = is_array($v) ? array_map('auto_filter_input', $v) : auto_filter_input($v);
}

if (empty($filtered['csrf_token']) || $filtered['csrf_token'] !== $_SESSION['csrf_token']) {
    return_json(['result' => 'error', 'msg' => '잘못된 접근입니다 (CSRF).']);
}

$idx = (int)($filtered['idx'] ?? 0);
if ($idx <= 0) {
    return_json(['result' => 'error', 'msg' => '잘못된 접근입니다.']);
}

switch ($mode) {
    case 'update_license':
        $table = 'df_site_application_registration';
        $dirName = 'registration';
        $hasName = true;
        break;
    case 'update_education':
        $table = 'df_site_edu_registration';
        $dirName = 'education';
        $hasName = true;
        break;
    case 'update_contest':
        $table = 'df_site_competition_registration';
        $dirName = 'competition';
        $hasName = false;
        break;
}

$user_id = $_SESSION['kbga_user_id'] ?? '';
$row = $db->row("SELECT f_issue_file" . ($hasName ? ', f_issue_file_name' : '') . " FROM {$table} WHERE idx=:idx AND f_user_id=:uid", ['idx' => $idx, 'uid' => $user_id]);
if (!$row) {
    return_json(['result' => 'error', 'msg' => '정보를 찾을 수 없습니다.']);
}

$currentFiles = $row['f_issue_file'] ? explode(',', $row['f_issue_file']) : [];
$currentNames = $hasName ? ($row['f_issue_file_name'] ? explode(',', $row['f_issue_file_name']) : []) : [];

$deleteArr = isset($filtered['delete_files']) ? array_filter(array_map('trim', explode(',', $filtered['delete_files']))) : [];
$dir = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/' . $dirName;

foreach ($deleteArr as $df) {
    $key = array_search($df, $currentFiles, true);
    if ($key !== false) {
        @unlink($dir . '/' . $currentFiles[$key]);
        unset($currentFiles[$key]);
        if ($hasName) {
            unset($currentNames[$key]);
        }
    }
}

$uploadSaved = [];
$uploadOriginal = [];
if (!empty($_FILES['upfile']['name'])) {
    $uploaded = upload_files($_FILES['upfile'], $dir);
    if ($uploaded) {
        $uploadSaved = array_column($uploaded, 'saved');
        $uploadOriginal = array_column($uploaded, 'original');
    }
}

$currentFiles = array_merge($currentFiles, $uploadSaved);
$currentNames = $hasName ? array_merge($currentNames, $uploadOriginal) : [];

$update = [
    'file' => $currentFiles ? implode(',', $currentFiles) : null,
    'name' => $hasName ? ($currentNames ? implode(',', $currentNames) : null) : null,
];

$params = ['file' => $update['file'], 'idx' => $idx, 'status' => 're'];
$sql = "UPDATE {$table} SET f_issue_file=:file,";
if ($hasName) {
    $sql .= " f_issue_file_name=:name,";
    $params['name'] = $update['name'];
}
$sql .= " f_applicant_status=:status WHERE idx=:idx";

$db->query($sql, $params);

return_json(['result' => 'ok', 'msg' => '수정되었습니다.', 'redirect' => '/mypage/history.html']);
