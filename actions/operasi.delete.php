<?php

include '../lib/dbconn.php';

pg_query("DELETE FROM rs00008 WHERE trans_group =".$_REQUEST['data']);
/**
if(!$tr->execute()){
echo json_encode(array($tr->showSQL()));
}
*/ 
