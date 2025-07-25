<? include $_SERVER['DOCUMENT_ROOT'] . "/Madmin/inc/top.php"; ?>
<?
if ($_SESSION['admin_part'] != "0") {
	error("접근 권한이 없습니다.");
}

$type = $_GET['admin_type'];

if ($type != "privacy" && $type != "use" && $type != "privacy_use" && $type != "privacy_third_party" && $type != "privacy_marketing" && $type != "email") {
	error("잘못된 접근입니다.");
}

$sql = "select * from df_site_page where type='$type'";
$page_info = $db->row($sql);

$page_title['privacy'] = "개인정보처리방침";
$page_title['use'] = "이용약관";
$page_title['email'] = "이메일무단수집거부";

$page_title['privacy_use'] = "개인정보 수집 및 이용";
$page_title['privacy_third_party'] = "개인정보 제 3자 제공";
$page_title['privacy_marketing'] = "마케팅목적 정보 수집";
?>

<div class="pageWrap">
	<div class="page-heading">
		<h3>
			<?= $page_title[$type] ?>
		</h3>
		<ul class="breadcrumb">
			<li>개인정보 수집·이용</li>
			<li class="active"><?= $page_title[$type] ?></li>
		</ul>
	</div>

	<form name="frm" action="page_save.php" method="post" onSubmit="return inputCheck(this)" enctype="multipart/form-data">
		<input type="hidden" name="tmp">
		<input type="hidden" name="mode" value="update">
		<input type="hidden" name="type" value="<?= $type ?>">
		<input type="hidden" name="page" value="page_privacy.php?admin_type=<?= $type ?>">	
		<div class="box" style="width:978px;">
			<div class="panel">
				<table class="table orderInfo" cellpadding="0" cellspacing="0">
					<col width="15%" />
					<col width="" />
					<tr>
						<th>내용</th>
						<td class="comALeft">
							<script src="/ckeditor/ckeditor.js"></script>
							<textarea name="content" id="content" cols="70" rows="23"><?= $page_info['content'] ?></textarea>
							<script type="text/javascript">
								//<![CDATA[
								CKEDITOR.replace('content', {
									enterMode: '2',
									shiftEnterMode: '3',
									height: 500,
									filebrowserImageUploadUrl: "/ckeditor/upload.php?type=Images"
								});
								//]]
							</script>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="box comMTop10 comMBottom20" style="width:978px;">
			<div class="comPTop10 comPBottom10 comACenter">
				<button class="btn btn-info btn-sm" type="submit">확인</button>
				<button class="btn btn-danger btn-sm" type="button" onClick="history.go(-1);">취소</button>
			</div>
		</div>

	</form>

</div>
</div>
</div>
</div>

</body>

</html>