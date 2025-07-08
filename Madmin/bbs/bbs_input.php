<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php";
include $_SERVER['DOCUMENT_ROOT'] . "/inc/bbs_info.inc";

$param = "code=" . $code . "&searchgrp=" . $searchgrp . "&search_option=" . $search_option . "&keyword=" . $keyword;
$reply_mode = false;

if ($mode == "insert" || $mode == "") {
    $mode = "insert";
    $bbs_row['memid'] = $_SESSION['admin_id'];
    $bbs_row['name'] = $_SESSION['admin_name'];
    $bbs_row['email'] = $_SESSION['email'];
    $bbs_row['wdate'] = date('Y-m-d H:i:s');
    $bbs_row['passwd'] = date('is');
    $bbs_row['count'] = 0;
    $bbs_row['ctype'] = "H";
    if ($code == 'education_news') {
        $edu_types = [];
    }
} else if ($mode == "update") {
    $sql = "select * from df_site_bbs where code = '$code' and idx='$idx'";
    $bbs_row = $db->row($sql);

    if ($bbs_row['parno']) {
        $reply_mode = true;
        $parno_ = $bbs_row['parno'];
        $sql_r = "Select * From df_site_bbs Where code = '$code' And idx = '$parno_'  ";
        $reply_row = $db->row($sql_r);
    }

    if ($code == 'education_news') {
        $edu_types = $db->query(
            "SELECT f_type FROM df_site_education_type WHERE news_idx=:idx ORDER BY idx ASC",
            ['idx' => $idx]
        );
    }
} else if ($mode == "reply") {
    $sql = "select grp, subject, content, privacy, passwd from df_site_bbs where code = '$code' and idx='$idx'";
    $bbs_row = $db->row($sql);

    $bbs_row['memid'] = $_SESSION['admin_id'];
    $bbs_row['name'] = $_SESSION['admin_name'];
    $bbs_row['email'] = $_SESSION['email'];
    $bbs_row['wdate'] = date('Y-m-d H:i:s');
    $bbs_row['count'] = 0;
    $bbs_row['ctype'] = "H";
}
?>
<script src="/ckeditor/ckeditor.js"></script>
<script language="JavaScript">
    <!--
    // 이벤트 기간
    jQuery(function($) {
        $.datepicker.regional['ko'] = {
            closeText: '닫기',
            prevText: '이전달',
            nextText: '다음달',
            currentText: '오늘',
            monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
            monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
            dayNames: ['일', '월', '화', '수', '목', '금', '토'],
            dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
            dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
            weekHeader: 'Wk',
            dateFormat: 'yy-mm-dd', // [mm/dd/yy], [yy-mm-dd], [d M, y], [DD, d MM]
            firstDay: 0,
            isRTL: false,
            showMonthAfterYear: true,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['ko']);
        var dates = $("#event_sdate, #event_edate").datepicker({
            'beforeShow': function(input, datepicker) {
                setTimeout(function() {
                    $(datepicker.dpDiv).css('zIndex', 100);
                }, 500);
            },
            onSelect: function(selectedDate) {
                var option = this.id == "event_sdate" ? "minDate" : "maxDate",
                    instance = $(this).data("datepicker"),
                    date = $.datepicker.parseDate(
                        instance.settings.dateFormat ||
                        $.datepicker._defaults.dateFormat,
                        selectedDate, instance.settings);

                dates.not(this).datepicker("option", option, date);
            }
        });
    });
    $(document).ready(function() { // 게시글 보기 등급 전체 선택
        $("input[type=checkbox][name=allchk]").click(function() {
            $("input[type=checkbox][class=chk]").prop("checked", $(this).prop("checked"));
        });
    });


    // 첨부파일 추가
    $(document).on('click', '.btnAddFiles', function() {
        var html = '';
        html += '<tr>\n';
        html += '<th><button class="btn btn-warning btn-xs comMLeft15 btnDelFiles" type="button">파일삭제</button></th>\n';
        html += '<td class="comALeft" colspan="3">\n';
        html += '<input type="hidden" name="old_idx[]" value="" />\n';
        html += '<input name="upfile[]" type="file" class="form-control" style="width:50%; margin-right:15px;">\n';
        html += '</td>\n';
        html += '</tr>\n';

        $('#tableFiles').append(html);
    });


    // 첨부파일 삭제
    $(document).on('click', '.btnDelFiles', function() {
        var $this = $(this);
        var old_idx = $this.closest('tr').find('input[name="old_idx[]"]').val();
        if (old_idx != '') {
            if (!confirm('파일삭제는 즉시 이루어 집니다. 삭제하시겠습니까?')) return;
            var params = 'mode=delimg&idx=' + old_idx + "&code=<?= $code ?>";
    $.ajax({
        type: 'post',
        url: 'bbs_save.php',
        data: params,
        dataType: 'html',
        success: function (res) {
            //console.log(res);
            if (res.trim() != 'Y') {
                alert('파일 삭제에 실패했습니다.');
                return;
            } else {
                $this.closest('tr').remove();
            }
        },
        error: function (e) {
            console.log(e);
            alert(e.responseText);
        }
    });
        } else {
        $this.closest('tr').remove();
    }
    });

    $(document).on('click', '.btnDelFiles_thumb', function () {
        var $this = $(this);
        var old_idx = $this.closest('tr').find('input[name="old_idx_thumb"]').val();
        if (old_idx != '') {
            if (!confirm('썸네일 삭제는 즉시 이루어 집니다. 삭제하시겠습니까?')) return;
            var params = 'mode=delimg_thumb&idx=' + old_idx;
            $.ajax({
                type: 'post',
                url: 'bbs_save.php',
                data: params,
                dataType: 'html',
                success: function (res) {
                    console.log(res);
                    if (res.trim() != 'Y') {
                        alert('파일 삭제에 실패했습니다.');
                        return;
                    } else {
                        $this.closest('tr').remove();
                    }
                },
                error: function (e) {
                    console.log(e);
                    alert(e.responseText);
                }
            });
        } else {
            $this.closest('tr').remove();
        }
    });

    $(document).on('click', '.btnAddEduType', function () {
        $('#tableEduType tbody').append('<tr><td class="comALeft"><input type="text" name="edu_types[]" class="form-control" style="width:60%;"></td><td><button class="btn btn-warning btn-xs btnDelEduType" type="button">삭제</button></td></tr>');
    });
    $(document).on('click', '.btnDelEduType', function () { $(this).closest('tr').remove(); });

    function inputCheck(frm) {
        if (frm.name.value == "") {
            alert("이름을 입력하세요.");
            frm.name.focus();
            return false;
        }
        if (frm.subject.value == "") {
            alert("제목을 입력하세요.");
            frm.subject.focus();
            return false;
        }
        /*
        if(frm.passwd.value == ""){
            alert("비밀번호를 입력하세요.");
            frm.passwd.focus();
            return false;
        }
        */
        <? if ($code == "news") { ?>
            if (frm.event_sdate.value == "") {
                alert("이벤트 기간을 선택하세요.");
                frm.event_sdate.focus();
                return false;
            }
            if (frm.event_edate.value == "") {
                alert("이벤트 기간을 선택하세요.");
                frm.event_edate.focus();
                return false;
            }
        <? } ?>
        <? if ($code == "review") { ?>
            if (frm.prdcode.value == "") {
                alert("상품을 선택하세요.");
                frm.prdcode.focus();
                return false;
            }
        <? } ?>
    }

    function period() {
        var arrayClass = eval("document.frm.grp");

        var selidx = arrayClass.selectedIndex;
        var selvalue = arrayClass.options[selidx].value;
        console.log(selvalue);
        if (selvalue == "예약상품") {
            $("#period").html("게시글기간");
            $(".rpermi_tr").show();
        } else {
            $("#period").html("이벤트기간");
            $(".rpermi_tr").hide();
        }

    }

    //
    -->
