<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";

$idx = isset($_GET['idx']) ? (int) $_GET['idx'] : 0;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

$sql = "SELECT * FROM df_site_competition_registration WHERE idx = '{$idx}'";
$row = $db->row($sql);
if (!$row) {
    error('잘못된 접근입니다.', 'competition_list.php');
    exit;
}
?>
<div class="pageWrap">
    <div class="page-heading">
        <h3>대회 신청 상세</h3>
        <ul class="breadcrumb">
            <li>신청관리</li>
            <li class="active">대회</li>
        </ul>
    </div>
    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <table class="table noMargin" cellpadding="0" cellspacing="0">
                <?php foreach ($row as $key => $val): ?>
                    <tr>
                        <td style="width:200px;"><?= htmlspecialchars($key, ENT_QUOTES) ?></td>
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
                    onclick="location.href='competition_list.php?page=<?= $page ?>';">목록</button>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>

</html>
