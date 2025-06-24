<?php
include $_SERVER['DOCUMENT_ROOT'].'/inc/global.inc';
include $_SERVER['DOCUMENT_ROOT'].'/inc/util_lib.inc';

$table = 'df_site_agency';
$mode = $_REQUEST['mode'] ?? '';
$page = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
$type = $_REQUEST['type'] ?? ($_REQUEST['f_type'] ?? 'cooperate');
$query_str = '&type='.$type;

switch($mode){
    case 'insert':
        $f_name = $_POST['f_name'] ?? '';
        $f_url  = $_POST['f_url'] ?? '';
        $f_type = $_POST['f_type'] ?? 'cooperate';
        $order = $db->single("SELECT IFNULL(MAX(f_order),0)+1 FROM {$table} WHERE f_type=:t", ['t'=>$f_type]);
        $f_img = '';
        $f_img_m = '';
        if(!empty($_FILES['f_img']['name'])){
            $dir = $_SERVER['DOCUMENT_ROOT'].'/userfiles/agency';
            if(!is_dir($dir)) mkdir($dir,0755,true);
            $ext = strtolower(pathinfo($_FILES['f_img']['name'], PATHINFO_EXTENSION));
            $f_img = uniqid('img_').'.'.$ext;
            move_uploaded_file($_FILES['f_img']['tmp_name'],$dir.'/'.$f_img);
        }
        if(!empty($_FILES['f_img_m']['name'])){
            $dir = $_SERVER['DOCUMENT_ROOT'].'/userfiles/agency';
            if(!is_dir($dir)) mkdir($dir,0755,true);
            $ext = strtolower(pathinfo($_FILES['f_img_m']['name'], PATHINFO_EXTENSION));
            $f_img_m = uniqid('img_m_').'.'.$ext;
            move_uploaded_file($_FILES['f_img_m']['tmp_name'],$dir.'/'.$f_img_m);
        }
        $db->query("INSERT INTO {$table} (f_type,f_name,f_url,f_img,f_img_m,f_order,wdate) VALUES (:type,:name,:url,:img,:img_m,:ord,NOW())",
            ['type'=>$f_type,'name'=>$f_name,'url'=>$f_url,'img'=>$f_img,'img_m'=>$f_img_m,'ord'=>$order]);
        complete('등록되었습니다.',"/Madmin/agency/agency_list.php?page={$page}{$query_str}");
        break;
    case 'update':
        $idx = (int)$_POST['idx'];
        $row = $db->row("SELECT * FROM {$table} WHERE idx=:idx", ['idx'=>$idx]);
        if(!$row) error('잘못된 접근입니다.');
        $f_name = $_POST['f_name'] ?? '';
        $f_url  = $_POST['f_url'] ?? '';
        $sets = ['f_name=:name','f_url=:url'];
        $params = ['name'=>$f_name,'url'=>$f_url,'idx'=>$idx];
        if(!empty($_FILES['f_img']['name'])){
            $dir = $_SERVER['DOCUMENT_ROOT'].'/userfiles/agency';
            if(!is_dir($dir)) mkdir($dir,0755,true);
            $ext = strtolower(pathinfo($_FILES['f_img']['name'], PATHINFO_EXTENSION));
            $new = uniqid('img_').'.'.$ext;
            move_uploaded_file($_FILES['f_img']['tmp_name'],$dir.'/'.$new);
            $sets[]='f_img=:img';
            $params['img']=$new;
        }
        if(!empty($_FILES['f_img_m']['name'])){
            $dir = $_SERVER['DOCUMENT_ROOT'].'/userfiles/agency';
            if(!is_dir($dir)) mkdir($dir,0755,true);
            $ext = strtolower(pathinfo($_FILES['f_img_m']['name'], PATHINFO_EXTENSION));
            $newm = uniqid('img_m_').'.'.$ext;
            move_uploaded_file($_FILES['f_img_m']['tmp_name'],$dir.'/'.$newm);
            $sets[]='f_img_m=:imgm';
            $params['imgm']=$newm;
        }
        $sql = "UPDATE {$table} SET ".implode(',', $sets)." WHERE idx=:idx";
        $db->query($sql,$params);
        complete('수정되었습니다.',"/Madmin/agency/agency_list.php?page={$page}{$query_str}");
        break;
    case 'delete':
        $arr = array_filter(array_map('intval', explode('|', $_REQUEST['selidx'] ?? '')));
        foreach($arr as $id){
            $db->query("DELETE FROM {$table} WHERE idx=:id", ['id'=>$id]);
        }
        complete('삭제되었습니다.',"/Madmin/agency/agency_list.php?page={$page}{$query_str}");
        break;
    case 'prior':
        $idx = (int)$_GET['idx'];
        $posi = $_GET['posi'];
        $current = $db->row("SELECT f_order,f_type FROM {$table} WHERE idx=:idx",['idx'=>$idx]);
        if($current){
            if($posi=='up'){
                $other = $db->row("SELECT idx,f_order FROM {$table} WHERE f_type=:t AND f_order<:o ORDER BY f_order DESC LIMIT 1",['t'=>$current['f_type'],'o'=>$current['f_order']]);
            }else{
                $other = $db->row("SELECT idx,f_order FROM {$table} WHERE f_type=:t AND f_order>:o ORDER BY f_order ASC LIMIT 1",['t'=>$current['f_type'],'o'=>$current['f_order']]);
            }
            if($other){
                $db->query("UPDATE {$table} SET f_order=:o WHERE idx=:id",['o'=>$current['f_order'],'id'=>$other['idx']]);
                $db->query("UPDATE {$table} SET f_order=:o WHERE idx=:id",['o'=>$other['f_order'],'id'=>$idx]);
            }
        }
        header("Location: /Madmin/agency/agency_list.php?page={$page}{$query_str}");
        exit;
    default:
        error('잘못된 모드입니다.');
}