</script>

<div class="pageWrap">
    <div class="page-heading">
        <h3>
            <?= $bbs_info['bbs_category'] ?> - <?= $bbs_info['title'] ?>
        </h3>
        <ul class="breadcrumb">
            <li>게시판 관리</li>
            <li class="active"><?= $bbs_info['title'] ?></li>
        </ul>
    </div>

    <form name="frm" action="bbs_save.php?<?= $param ?>" method="post" enctype="multipart/form-data"
        onSubmit="return inputCheck(this)">
        <input type="hidden" name="mode" value="<?= $mode ?>">
        <input type="hidden" name="idx" value="<?= $idx ?>">
        <input type="hidden" name="page" value="<?= $page ?>">
        <input type="hidden" name="ctype" value="<?= $bbs_row['ctype'] ?>">


        <?php if ($code == 'education_news') : ?>
        <div class="box comMTop20" style="width:978px;">
            <div class="panel">
                <div class="title">
                    <i class="fa fa-shopping-cart"></i>
                    <span>교육구분</span>
                    <button class="btn btn-success btn-xs comMLeft15 btnAddEduType" type="button">항목추가</button>
                </div>
                <table id="tableEduType" class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="85%">
                    <col width="15%">
                    <tbody>
                        <?php if ($mode == 'insert' && empty($edu_types)): ?>
                            <tr>
                                <td class="comALeft"><input type="text" name="edu_types[]" class="form-control" style="width:60%;"></td>
                                <td><button class="btn btn-warning btn-xs btnDelEduType" type="button">삭제</button></td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($edu_types as $t): ?>
                            <tr>
                                <td class="comALeft"><input type="text" name="edu_types[]"
                                        value="<?= htmlspecialchars($t['f_type'], ENT_QUOTES) ?>" class="form-control"
                                        style="width:60%;"></td>
                                <td><button class="btn btn-warning btn-xs btnDelEduType" type="button">삭제</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <div class="box comMTop20" style="width:978px;">
            <div class="panel">
                <div class="title">
                    <i class="fa fa-shopping-cart"></i>
                    <span>게시물 작성</span>
                </div>
                <table class="table orderInfo" cellpadding="0" cellspacing="0">
                    <col width="15%" />
                    <col width="35%" />
                    <col width="15%" />
                    <col width="35%" />
                    <tr>
                        <th>작성자 ID/이름</th>
                        <td class="comALeft">
                            <input type="text" name="memid" value="<?= $bbs_row['memid'] ?>" class="form-control"
                                style="width:39%;" />
                            <input type="text" name="name" value="<?= $bbs_row['name'] ?>" class="form-control"
                                style="width:39%;" />
                        </td>
                        <th>작성일</th>
                        <td class="comALeft"><input type="text" name="wdate" value="<?= $bbs_row['wdate'] ?>"
                                class="form-control" style="width:88%;" /></td>
                    </tr>
                    <tr>
                        <!-- <th>조회수</th>
                        <td class="comALeft"><input type="text" name="count" value="<?= $bbs_row['count'] ?>"
                                class="form-control" style="width:88%;" /></td> -->
                    </tr>
                    <?php if ($code != 'faq') { ?>
                        <?php if ($bbs_info['upfile'] == 'Y') { ?>
                            <tr>
                                <th>썸네일 <br />(000 x 000 px)</th>
                                <td class="comALeft" colspan="3">
                                    <input type="file" name="upfile_thumb" class="form-control" style="width:50%;" />
                                    <?php if ($bbs_row['upfile'] != "") { ?>
                                        <div class="thumb_del_wrap" style="display: inline-block; margin-left:10px;">
                                            <a href="/userfiles/<?= $code ?>/<?= $bbs_row['upfile'] ?>" target="_blank">
                                                <img src="/userfiles/<?= $code ?>/<?= $bbs_row['upfile'] ?>" height="32"
                                                    align="absmiddle" id="upfile_prev_img" />
                                            </a>
                                            <button class="btn btn-warning btn-xs comMLeft15 btnDelFiles_thumb" type="button"
                                                style="margin-left:10px;">썸네일 삭제</button>
                                            <input type="hidden" name="old_idx_thumb" value="<?= $bbs_row['idx'] ?>" />
                                        </div>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    <tr>
                        <th><?= $code == 'education_news' ? '명칭' : '제 목' ?></th>
                        <td class="comALeft" colspan="3">
                            <?php
                            if ($bbs_info['grp'] != "") {
                                $catlist = explode(",", $bbs_info['grp']);
                                ?>
                                <select name="grp" class="form-control" style="width:auto;">
                                    <!-- <option value="">분류</option> -->
                                    <?
                                    for ($ii = 0; $ii < count($catlist); $ii++) {
                                        if ($bbs_row['grp'] == $catlist[$ii])
                                            $selected = "selected";
                                        else
                                            $selected = "";
                                        ?>
                                        <option value="<?= $catlist[$ii] ?>" <?= $selected ?>><?= $catlist[$ii] ?></option>
                                        <?
                                    }
                                    ?>
                                </select>
                                <?
                            }
                            ?>
                            <input type="text" name="subject" value="<?= $bbs_row['subject'] ?>" class="form-control"
                                style="width:60%;" />
                            <!-- <label style="margin-left:15px;">
                                    <input type="checkbox" name="notice" value="Y" <? if ($bbs_row['notice'] == "Y")
                                        echo "checked"; ?>> 공지글
                                </label> -->
                            <!--<label style="margin-left:15px;">
                                <input type="checkbox" name="privacy" value="Y" <? if ($bbs_row['privacy'] == "Y" || ($mode != "update" && $bbs_info['privacy'] == "Y"))
                                    echo "checked"; ?>> 비밀글
                            </label-->
                        </td>
                    </tr>

                    <?php if ($code == 'c_media_gallery') { ?>
                        <tr>
                            <th>영상주소</th>
                            <td class="comALeft" colspan="3">
                                <input type="text" name="media_url" value="<?= $bbs_row['media_url'] ?>"
                                    class="form-control" style="width:88%;" />
                            </td>
                        </tr>
                    <?php } ?>

                    <?php if ($code == 'education_news') { ?>
                        <tr>
                            <th>장소</th>
                            <td class="comALeft" colspan="3">
                                <input type="text" name="center_name" value="<?= $bbs_row['center_name'] ?>"
                                    class="form-control" style="width:88%;" />
                            </td>
                        </tr>
                        <tr>
                            <th>일시</th>
                            <td class="comALeft" colspan="3">
                                <input type="text" name="app_date" value="<?= $bbs_row['app_date'] ?>" class="form-control"
                                    style="width:88%;" />
                            </td>
                        </tr>
                        <tr>
                            <th>참가대상</th>
                            <td class="comALeft" colspan="3">
                                <input type="text" name="app_target" value="<?= $bbs_row['app_target'] ?>"
                                    class="form-control" style="width:88%;" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="padding:0;">
                                <div class="panel" style="margin-top:10px;">
                                    <div class="title">
                                        <i class="fa fa-list"></i>
                                        <span>교육구분</span>
                                        <button class="btn btn-success btn-xs comMLeft15 btnAddEduType" type="button">항목추가</button>
                                    </div>
                                    <table id="tableEduType" class="table orderInfo" cellpadding="0" cellspacing="0">
                                        <col width="85%">
                                        <col width="15%">
                                        <tbody>
                                            <?php if ($mode == 'insert' && empty($edu_types)): ?>
                                                <tr>
                                                    <td class="comALeft"><input type="text" name="edu_types[]" class="form-control" style="width:60%;"></td>
                                                    <td><button class="btn btn-warning btn-xs btnDelEduType" type="button">삭제</button></td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php foreach ($edu_types as $t): ?>
                                                <tr>
                                                    <td class="comALeft"><input type="text" name="edu_types[]" value="<?= htmlspecialchars($t['f_type'], ENT_QUOTES) ?>" class="form-control" style="width:60%;"></td>
                                                    <td><button class="btn btn-warning btn-xs btnDelEduType" type="button">삭제</button></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>

                    <?php } ?>

                    <?php
                    if ($mode == "reply") {
                        ?>
                        <tr>
                            <th>문의내용</th>
                            <td class="comALeft" colspan="3" style="padding-top:7px;padding-bottom:7px;">
                                <? if ($bbs_row['ctype'] == "T") { ?>
                                    <?= nl2br($bbs_row['content']) ?>
                                <? } else { ?>
                                    <?= $bbs_row['content'] ?>
                                <? } ?>
                            </td>
                        </tr>
                        <?
                    } else if ($reply_mode) {
                        ?>
                            <tr>
                                <th>문의내용</th>
                                <td class="comALeft" colspan="3" style="padding-top:7px;padding-bottom:7px;">
                                <? if ($reply_row['ctype'] == "T") { ?>
                                    <?= nl2br($reply_row['content']) ?>
                                <? } else { ?>
                                    <?= $reply_row['content'] ?>
                                <? } ?>
                                </td>
                            </tr>
                        <?
                    }
                    ?>
                    <tr>
                        <th><? if ($code == "faq") { ?>Q.<? } else { ?>내 용<? } ?></th>
                        <td class="comALeft" colspan="3" style="padding-top: 7px; padding-bottom: 7px;">
                            <textarea name="content" id="content"
                                class="textarea"><? if ($mode != "reply" || $reply_mode == true) { ?><?= $bbs_row['content'] ?><? } else { ?><? } ?></textarea>
                            <script type="text/javascript">
                                //<![CDATA[
                                CKEDITOR.replace('content', {
                                    enterMode: '2',
                                    shiftEnterMode: '3',
                                    height: <?= $code == 'faq' ? 100 : 400 ?>,
                                    filebrowserImageUploadUrl: "/ckeditor/upload.php?type=Images"
                                });
                                //]]
                            </script>
                        </td>
                    </tr>
                    <?php if ($code == "faq") { ?>
                        <tr>
                            <th>A.</th>
                            <td class="comALeft" colspan="3" style="padding-top: 7px; padding-bottom: 7px;">
                                <textarea name="faq_a" id="faq_a"
                                    class="textarea"><? if ($mode != "reply" || $reply_mode == true) { ?><?= $bbs_row['faq_a'] ?><? } else { ?><? } ?></textarea>
                                <script type="text/javascript">
                                    //<![CDATA[
                                    CKEDITOR.replace('faq_a', {
                                        enterMode: '2',
                                        shiftEnterMode: '3',
                                        height: 100,
                                        filebrowserImageUploadUrl: "/ckeditor/upload.php?type=Images"
                                    });
                                    //]]
                                </script>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if ($code == "education_news") {
                        $str = "기간";
                        ?>
                        <tr>
                            <th id="period"><?= $str ?></th>
                            <td class="comALeft" colspan="3">
                                <input type="text" name="event_sdate" id="event_sdate"
                                    value="<?= $bbs_row['event_sdate'] ?>" class="form-control" style="width:15%;" />
                                ~
                                <input type="text" name="event_edate" id="event_edate"
                                    value="<?= $bbs_row['event_edate'] ?>" class="form-control" style="width:15%;" />
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>

        <? if (!$reply_mode) { ?>
            <div class="box comMTop20" style="width:978px;">
                <div class="panel">
                    <div class="title">
                        <i class="fa fa-shopping-cart"></i>
                        <span>첨부 파일</span>
                        <button class="btn btn-success btn-xs comMLeft15 btnAddFiles" type="button">파일추가</button>
                    </div>
                    <table id="tableFiles" class="table orderInfo" cellpadding="0" cellspacing="0">
                        <col width="15%" />
                        <col width="35%" />
                        <col width="15%" />
                        <col width="35%" />
                        <tbody>
                            <?
                            $sqlF = " Select * From df_site_bbs_files Where bbsidx='" . $idx . "' Order by idx Asc ";
                            $rsF = $db->query($sqlF);
                            for ($ii = 0; $ii < count($rsF); $ii++) {
                                ?>
                                <tr>
                                    <th><button class="btn btn-warning btn-xs comMLeft15 btnDelFiles"
                                            type="button">파일삭제</button></th>
                                    <td class="comALeft" colspan="3">
                                        <input type="hidden" name="old_idx[]" value="<?= $rsF[$ii]['idx'] ?>" />
                                        <input name="upfile[]" type="file" class="form-control"
                                            style="width:50%; margin-right:15px;">
                                        <? if ($rsF[$ii]['upfile'] != "") { ?>
                                            <a href="/userfiles/<?= $code ?>/<?= $rsF[$ii]['upfile'] ?>"
                                                target="_blank"><?= $rsF[$ii]['upfile_name'] ?></a>
                                        <? } ?>
                                    </td>
                                </tr>
                                <?
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <? } ?>

        <div class="box comMTop20 comMBottom20" style="width:978px;">
            <div class="comPTop20 comPBottom20">
                <div class="comFLeft comACenter" style="width:10%;">
                    <button class="btn btn-primary btn-sm" type="button"
                        onClick="location.href='bbs_list.php?code=<?= $code ?>';">목록</button>
                </div>
                <div class="comFRight comARight" style="width:85%; padding-right:20px;">
                    <button class="btn btn-info btn-sm" type="submit">확인</button>
                    <button class="btn btn-danger btn-sm" type="button"
                        onClick="location.href='bbs_list.php?code=<?= $code ?>';">취소</button>
                </div>
                <div class="clear"></div>
            </div>
        </div>

    </form>

</div>
</div>
</div>
</div>

</body>

</html>
