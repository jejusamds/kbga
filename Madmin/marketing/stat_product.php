<? include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php"; ?>

<?
if (!$syval) $syval = date("Y");
if (!$smval) $smval = date("m");
if (!$eyval) $eyval = $syval;
if (!$emval) $emval = $smval;
if (!$order_fg) $order_fg = "date";
$start_ym = $syval . str_pad($smval, 2, "0", STR_PAD_LEFT);
$end_ym   = $eyval . str_pad($emval, 2, "0", STR_PAD_LEFT);
?>

<div class="pageWrap">
    <div class="page-heading">
        <h3>
            상품별 매출 통계
        </h3>
        <ul class="breadcrumb">
            <li>통계 현황</li>
            <li class="active">상품별 매출 통계</li>
        </ul>
    </div>

    <div class="box comMTop20" style="width:978px;">
        <div class="panel">
            <form name="frm" action="<?= $PHP_SELF ?>" method="get">
                <table class="table noMargin" cellpadding="0" cellspacing="0">
                    <col width="80" />
                    <col width="" />
                    <tbody>
                        <tr>
                            <td height="26" align="right" style="padding-left:5px">검색 기간</td>
                            <td class="comALeft" style="padding-left:5px">
                                <select name="syval" class="form-control" style="width:auto;" onChange="this.form.submit();">
                                    <? for ($i = 2017; $i <= date("Y"); $i++) { ?>
                                        <option value="<?= $i ?>" <? if ($syval == $i) { ?>selected<? } ?>><?= $i ?> 년</option>
                                    <? } ?>
                                </select>
                                <select name="smval" class="form-control" style="width:auto;" onChange="this.form.submit();">
                                    <? for ($i = 1; $i <= 12; $i++) { ?>
                                        <option value="<?= str_pad($i, 2, "0", STR_PAD_LEFT) ?>" <? if ($smval == str_pad($i, 2, "0", STR_PAD_LEFT)) { ?>selected<? } ?>><?= str_pad($i, 2, "0", STR_PAD_LEFT) ?> 월</option>
                                    <? } ?>
                                </select>
                                ~
                                <select name="eyval" class="form-control" style="width:auto;" onChange="this.form.submit();">
                                    <? for ($i = 2017; $i <= date("Y"); $i++) { ?>
                                        <option value="<?= $i ?>" <? if ($eyval == $i) { ?>selected<? } ?>><?= $i ?> 년</option>
                                    <? } ?>
                                </select>
                                <select name="emval" class="form-control" style="width:auto;" onChange="this.form.submit();">
                                    <? for ($i = 1; $i <= 12; $i++) { ?>
                                        <option value="<?= str_pad($i, 2, "0", STR_PAD_LEFT) ?>" <? if ($emval == str_pad($i, 2, "0", STR_PAD_LEFT)) { ?>selected<? } ?>><?= str_pad($i, 2, "0", STR_PAD_LEFT) ?> 월</option>
                                    <? } ?>
                                </select>
                                <select name="order_fg" class="form-control" style="width:auto;" onChange="this.form.submit();">
                                    <option value="date" <? if ($order_fg == "date") { ?>selected<? } ?>>일자순 정렬</option>
                                    <option value="amount" <? if ($order_fg == "amount") { ?>selected<? } ?>>판매수량순 정렬</option>
                                </select>
                                <button class="btn btn-info btn-sm" type="button" onclick="location.href='stat_product_excel.php?syval=<?= $syval ?>&smval=<?= $smval ?>&eyval=<?= $eyval ?>&emval=<?= $emval ?>&order_fg=<?= $order_fg ?>';">엑셀 다운로드</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    <div class="box comMTop20" style="width:978px;">
        <div class="panel">
            <table class="table statTable" cellpadding="0" cellspacing="0">
                <col width="" />
                <col width="" />
                <col width="" />
                <thead>
                    <tr>
                        <td>년월</td>
                        <td>상품명</td>
                        <td>판매수량</td>
                    </tr>
                </thead>
                <tbody>

