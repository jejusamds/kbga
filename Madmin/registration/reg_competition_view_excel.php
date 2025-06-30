<?php
include "../../inc/global.inc";
include "../../inc/util_lib.inc";

$idx = isset($_GET['idx']) ? (int)$_GET['idx'] : 0;

$sql = "SELECT * FROM df_site_competition_registration WHERE idx = '{$idx}'";
$row = $db->row($sql);
if (!$row) {
    die('잘못된 접근입니다.');
}

function printValue($val)
{
    return nl2br(safeAdminOutput($val));
}

function printType($val)
{
    switch ($val) {
        case 'P':
            return '개인';
        case 'O':
            return '단체';
        default:
            return safeAdminOutput($val);
    }
}

$filename = iconv('UTF-8', 'EUC-KR', "대회신청_상세_{$idx}.xls");

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename={$filename}");
header("Content-Description: PHP Generated Data");

echo "<table border='1'>";

$rows = [
    '신청자구분' => printType($row['f_applicant_type']),
    '대회구분'   => printValue($row['f_competition_idx']),
    '참가부문'   => printValue($row['f_part']),
    '종목분야'   => printValue($row['f_field']),
    '참가종목'   => printValue($row['f_event']),
    '이름'       => printValue($row['f_user_name']),
    '성별'       => printValue($row['f_gender']),
    '영문이름'   => printValue($row['f_user_name_en']),
    '생년월일'   => printValue($row['f_birth_date']),
    '연락처'     => printValue($row['f_tel']),
    '이메일'     => printValue($row['f_email']),
    '우편번호'   => printValue($row['f_zip']),
    '기본주소'   => printValue($row['f_address1']),
    '상세주소'   => printValue($row['f_address2']),
    '입금자명'   => printValue($row['f_payer_name']),
    '은행'       => printValue($row['f_payer_bank']),
    '입금 구분'  => printValue($row['f_payment_category']),
    '회원ID'     => printValue($row['f_user_id']),
    '등록일'     => printValue($row['reg_date'])
];

foreach ($rows as $label => $value) {
    echo "<tr><td>{$label}</td><td>{$value}</td></tr>";
}

echo "</table>";
?>
