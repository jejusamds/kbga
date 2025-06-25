<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

$idx = isset($_GET['idx']) ? (int) $_GET['idx'] : 0;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

$sql = "SELECT * FROM df_site_application_registration WHERE idx = '{$idx}'";
$row = $db->row($sql);
if (!$row) {
    error('잘못된 접근입니다.', 'application_list.php');
    exit;
}

// 컬럼명 대신 화면에 표시할 한글 라벨 매핑
$labels = [
    'f_applicant_type'   => '신청자구분',
    'f_category'         => '자격분야',
    'f_item_idx'         => '자격종목',
    'f_schedule_idx'     => '시험일정',
    'f_user_name'        => '이름',
    'f_user_name_en'     => '영문이름',
    'f_tel'              => '연락처',
    'f_contact_phone'    => '담당자 연락처',
    'f_birth_date'       => '생년월일',
    'f_zip'              => '우편번호',
    'f_address1'         => '기본주소',
    'f_address2'         => '상세주소',
    'f_email'            => '이메일',
    'f_application_type' => '신청구분',
    'f_issue_desire'     => '발급희망 여부',
    'f_issue_file'       => '사진첨부',
    'f_payer_name'       => '입금자명',
    'f_payer_bank'       => '은행',
    'f_payment_category' => '입금 구분',
    'f_user_id'          => '회원ID',
    'reg_date'           => '등록일',
];
?>
<div class="pageWrap">
    <div class="page-heading">
        <h3>자격시험 신청 상세</h3>
        <ul class="breadcrumb">
            <li>신청관리</li>
            <li class="active">자격시험</li>
        </ul>
    </div>
    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <table class="table noMargin" cellpadding="0" cellspacing="0">
                <?php foreach ($row as $key => $val): ?>
                    <?php if ($key === 'idx') continue; ?>
                    <?php if (!isset($labels[$key])) continue; ?>
                    <tr>
                        <td style="width:200px;"><?= htmlspecialchars($labels[$key], ENT_QUOTES) ?></td>
                        <td><?= nl2br(htmlspecialchars($val, ENT_QUOTES)) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <div class="box comMTop20 comMBottom20" style="width:1114px;">
        <div class="comPTop20 comPBottom20">
            <div class="comFLeft comACenter" style="width:10%;">
                <button class="btn btn-primary btn-sm" type="button"
                    onclick="location.href='application_list.php?page=<?= $page ?>';">목록</button>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>

</html>