<?
    $sql  = "";
    $sql .= "       Select  ";
    if ($order_fg == "date") {
        $sql .= "           DATE_FORMAT(o.order_date,'%Y.%m.%d') As ym, ";
    } else {
        $sql .= "           DATE_FORMAT(o.order_date,'%Y.%m') As ym, ";
    }
    $sql .= "           b.prdcode, b.prdname, SUBSTRING_INDEX(SUBSTRING_INDEX(opt_normal_counting,'^',2),'^',-1) As prdname_opt, ";
    $sql .= "           SUM(IFNULL(SUBSTRING_INDEX(opt_normal_counting,'^',1),1) * b.amount) As sum_amount ";
    $sql .= "   From    df_shop_order o, df_shop_basket b ";
    $sql .= "   Where   DATE_FORMAT(o.order_date,'%Y%m') between '" . $start_ym . "' and '" . $end_ym . "' ";
    $sql .= "   And             o.status In ('DI','DC') ";
    if($ez_admin['id'] == "gonggu_admin"){
        $sql .= " And Exists (Select 1 From df_shop_basket Where prdcode='2303100001' And orderid=o.orderid) ";
    }
    else if($ez_admin['id'] == "gonggu_admin2"){
        $sql .= " And Exists (Select 1 From df_shop_basket Where prdcode='2304030001' And orderid=o.orderid) ";
    }
    else if($ez_admin['id'] == "gonggu_admin3"){
        $sql .= " And Exists (Select 1 From df_shop_basket Where prdcode='2304120001' And orderid=o.orderid) ";
    }
    else if($ez_admin['id'] == "gonggu_admin4"){
        $sql .= " And Exists (Select 1 From df_shop_basket Where prdcode='2407150001' And orderid=o.orderid) ";
    }
    else if($ez_admin['id'] == "gonggu_admin5"){
        $sql .= " And Exists (Select 1 From df_shop_basket Where prdcode='2407010001' And orderid=o.orderid) ";
    }
    else if($ez_admin['id'] == "gonggu_admin6"){
        $sql .= " And Exists (Select 1 From df_shop_basket Where prdcode='2407260001' And orderid=o.orderid) ";
    }
    else if($ez_admin['id'] == "gonggu_admin7"){
        $sql .= " And Exists (Select 1 From df_shop_basket Where prdcode='2408020001' And orderid=o.orderid) ";
    }
    else if($ez_admin['id'] == "gonggu_admin8"){
        $sql .= " And Exists (Select 1 From df_shop_basket Where prdcode='2410040001' And orderid=o.orderid) ";
    }
    else if($ez_admin['id'] == "gonggu_admin9"){
        $sql .= " And Exists (Select 1 From df_shop_basket Where prdcode='2410040002' And orderid=o.orderid) ";
    }
    else if($ez_admin['id'] == "gonggu_admin10"){
        $sql .= " And Exists (Select 1 From df_shop_basket Where prdcode='2410040003' And orderid=o.orderid) ";
    }
    else if($ez_admin['id'] == "gonggu_admin11"){
        $sql .= " And Exists (Select 1 From df_shop_basket Where prdcode='2410110001' And orderid=o.orderid) ";
    }
    else if($ez_admin['id'] == "gonggu_admin12"){
        $sql .= " And Exists (Select 1 From df_shop_basket Where prdcode='2411040001' And orderid=o.orderid) ";
    }
    else if($ez_admin['id'] == "gonggu_admin13"){
        $sql .= " And Exists (Select 1 From df_shop_basket Where prdcode='2410040003' And orderid=o.orderid) ";
    }
    else if($ez_admin['id'] == "gonggu_admin14"){
        $sql .= " And Exists (Select 1 From df_shop_basket Where prdcode='2504010001' And orderid=o.orderid) ";
    }
    else if($ez_admin['id'] == "gonggu_admin15"){
        $sql .= " And Exists (Select 1 From df_shop_basket Where prdcode='2504230002' And orderid=o.orderid) ";
    }
    $sql .= "   And             o.orderid = b.orderid ";
    $sql .= "   Group by        ym, b.prdcode, b.prdname ";
    if ($order_fg == "date") {
        $sql .= " Order by ym Asc, sum_amount Desc ";
    } else {
        $sql .= " Order by sum_amount Desc ";
    }
?>
                    $result = $db->query($sql);

                    $i = 1;
                    foreach ($result as $row) {
                    ?>
                        <tr <? if ($i % 2 == 0) { ?>style="background-color:#f5f5f5;" <? } ?>>
                            <td><?= $row['ym'] ?></td>
                            <td><?= $row['prdname'] ?></td>
                            <td><?= number_format($row['sum_amount']) ?></td>
                        </tr>
                    <?
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

</div>
</div>

</body>

</html>
