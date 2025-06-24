<?php
include $_SERVER['DOCUMENT_ROOT'] . "/inc/global.inc";
include $_SERVER['DOCUMENT_ROOT'] . "/inc/util_lib.inc";
include $_SERVER['DOCUMENT_ROOT'] . "/inc/Eadmin_check.inc";
include $_SERVER['DOCUMENT_ROOT'] . "/inc/site_info.inc";

$gb = $_SERVER['REQUEST_URI'];
$gb = @str_replace("/Madmin/", "", $gb);
$gb = @str_replace("set/", "", $gb);
$gb = @str_replace("page/", "", $gb);
$gb = @str_replace("bbs/", "", $gb);
$gb = @str_replace("contents/", "", $gb);
$gb = @str_replace("delivery/", "", $gb);
$gb = @str_replace("marketing/", "", $gb);
$gb = @str_replace("main_manage/", "", $gb);
$gb = @str_replace("business/", "", $gb);
$gb = @str_replace("application/", "", $gb);
$gb = @str_replace("competition/", "", $gb);
$gb = @str_replace("material/", "", $gb);
$gb = @str_replace("agency/", "", $gb);

$gb = substr("$gb", 0, strpos($gb, ".php"));

$menu01 = array("page_privacy");

$menu02 = array("bbs_list", "bbs_input", "bbs_view");    // 문의
$menu03 = array(
    "contents_list",
    "contents_input",
    "contents_view",
    "category_list",
);

$menu04 = array(
    "page_popup",
    "popup_input",
    "mobile_popup",
    "mobile_popup_input",
    "banner_main",
    "banner_main_input"
);
$menu05 = array(
    "main_slide_list",
    "main_slide_input",
    "sub_slide_list",
    "sub_slide_input"
);
$menu06 = array(
    "sigong_list",
    "sigong_input",
    "sihang_list",
    "sihang_input",
);

$menu07 = array(
    "application_list",
    "application_input",
);
$menu09 = array(
    "material_list",
    "material_input",
);
$menu08 = array(
    "competition_list",
    "competition_input",
);

$menu10 = array(
    "agency_list",
    "agency_input",
);

$menu99 = array("stat_visit", "stat_url", "stat_url_view");    // 통계 현황

