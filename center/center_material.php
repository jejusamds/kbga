<?php
$ssMenu_num = '3';
include __DIR__.'/include/center_sub_common.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/header.html';

$list = $db->query("SELECT * FROM df_site_material WHERE f_category = :cat ORDER BY idx DESC", ['cat'=>$category]);
?>
<div id="container">
    <div id="sub_con" class="center_sub02">
        <?php include $_SERVER['DOCUMENT_ROOT'].'/include/sub_banner.html'; ?>
        <div class="contents_con">
            <div class="data_notice_con">
                <ul>
                    <?php if($list): foreach($list as $row): ?>
                    <li>
                        <div class="data_notice_div">
                            <div class="title_con">
                                <div class="bar"></div>
                                <span><?= htmlspecialchars($row['f_subject']) ?></span>
                            </div>
                            <div class="list_con">
                                <ul>
                                    <li>
                                        <div class="list_div">
                                            <table cellpadding="0" cellspacing="0">
                                                <tbody>
                                                    <tr>
                                                        <td align="center" class="category_td"><span><?= htmlspecialchars($row['f_type']) ?></span></td>
                                                        <td align="center" class="level_td"><span><?= htmlspecialchars($row['f_level']) ?></span></td>
                                                        <td align="left" class="text_td"><span><?= htmlspecialchars($row['f_description']) ?></span></td>
                                                        <td align="left" class="btn_td">
                                                            <?php if($row['f_file_name']): ?>
                                                            <a href="/userfiles/material/<?= htmlspecialchars($row['f_file']) ?>" class="a_btn" target="_blank">자료 다운로드</a>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; else: ?>
                    <li class="none_li"><span>등록된 게시글이 없습니다.</span></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php include $_SERVER['DOCUMENT_ROOT'].'/include/footer.html'; ?>
