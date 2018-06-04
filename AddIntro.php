<script src="jquery-2.1.1.min.js" type="text/javascript"></script>
<!--script src="code.js" type="text/javascript"></script -->
<link rel="stylesheet" href="design.css">

<?php
require_once("dbcontroller.php");
$db_handle = new DBController();

if(!empty($_POST)) {
	
	if ($_POST['delet']) {
		$team= $_POST['team_to_delete'];
		$position= substr($_POST['position'], 2,1);
		$group= substr($_POST['position'], 0,1);

		$sql = "DELETE FROM `a2684541_futsal`.`esk38_intro` WHERE `esk38_intro`.`team`='".$team."' AND `esk38_intro`.`position`='".$position."' AND `esk38_intro`.`group`='".$group."'";
		//die($sql);

		$res = $db_handle->executeQuery($sql);

		
		echo "<div class='success-msg'> Delete done for $team in group $group row $position </div>";

	}else{

	$team= $_POST['team_to_add'];
	$position= substr($_POST['position'], 2,1);
	$group= substr($_POST['position'], 0,1);
	$date= date("Y-m-d H:i:s");

  $sql = "INSERT INTO esk38_intro  VALUES ('" . $position . "','" . $team . "','" . $group . "','" . $date ."')";
  //die($sql);
  
  $ins_id = $db_handle->executeInsert($sql);
  //print($ins_id);
		if($ins_id==0) {
			echo "<div class='success-msg'> Insertion done for $team in group $group row $position </div>";
		}
	}
?>
<!--<tr class="table-row" id="table-row-<?php echo $posts[0]["id"]; ?>">
<td contenteditable="true" onBlur="saveToDatabase(this,'post_title','<?php echo $posts[0]["id"]; ?>')" onClick="editRow(this);"><?php echo $posts[0]["post_title"]; ?></td>
<td contenteditable="true" onBlur="saveToDatabase(this,'description','<?php echo $posts[0]["id"]; ?>')" onClick="editRow(this);"><?php echo $posts[0]["description"]; ?></td>
<td><a class="ajax-action-links" onclick="deleteRecord(<?php echo $posts[0]["id"]; ?>);">Delete</a></td>
</tr> --> 
<?php
	
} //end if POST not empty 

echo "<form action='add.php' method='POST'>";

$equipes = $db_handle->getAllTeam();
$equipesUsed = $db_handle->getUsedteamIntro();
foreach($equipes as $v) {
    $new_equipes[] = $v['t_name'];
}
foreach($equipesUsed as $v) {
    $new_equipesUsed[] = $v['team'];
}
$equipesLeft = array_diff($new_equipes, $new_equipesUsed);
$equipesUnused = array_diff($new_equipes, $equipesLeft);

echo "<select class='select-style' id='team_to_add' name='team_to_add'>";
foreach ($equipesLeft as $equipe_k) {
	$eq = $equipe_k;
	echo "<option value='$eq'>$eq</option>";
}
echo "</select>";
echo "<br><br>";


echo "<select class='select-style' id='team_to_delete' name='team_to_delete'>";
foreach ($equipesUnused as $equipe_kk) {
	$eqq = $equipe_kk;
	echo "<option value='$eqq'>$eqq</option>";
}
echo "</select>";
echo "<br><br>";

$groups = $db_handle->getGroupsIntro();

echo "<select class='select-style' id='position' name='position'>";
foreach ($groups as $group_k => $group_v) {
	$pos = trim($groups[$group_k]['group_name'],'Group ');
	for ($i=1; $i < 7; $i++) { 
	echo "<option value='$pos.$i'>$pos.$i</option>";
	}
}
echo "</select>";

echo "<br><br>";

echo "<input class='input-btn' type='submit' value='ADD'>";
echo "<input class='input-btn' name='delet' type='submit' value='Delete'>";

echo "</form>";
?>