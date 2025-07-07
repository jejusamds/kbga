<?php
include $_SERVER['DOCUMENT_ROOT'] . '/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/inc/util_lib.inc';

$table = 'df_site_qualification';
$mode = $_REQUEST['mode'] ?? '';
$page = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;

$fields = ['page_no','f_name','f_type','f_reg_no','f_manage_org','f_ministry'];

switch($mode){
    case 'insert':
        $cols=[];$vals=[];$params=[];
        foreach($fields as $f){$cols[]=$f;$vals[]=':'.$f;$params[$f]=$_POST[$f]??'';}
        $sql="INSERT INTO {$table} (".implode(',', $cols).", wdate) VALUES (".implode(',', $vals).", NOW())";
        $db->query($sql,$params);
        $url = "qualification_list.php?page={$page}&category=".(int)$_POST['category'];
        complete('등록되었습니다.',$url);
        break;
    case 'update':
        $idx=(int)$_POST['idx'];
        $sets=[];$params=['idx'=>$idx];
        foreach($fields as $f){$sets[]="$f=:$f";$params[$f]=$_POST[$f]??'';}
        $sql="UPDATE {$table} SET ".implode(',', $sets)." WHERE idx=:idx";
        $db->query($sql,$params);
        $url = "qualification_list.php?page={$page}&category=".(int)$_POST['category'];
        complete('수정되었습니다.',$url);
        break;
    case 'delete':
        $ids=array_filter(array_map('intval', explode('|', $_REQUEST['selidx']??'')));
        foreach($ids as $id){$db->query("DELETE FROM {$table} WHERE idx=:id",['id'=>$id]);}
        $url = "qualification_list.php?page={$page}&category=".(int)$_REQUEST['category'];
        complete('삭제되었습니다.',$url);
        break;
    default:
        error('잘못된 모드입니다.');
}
