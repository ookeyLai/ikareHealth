<?php
$account = load_class("Account");
$ID = $_REQUEST['ID'];
$action = $_REQUEST['action'];
if ($action=='DELETE' && $ID) {
	$d['ID'] = $ID;
	$account->delete($d);
}
?>
        <div align="right">
               <form action="index.php" method="post" name="add" >
               <input type="hidden" name="page" value="accountEdit" >
               <input type="submit" value="+<?=$_ADD;?>" />
               </form>
		</div>
        <table id="loglist" class="tablesorter" width="100%" border="0">
         <thead>
          <tr>
            <th width="21%" scope="col"><?=$_USERNAME;?></th>
            <th width="16%" scope="col"><?=$_NAME;?></th>
            <th width="30%" scope="col"><?=$_EMAIL;?></th>
            <th width="20%" scope="col"><?=$_PHONE;?></th>
            <th width="13%" scope="col" colspan="2"><?=$_ACTION;?></th>
          </tr>
         </thead>
    	 <tbody>
        <?php
		$db = $account->sql();
		$i = 0;
		while ($db->next_record()) {
			$i++;
			$ID = $db->f("ID");
		?>
          <tr class="<?=($i%2==0?'even':'odd'); ?>">
            <td><?=$db->f('username'); ?></td>
            <td><?=$db->f('name'); ?></td>
            <td><?=$db->f('email'); ?></td>
            <td><?=$db->f('phone'); ?></td>
            <td align="center">
               <form action="index.php" method="post" name="edit<?=$ID; ?>" >
               <input type="hidden" name="page" value="accountEdit" >
               <input type="hidden" name="ID" value="<?=$ID; ?>" >
               <input type="submit" value="<?=$_EDIT;?>" />
               </form>
            </td>
            <td align="center">
               <form action="index.php" method="post" name="delete<?=$ID; ?>" >
               <input type="hidden" name="page" value="accountList" >
               <input type="hidden" name="action" value="DELETE" >
               <input type="hidden" name="ID" value="<?=$ID; ?>" >
               <input type="submit" value="<?=$_DELETE;?>" onClick="return confirm('<?=$_DELETE_MSG;?>');" />
               </form>
            </td>
          </tr>
        <?php
		}
		?>
		 </tbody>
        </table>
        <div align="right">
               <form action="index.php" method="post" name="add" >
               <input type="hidden" name="page" value="accountEdit" >
               <input type="submit" value="+<?=$_ADD;?>" />
               </form>
        </div>
	<script type="text/javascript">
	$(function() {		
		$("#loglist").tablesorter({headers: {4:{sorter: false}}});
	});	
	</script>
