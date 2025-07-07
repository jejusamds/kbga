<?php
include $_SERVER['DOCUMENT_ROOT'] . '/Madmin/inc/top.php';

$this_table = 'df_site_qualification';
$table = 'qualification';
$page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
$category = isset($_GET['category']) ? (int)$_GET['category'] : 1;

$page_set = 20;
$block_set = 10;

$total = $db->single("SELECT COUNT(*) FROM {$this_table} WHERE page_no=:c", ['c'=>$category]);
$pageCnt = (int)(($total - 1) / $page_set) + 1;
if ($page > $pageCnt) $page = $pageCnt > 0 ? $pageCnt : 1;

$list = [];
if ($total > 0) {
    $offset = ($page - 1) * $page_set;
    $sql = "SELECT * FROM {$this_table} WHERE page_no=:c ORDER BY idx DESC LIMIT {$offset}, {$page_set}";
    $list = $db->query($sql, ['c'=>$category]);
}
?>
<script>
function onSelectAll(allChk){
    document.querySelectorAll('.select_checkbox').forEach(ch=>ch.checked=allChk.checked);
}
function deleteEntries(){
    var arr=[];document.querySelectorAll('.select_checkbox:checked').forEach(ch=>arr.push(ch.value));
    if(arr.length===0){alert('삭제할 항목을 선택하세요.');return;}
    if(confirm('선택한 항목을 삭제하시겠습니까?')){
        location.href='/Madmin/qualification/exec.php?table=<?= $table ?>&mode=delete&selidx='+arr.join('|')+'&page=<?= $page ?>&category=<?= $category ?>';
    }
}
</script>
<div class="pageWrap">
    <div class="page-heading">
        <h3>민간자격 등록현황</h3>
        <ul class="breadcrumb">
            <li>민간자격 관리</li>
            <li class="active">목록</li>
        </ul>
    </div>
    <form method="get" style="margin-bottom:10px;">
        <select name="category" onchange="this.form.submit()" class="form-control" style="width:150px; display:inline-block;">
            <?php for($i=1;$i<=5;$i++): ?>
            <option value="<?= $i ?>" <?= $category==$i?'selected':'' ?>><?= $i ?>페이지</option>
            <?php endfor; ?>
        </select>
        <input type="hidden" name="page" value="<?= $page ?>">
    </form>
    <div class="box comMTop20" style="width:1114px;">
        <div class="panel">
            <table class="table" cellpadding="0" cellspacing="0">
                <colgroup>
                    <col width="40" />
                    <col width="60" />
                    <col width="80" />
                    <col width="200" />
                    <col width="120" />
                    <col width="160" />
                    <col width="160" />
                    <col width="120" />
                </colgroup>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select_all" onclick="onSelectAll(this)"></th>
                        <th>번호</th>
                        <th>페이지</th>
                        <th>자격명</th>
                        <th>자격구분</th>
                        <th>등록번호</th>
                        <th>자격관리기관</th>
                        <th>주무부처</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($total > 0): ?>
                    <?php foreach ($list as $i => $row): ?>
                    <tr>
                        <td><input type="checkbox" class="select_checkbox" value="<?= $row['idx'] ?>"></td>
                        <td><?= $total - ($page-1)*$page_set - $i ?></td>
                        <td><?= $row['page_no'] ?></td>
                        <td class="comALeft"><a href="qualification_input.php?mode=update&idx=<?= $row['idx'] ?>&page=<?= $page ?>&category=<?= $category ?>"><?= htmlspecialchars($row['f_name'],ENT_QUOTES) ?></a></td>
                        <td><?= htmlspecialchars($row['f_type'],ENT_QUOTES) ?></td>
                        <td><?= htmlspecialchars($row['f_reg_no'],ENT_QUOTES) ?></td>
                        <td><?= htmlspecialchars($row['f_manage_org'],ENT_QUOTES) ?></td>
                        <td><?= htmlspecialchars($row['f_ministry'],ENT_QUOTES) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="comACenter" height="50">등록된 데이터가 없습니다.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box comMTop20 comMBottom20" style="width:1114px;">
        <div class="comPTop20 comPBottom20">
            <div class="comFLeft comALeft" style="width:10%; padding-left:10px;">
                <button class="btn btn-danger btn-sm" type="button" onclick="deleteEntries();">삭제</button>
            </div>
            <div class="comFCenter comACenter" style="width:70%; display:inline-block;">
                <?php print_pagelist_admin($total,$page_set,$block_set,$page,'&category='.$category); ?>
            </div>
            <div class="comFRight comARight" style="width:15%; padding-right:10px;">
                <button class="btn btn-default btn-sm" type="button" onclick="location.href='qualification_input.php?page=<?= $page ?>&category=<?= $category ?>';">등록</button>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>
</html>
