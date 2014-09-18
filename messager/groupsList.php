<?php
$groups = load_class("Groups");
$ID = $_REQUEST['ID'];
$action = $_REQUEST['action'];
if ($action=='DELETE' && $ID) {
	$d['ID'] = $ID;
	$groups->delete($d);
}
?>
        <div align="right">
               <form action="index.php" method="post" name="add" >
               <input type="hidden" name="page" value="groupsEdit" >
               <input type="submit" value="+<?=$_ADD;?>" />
               </form>
		</div>
        <table id="loglist" class="tablesorter" width="100%" border="0">
         <thead>
          <tr>
            <th width="43%" scope="col"><?=$_GROUPNAME;?></th>
            <th width="43%" scope="col"><?=$_SUB_GROUPNAME;?></th>
            <th width="14%" scope="col" colspan="2"><?=$_ACTION;?></th>
          </tr>
         </thead>
    	 <tbody>
        <?php
		$db = $groups->sql("parentID='0'","groupname");
		$i = 0;
		while ($db->next_record()) {
			$i++;
			$ID = $db->f("ID");
		?>
          <tr class="<?=($i%2==0?'even':'odd'); ?>">
            <td><?=$db->f('groupname'); ?></td>
            <td>&nbsp;</td>
            <td align="center">
               <form action="index.php" method="post" name="edit<?=$ID; ?>" >
               <input type="hidden" name="page" value="groupsEdit" >
               <input type="hidden" name="ID" value="<?=$ID; ?>" >
               <input type="submit" value="<?=$_EDIT;?>" />
               </form>
            </td>
            <td align="center">
               <form action="index.php" method="post" name="delete<?=$ID; ?>" >
               <input type="hidden" name="page" value="groupsList" >
               <input type="hidden" name="action" value="DELETE" >
               <input type="hidden" name="ID" value="<?=$ID; ?>" >
               <input type="submit" value="<?=$_DELETE;?>" onClick="return confirm('<?=$_DELETE_MSG;?>');" />
               </form>
            </td>
          </tr>
        <?php
        	$db1 = $groups->sql("parentID='$ID'","groupname");
			while ($db1->next_record()) {
				$i++;
				$ID = $db1->f("ID");
			?>
	          <tr class="<?=($i%2==0?'even':'odd'); ?>">
	            <td>&nbsp;</td>
	            <td><?=$db1->f('groupname'); ?></td>
	            <td align="center">
	               <form action="index.php" method="post" name="edit<?=$ID; ?>" >
	               <input type="hidden" name="page" value="groupsEdit" >
	               <input type="hidden" name="ID" value="<?=$ID; ?>" >
	               <input type="submit" value="<?=$_EDIT;?>" />
	               </form>
	            </td>
	            <td align="center">
	               <form action="index.php" method="post" name="delete<?=$ID; ?>" >
	               <input type="hidden" name="page" value="groupsList" >
	               <input type="hidden" name="action" value="DELETE" >
	               <input type="hidden" name="ID" value="<?=$ID; ?>" >
	               <input type="submit" value="<?=$_DELETE;?>" onClick="return confirm('<?=$_DELETE_MSG;?>');" />
	               </form>
	            </td>
	          </tr>
        <?php
        	}
		}
		?>
		 </tbody>
        </table>
        <div align="right">
               <form action="index.php" method="post" name="add" >
               <input type="hidden" name="page" value="groupsEdit" >
               <input type="submit" value="+<?=$_ADD;?>" />
               </form>
        </div>
	<script type="text/javascript">
	$(function() {		
		$("#loglist").tablesorter({headers: {0:{sorter: false},1:{sorter: false},2:{sorter: false}}});
	});	
	</script>
