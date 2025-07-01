<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page_set = 15;
$block_set = 10;

$sql = "SELECT COUNT(*) FROM df_site_application_registration";
$total = $db->single($sql);

$pageCnt = (int) (($total - 1) / $page_set) + 1;
if ($page > $pageCnt) {
    $page = 1;
}

$list = [];
if ($total > 0) {
    $offset = ($page - 1) * $page_set;
    $sql = "SELECT * FROM df_site_application_registration ORDER BY idx DESC LIMIT {$offset}, {$page_set}";
    $list = $db->query($sql);
}

$category_map = [
    'makeup' => '메이크업',
    'nail' => '네일',
    'hair' => '헤어',
    'skin' => '피부',
    'half' => '반영구',
    'foreign' => '해외인증',
    'teacher' => '강사인증',
];

$application_type_map = [
    'exam' => '시헙 접수',
    'cert' => '자격증 발급',
];
?>
<style>
    .pagination {
        margin: 0 auto;
    }
</style>

<div class="pageWrap">
    <div class="page-heading">
        <h3>자격시험 신청 내역</h3>
        <ul class="breadcrumb">
            <li>신청관리</li>
            <li class="active">자격시험</li>
        </ul>
    </div>

    <table class="comMTop20" cellpadding="0" cellspacing="0" style="width:1114px;">
        <tr>
            <td width="5"></td>
            <td colspan="6" align="right">
                <button class="btn btn-success btn-xs" type="button" onclick="location.href='reg_application_excel.php?<?= $param ?>'">엑셀파일저장</button>
            </td>
            <td width="5"></td>
        </tr>
    </table>

    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <table class="table" cellpadding="0" cellspacing="0">
                <col width="60" />
                <col width="150" />
                <col width="150" />
                <col width="150" />
                <col width="200" />
                <col width="200" />
                <thead>
                    <tr>
                        <td>번호</td>
                        <td>분야</td>
                        <td>이름</td>
                        <td>신청구분</td>
                        <td>연락처</td>
                        <td>이메일</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total > 0): ?>
                        <?php foreach ($list as $i => $row): ?>
                            <tr>
                                <td><?= $total - ($page - 1) * $page_set - $i ?></td>
                                <td><?= htmlspecialchars($category_map[$row['f_category']], ENT_QUOTES) ?></td>
                                <td><a
                                        href="reg_application_view.php?idx=<?= $row['idx'] ?>&page=<?= $page ?>"><?= htmlspecialchars($row['f_user_name'], ENT_QUOTES) ?></a>
                                </td>
                                <td><?= htmlspecialchars($application_type_map[$row['f_application_type']], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($row['f_tel'], ENT_QUOTES) ?></td>
                                <td><?= htmlspecialchars($row['f_email'], ENT_QUOTES) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" align="center">등록된 데이터가 없습니다.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box comMTop20 comMBottom20" style="width:1114px;">
        <div class="comPTop20 comPBottom20">
            <div class="comFCenter comACenter" style="width:100%; display:inline-block;">
                <?php print_pagelist_admin($total, $page_set, $block_set, $page, ""); ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>

</html>
