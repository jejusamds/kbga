<?
include "../../inc/global.inc";
include "../../inc/util_lib.inc";

if ($mval == "All") $mval = "";
$filename = iconv('UTF-8', 'EUC-KR', "상품별매출통계[" . $yval . $mval . "].xls");

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename");
header("Content-charset=utf-8");
header("Content-type: application/vnd.ms-excel;");
header("Content-Description: PHP4 Generated Data");

echo "<html xmlns:v=\"urn:schemas-microsoft-com:vml\" 
xmlns:o=\"urn:schemas-microsoft-com:office:office\" 
xmlns:x=\"urn:schemas-microsoft-com:office:excel\" 
xmlns=\"http://www.w3.org/TR/REC-html40\">";
echo "<head>\n";
echo "<style>\n";
echo ".xl40\n";
echo "        {mso-style-parent:style0;\n";
echo "        mso-number-format:'0_ ';\n";
echo "        text-align:center;\n";
echo "        border:.5pt solid black;\n";
echo "        background:white;\n";
echo "        mso-pattern:auto none;\n";
echo "        white-space:normal;}\n";
echo ".number\n";
echo "{mso-number-format:'0_'}\n";
echo ".percent\n";
echo "{mso-number-format:'Percent'}\n";
echo "-->\n";
echo "</style>\n";
echo "</head>\n";
echo "<body>\n";


echo "<table border=0>\n";
echo "<tr align=center style=font-weight:bold>\n";
echo "<td align=center colspan=3>상품별 매출 통계</td>\n";
echo "</tr>";
echo "</table>";
echo "<table border=1>\n";
echo "<tr align=center style=font-weight:bold>\n";
echo "<td bgcolor=#C0C0C0>년월</td>\n";
echo "<td bgcolor=#C0C0C0>상품명</td>\n";
echo "<td bgcolor=#C0C0C0>판매수량</td>\n";
echo "</tr>";

// 년도 - 월별로 합산
if ($mval == "All") {

    $sql  = "";
    $sql .= "	Select	";
    if ($order_fg == "date") {
        $sql .= "		DATE_FORMAT(o.order_date,'%Y.%m') As ym, ";
    } else {
        $sql .= "		DATE_FORMAT(o.order_date,'%Y') As ym, ";
    }
    $sql .= "			b.prdcode, b.prdname, SUBSTRING_INDEX(SUBSTRING_INDEX(opt_normal_counting,'^',2),'^',-1) As prdname_opt, ";
    $sql .= "			SUM(IFNULL(SUBSTRING_INDEX(opt_normal_counting,'^',1),1) * b.amount) As sum_amount ";
    $sql .= "	From	df_shop_order o, df_shop_basket b ";
    $sql .= "	Where	DATE_FORMAT(o.order_date,'%Y') = '" . $yval . "' ";
    $sql .= "	And		o.status In ('DI','DC') ";
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
    $sql .= "	And		o.orderid = b.orderid ";
    $sql .= "	Group by	ym, b.prdcode, b.prdname ";
    if ($order_fg == "date") {
        $sql .= " Order by ym Asc, sum_amount Desc ";
    } else {
        $sql .= " Order by sum_amount Desc ";
    }
}

// 년도 - 월별로 합산
else {

    $sql  = "";
    $sql .= "	Select	";
    if ($order_fg == "date") {
        $sql .= "		DATE_FORMAT(o.order_date,'%Y.%m.%d') As ym, ";
    } else {
        $sql .= "		DATE_FORMAT(o.order_date,'%Y.%m') As ym, ";
    }
    $sql .= "			b.prdcode, b.prdname, SUBSTRING_INDEX(SUBSTRING_INDEX(opt_normal_counting,'^',2),'^',-1) As prdname_opt, ";
    $sql .= "			SUM(IFNULL(SUBSTRING_INDEX(opt_normal_counting,'^',1),1) * b.amount) As sum_amount ";
    $sql .= "	From	df_shop_order o, df_shop_basket b ";
    $sql .= "	Where	DATE_FORMAT(o.order_date,'%Y%m') = '" . $yval . $mval . "' ";
    $sql .= "	And		o.status In ('DI','DC') ";
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
    $sql .= "	And		o.orderid = b.orderid ";
    $sql .= "	Group by	ym, b.prdcode, b.prdname ";
    if ($order_fg == "date") {
        $sql .= " Order by ym Asc, sum_amount Desc ";
    } else {
        $sql .= " Order by sum_amount Desc ";
    }
}
$result = $db->query($sql);

$i = 1;
foreach ($result as $row) {
    echo "<tr>\n";
    echo "<td>" . $row['ym'] . "</td>\n";
    echo "<td>" . $row['prdname'] . "</td>\n";
    echo "<td class=number>" . number_format($row['sum_amount']) . "</td>\n";
    echo "</tr>";
    $i++;
}
echo "</table>\n";


echo "</body>\n";
echo "</html>\n";