?>

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <link rel="shortcut icon" href="/img/favicon.ico" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title><?= $site_info['admin_title'] ?></title>

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="/Madmin/js/jquery.mCustomScrollbar.js"></script>
    <script language="javascript" type="text/javascript" src="/Madmin/js/jquery.sparkline.js"></script>
    <script src="/Madmin/js/jquery.nicescroll.js"></script>

    <link rel="stylesheet" href="/Madmin/css/jquery.mCustomScrollbar.css" />
    <link href="/Madmin/css/admin.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/Madmin/css/font-awesome.css">

    <link rel="stylesheet" type="text/css" href="/css/colorbox.css" />
    <script type="text/javascript" src="/js/jquery.colorbox.js"></script>
    <!--script type="text/javascript" src="/js/util_lib.js"></script-->

    <script>
        (function ($) {
            $(window).on("load resize", function () {
                $("#rightCont").css("height", $(window).height() + "px");
                $("#rightCont .gnb").css("width", ($(window).width() - 240) + "px");
            });
        })(jQuery);

        // 좌측 메뉴 DOWN / UP
        $(document).on("click", ".lnb-menu", function () {
            $this = $(this);
            // 다른메뉴 초기화
            $(".lnb-menu").not($this).each(function () {
                $(this).removeClass("on");
                $(this).removeClass("sel");
                $(".right i", $(this)).removeClass("fa-minus");
                $(".right i", $(this)).addClass("fa-plus");
                $(this).next(".lnb-submenu").slideUp("fast");
            });

            if ($(".right i", $this).hasClass("fa-plus")) {
                $this.addClass("sel");
                $(".right i", $this).removeClass("fa-plus");
                $(".right i", $this).addClass("fa-minus");
                $this.next(".lnb-submenu").slideDown("fast");
            } else {
                $this.removeClass("sel");
                $(".right i", $this).removeClass("fa-minus");
                $(".right i", $this).addClass("fa-plus");
                $this.next(".lnb-submenu").slideUp("fast");
            }
        });

        // 좌측 메뉴 클릭 (메뉴)
        $(document).on("click", "#leftMenu .lnb .lnb-menu.href", function () {
            location.href = $(this).attr("href");
        });
        // 좌측 메뉴 클릭 (서브메뉴)
        $(document).on("click", "#leftMenu .lnb .lnb-submenu .lnb-submenu-item", function () {
            location.href = $(this).attr("href");
        });

        // 상단 메뉴 클릭
        $(document).on("click", "#rightCont .gnb .gnb-menu .gnb-menu-list .gnb-menu-item", function () {
            if ($(this).attr("target") == "blank") window.open($(this).attr("href"));
            else location.href = $(this).attr("href");
        });

        $(document).ready(function () {
            // 좌측 메뉴 HOVER
            $(".lnb-menu").hover(
                function () {
                    // mouseenter
                    if (!$(this).hasClass("on")) {
                        $(this).addClass("sel");
                    }
                },
                function () {
                    // mouseleave
                    var $submenu = $(this).next(".lnb-submenu");
                    // 요소가 없거나 display:none 이면 sel 제거
                    if ($submenu.length === 0 || $submenu.is(":hidden")) {
                        $(this).removeClass("sel");
                    }
                }
            );

            // 좌측 서브메뉴 HOVER
            $(".lnb-submenu-item").hover(
                function () {
                    if (!$(this).hasClass("on")) {
                        $(this).addClass("sel");
                    }
                },
                function () {
                    $(this).removeClass("sel");
                }
            );

            // 상단 우측 메뉴
            $(".gnb-menu").hover(
                function () {
                    $(".gnb-menu-list", $(this)).stop().slideDown("fast");
                },
                function () {
                    $(".gnb-menu-list", $(this)).stop().slideUp("fast");
                }
            );
        });
    </script>

    <script>
        jQuery(function ($) {
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
                dateFormat: 'yy.mm.dd', // [mm/dd/yy], [yy-mm-dd], [d M, y], [DD, d MM]
                firstDay: 0,
                isRTL: false,
                showMonthAfterYear: true,
                yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['ko']);
            var dates = $("#date_from, #date_to, #adm_date, #start_date, #end_date").datepicker({
                'beforeShow': function (input, datepicker) {
                    setTimeout(function () {
                        $(datepicker.dpDiv).css('zIndex', 100);
                    }, 500);
                },
                onSelect: function (selectedDate) {
                    if (!$(this).hasClass("date_input")) {
                        var option = this.id == "date_from" ? "minDate" : "maxDate",
                            instance = $(this).data("datepicker"),
                            date = $.datepicker.parseDate(
                                instance.settings.dateFormat ||
                                $.datepicker._defaults.dateFormat,
                                selectedDate, instance.settings);
                    } else {
                        var option = this.id == 'start_date' ? "minDate" : "maxDate",
                            instance = $(this).data("datepicker"),
                            date = $.datepicker.parseDate(
                                instance.settings.dateFormat ||
                                $.datepicker._defaults.dateFormat,
                                selectedDate, instance.settings);
                    }


                    dates.not(this).datepicker("option", option, date);
                }
            });

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
            var dates = $("#f_date").datepicker({
                'beforeShow': function (input, datepicker) {
                    setTimeout(function () {
                        $(datepicker.dpDiv).css('zIndex', 100);
                    }, 500);
                },
                onSelect: function (selectedDate) {
                    if (!$(this).hasClass("date_input")) {
                        var option = this.id == "date_from" ? "minDate" : "maxDate",
                            instance = $(this).data("datepicker"),
                            date = $.datepicker.parseDate(
                                instance.settings.dateFormat ||
                                $.datepicker._defaults.dateFormat,
                                selectedDate, instance.settings);
                    } else {
                        var option = this.id == 'start_date' ? "minDate" : "maxDate",
                            instance = $(this).data("datepicker"),
                            date = $.datepicker.parseDate(
                                instance.settings.dateFormat ||
                                $.datepicker._defaults.dateFormat,
                                selectedDate, instance.settings);
                    }


                    dates.not(this).datepicker("option", option, date);
                }
            });
        });

        function setPeriod(date_from, date_to) {
            $("#date_from").val(date_from);
            $("#date_to").val(date_to);
        }

        // 숫자만 입력
        function onlyNumber(obj) {
            $(obj).keyup(function () {
                $(this).val($(this).val().replace(/[^0-9]/g, ""));
            });
        }

        // 전체 선택
        $(document).on('click', '#chkAll', function () {
            $this = $(this);
            $('input[name=chk]').each(function () {
                $(this).prop('checked', $this.prop('checked'));
            });
        });
    </script>
    <style>
        .logo {
            height: 40px;
            width: 100%;
            /* 부모의 너비 */
            display: flex;
            /* Flexbox로 정렬 */
            justify-content: center;
            /* 가로 중앙 정렬 */
            align-items: center;
            /* 세로 중앙 정렬 */
            overflow: hidden;
            /* 넘치는 부분 숨김 */
            position: relative;
            /* 자식의 위치 기준 설정 */
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .logo-img {
            height: 40px;
            /* 부모 높이를 넘지 않음 */
            max-width: 100%;
            /* 부모 너비를 넘지 않음 */
            object-fit: contain;
            /* 이미지 비율 유지하며 부모 안에 맞춤 */
            display: block;
            /* 불필요한 여백 제거 */
        }
    </style>
</head>

<body>

    <div id="leftMenu">
        <div class="logo" style="background: white;">
            <a href="/Madmin/">
                <img src="/Madmin/img/main_logo_beauty.svg" class="logo-img" />
            </a>
        </div>
        <div class="lnb">
            <div class="lnb-div userInfo">
                <span class="left">
                    <?= $_SESSION['admin_id'] ?>
                    <span>_ <?= $_SESSION['admin_name'] ?></span>
                </span>
                <span class="right">
                    <a href="/Madmin/admin_logout.php">
                        <i class="fa fa-sign-out fa-lg"></i>
                    </a>
                </span>
            </div>

            <div class="lnb-menu <? if (in_array($gb, $menu01)) { ?>on<? } ?>">
                <span class="left">
                    <i class="fa fa-user fa-lg"></i>
                    <span>개인정보 수집·이용</span>
                </span>
                <span class="right">
                    <i class="fa fa-<? if (in_array($gb, $menu01)) { ?>minus<? } else { ?>plus<? } ?>"></i>
                </span>
            </div>
            <div class="lnb-submenu"
                style="display:<? if (in_array($gb, $menu01)) { ?>block;<? } else { ?>none;<? } ?>">
                <div class="lnb-submenu-item <? if ($admin_type == "privacy") { ?>on<? } ?>"
                    href="/Madmin/page/page_privacy.php?admin_type=privacy">개인정보처리방침</div>
                <div class="lnb-submenu-item <? if ($admin_type == "use") { ?>on<? } ?>"
                    href="/Madmin/page/page_privacy.php?admin_type=use">이용약관</div>
                <div class="lnb-submenu-item <? if ($admin_type == "email") { ?>on<? } ?>"
                    href="/Madmin/page/page_privacy.php?admin_type=email">이메일무단수집거부</div>
            </div>


            <div class="lnb-menu <? if (in_array($gb, $menu04)) { ?>on<? } ?>">
                <span class="left">
                    <i class="fa fa-bullhorn fa-lg"></i>
                    <span>팝업 설정</span>
                </span>
                <span class="right">
                    <i class="fa fa-<? if (in_array($gb, $menu04)) { ?>minus<? } else { ?>plus<? } ?>"></i>
                </span>
            </div>
            <div class="lnb-submenu"
                style="display:<? if (in_array($gb, $menu04)) { ?>block;<? } else { ?>none;<? } ?>">
                <div class="lnb-submenu-item <? if ($gb == "page_popup" || $gb == "popup_input") { ?>on<? } ?>"
                    href="/Madmin/page/page_popup.php">팝업관리 - PC</div>
                <div class="lnb-submenu-item <? if ($gb == "mobile_popup" || $gb == "mobile_popup_input") { ?>on<? } ?>"
                    href="/Madmin/page/mobile_popup.php">팝업관리 - MOBILE</div>
            </div>


            <!-- <div class="lnb-menu <? if (in_array($gb, $menu05)) { ?>on<? } ?>">
                <span class="left">
                    <i class="fa fa-desktop fa-lg"></i>
                    <span>메인 관리</span>
                </span>
                <span class="right">
                    <i class="fa fa-<? if (in_array($gb, $menu05)) { ?>minus<? } else { ?>plus<? } ?>"></i>
                </span>
            </div>
            <div class="lnb-submenu" style="display:<? if (in_array($gb, $menu05)) { ?>block;<? } else { ?>none;<? } ?>">
                <div class="lnb-submenu-item <? if ($code == 'main' || $code == 'main') { ?>on<? } ?>" href="/Madmin/main_manage/main_slide_list.php?code=main">메인 슬라이드</div>
                <div class="lnb-submenu-item <? if ($code == 'best' || $code == 'best') { ?>on<? } ?>" href="/Madmin/main_manage/main_slide_list.php?code=best">베스트 제품 슬라이드</div>
            </div> -->


            <!-- <div class="lnb-menu <? if (in_array($gb, $menu03)) { ?>on<? } ?>">
                <span class="left">
                    <i class="fa fa-gift fa-lg"></i>
                    <span>제품 관리</span>
                </span>
                <span class="right">
                    <i class="fa fa-<? if (in_array($gb, $menu03)) { ?>minus<? } else { ?>plus<? } ?>"></i>
                </span>
            </div>
            <div class="lnb-submenu"
                style="display:<? if (in_array($gb, $menu03)) { ?>block;<? } else { ?>none;<? } ?>">
                <div class="lnb-submenu-item <? if (in_array($gb, $menu03_2)) { ?>on<? } ?>"
                    href="/Madmin/contents/category_list.php">카테고리 관리</div>
                <div class="lnb-submenu-item <? if (in_array($gb, $menu03_1)) { ?>on<? } ?>"
                    href="/Madmin/contents/contents_list.php">제품 관리</div>
            </div> -->


            <div class="lnb-menu <? if (in_array($gb, $menu10)) { ?>on<? } ?>">
                <span class="left">
                    <i class="fa fa-building-o fa-lg"></i>
                    <span>기관 관리</span>
                </span>
                <span class="right">
                    <i class="fa fa-<? if (in_array($gb, $menu10)) { ?>minus<? } else { ?>plus<? } ?>"></i>
                </span>
            </div>
            <div class="lnb-submenu"
                style="display:<? if (in_array($gb, $menu10)) { ?>block;<? } else { ?>none;<? } ?>">
                <div class="lnb-submenu-item <? if ($type == 'cooperate' && in_array($gb, $menu10)) { ?>on<? } ?>"
                    href="/Madmin/agency/agency_list.php?type=cooperate">협력기관</div>
                <div class="lnb-submenu-item <? if ($type == 'manage' && in_array($gb, $menu10)) { ?>on<? } ?>"
                    href="/Madmin/agency/agency_list.php?type=manage">관리기관</div>
            </div>

            <div class="lnb-menu <? if (in_array($gb, $menu07)) { ?>on<? } ?>">
                <span class="left">
                    <i class="fa fa-calendar fa-lg"></i>
                    <span>시험일정 관리</span>
                </span>
                <span class="right">
                    <i class="fa fa-<? if (in_array($gb, $menu07)) { ?>minus<? } else { ?>plus<? } ?>"></i>
                </span>
            </div>
            <div class="lnb-submenu"
                style="display:<? if (in_array($gb, $menu07)) { ?>block;<? } else { ?>none;<? } ?>">
                <div class="lnb-submenu-item <? if ($category == 'makeup') { ?>on<? } ?>"
                    href="/Madmin/application/application_list.php?category=makeup">시험일정 관리 [메이크업]</div>
                <div class="lnb-submenu-item <? if ($category == 'nail') { ?>on<? } ?>"
                    href="/Madmin/application/application_list.php?category=nail">시험일정 관리 [네일]</div>
                <div class="lnb-submenu-item <? if ($category == 'hair') { ?>on<? } ?>"
                    href="/Madmin/application/application_list.php?category=hair">시험일정 관리 [헤어]</div>
                <div class="lnb-submenu-item <? if ($category == 'skin') { ?>on<? } ?>"
                    href="/Madmin/application/application_list.php?category=skin">시험일정 관리 [피부]</div>
                <div class="lnb-submenu-item <? if ($category == 'half') { ?>on<? } ?>"
                    href="/Madmin/application/application_list.php?category=half">시험일정 관리 [반영구]</div>
                <div class="lnb-submenu-item <? if ($category == 'foreign') { ?>on<? } ?>"
                    href="/Madmin/application/application_list.php?category=foreign">시험일정 관리 [해외인증]</div>
                <div class="lnb-submenu-item <? if ($category == 'teacher') { ?>on<? } ?>"
                    href="/Madmin/application/application_list.php?category=teacher">시험일정 관리 [강사인증]</div>
            </div>

            <div class="lnb-menu <? if (in_array($gb, $menu09)) { ?>on<? } ?>">
                <span class="left">
                    <i class="fa fa-file-text-o fa-lg"></i>
                    <span>필/실기 자료 관리</span>
                </span>
                <span class="right">
                    <i class="fa fa-<? if (in_array($gb, $menu09)) { ?>minus<? } else { ?>plus<? } ?>"></i>
                </span>
            </div>
            <div class="lnb-submenu"
                style="display:<? if (in_array($gb, $menu09)) { ?>block;<? } else { ?>none;<? } ?>">
                <div class="lnb-submenu-item <? if ($category == 'makeup' && in_array($gb, $menu09)) { ?>on<? } ?>"
                    href="/Madmin/material/material_list.php?category=makeup">필/실기 자료 [메이크업]</div>
                <div class="lnb-submenu-item <? if ($category == 'nail' && in_array($gb, $menu09)) { ?>on<? } ?>"
                    href="/Madmin/material/material_list.php?category=nail">필/실기 자료 [네일]</div>
                <div class="lnb-submenu-item <? if ($category == 'hair' && in_array($gb, $menu09)) { ?>on<? } ?>"
                    href="/Madmin/material/material_list.php?category=hair">필/실기 자료 [헤어]</div>
                <div class="lnb-submenu-item <? if ($category == 'skin' && in_array($gb, $menu09)) { ?>on<? } ?>"
                    href="/Madmin/material/material_list.php?category=skin">필/실기 자료 [피부]</div>
                <div class="lnb-submenu-item <? if ($category == 'half' && in_array($gb, $menu09)) { ?>on<? } ?>"
                    href="/Madmin/material/material_list.php?category=half">필/실기 자료 [반영구]</div>
                <div class="lnb-submenu-item <? if ($category == 'foreign' && in_array($gb, $menu09)) { ?>on<? } ?>"
                    href="/Madmin/material/material_list.php?category=foreign">필/실기 자료 [해외인증]</div>
                <div class="lnb-submenu-item <? if ($category == 'teacher' && in_array($gb, $menu09)) { ?>on<? } ?>"
                    href="/Madmin/material/material_list.php?category=teacher">필/실기 자료 [강사인증]</div>
            </div>

            <div class="lnb-menu <? if (in_array($gb, $menu08)) { ?>on<? } ?>">
                <span class="left">
                    <i class="fa fa-trophy fa-lg"></i>
                    <span>대회 관리</span>
                </span>
                <span class="right">
                    <i class="fa fa-<? if (in_array($gb, $menu08)) { ?>minus<? } else { ?>plus<? } ?>"></i>
                </span>
            </div>
            <div class="lnb-submenu"
                style="display:<? if (in_array($gb, $menu08)) { ?>block;<? } else { ?>none;<? } ?>">
                <div class="lnb-submenu-item <? if (in_array($gb, $menu08)) { ?>on<? } ?>"
                    href="/Madmin/competition/competition_list.php">대회 관리</div>
            </div>


            <div class="lnb-menu <? if (in_array($gb, $menu02)) { ?>on<? } ?>">
                <span class="left">
                    <i class="fa fa-comments fa-lg"></i>
                    <span>게시판 관리</span>
                </span>
                <span class="right">
                    <i class="fa fa-<? if (in_array($gb, $menu02)) { ?>minus<? } else { ?>plus<? } ?>"></i>
                </span>
            </div>
            <div class="lnb-submenu"
                style="display:<? if (in_array($gb, $menu02)) { ?>block;<? } else { ?>none;<? } ?>">
                <?php
                $sql = "select * from df_site_bbsinfo order by bbs_order";
                $list = $db->query($sql);
                foreach ($list as $row) {
                    ?>
                    <div class="lnb-submenu-item <? if ($code == $row['code']) { ?>on<? } ?>"
                        href="/Madmin/bbs/bbs_list.php?code=<?= $row['code'] ?>"><?= $row['bbs_category'] ?> -
                        <?= $row['title'] ?>
                    </div>
                <?php } ?>
            </div>


            <div class="lnb-menu <? if (in_array($gb, $menu99)) { ?>on<? } ?>">
                <span class="left">
                    <i class="fa fa-bar-chart fa-lg"></i>
                    <span>접속 현황</span>
                </span>
                <span class="right">
                    <i class="fa fa-<? if (in_array($gb, $menu99)) { ?>minus<? } else { ?>plus<? } ?>"></i>
                </span>
            </div>
            <div class="lnb-submenu"
                style="display:<? if (in_array($gb, $menu99)) { ?>block;<? } else { ?>none;<? } ?>">
                <div class="lnb-submenu-item <? if ($gb == "stat_visit") { ?>on<? } ?>"
                    href="/Madmin/marketing/stat_visit.php">접속 통계</div>
                <div class="lnb-submenu-item <? if ($gb == "stat_url" || $gb == "stat_url_view") { ?>on<? } ?>"
                    href="/Madmin/marketing/stat_url.php">유입 경로</div>
            </div>

            <div class="clear"></div>
        </div>
    </div>

    <div id="rightCont">
        <div class="gnb">

            <div class="gnb-menu">
                <a href="/Madmin/admin_logout.php">
                    <i class="fa fa-sign-out fa-lg"></i>
                    <span>로그아웃</span>
                </a>
            </div>

            <?php if ($_SESSION['admin_part'] == "0") { ?>
                <div class="gnb-menu">
                    <i class="fa fa-cog fa-lg"></i>
                    <span>관리자설정</span>
                    <i class="fa fa-caret-down fa-lg"></i>

                    <div class="gnb-menu-list">
                        <div class="gnb-menu-item" href="/Madmin/set/set_admin.php" target="">
                            <span class="square">■</span>
                            <span>관리자 설정</span>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>
        <div class="clear"></div>

        <div id="pageContainer">
