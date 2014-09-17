<?php
$messages = load_class("Messages");
$ID = $_REQUEST['ID'];
$action = $_REQUEST['action'];
if ($action=='DELETE' && $ID) {
	$d['ID'] = $ID;
	$messages->delete($d);
}
?>
        <div align="right">
               <form action="index.php" method="post" name="add" >
               <input type="hidden" name="page" value="messagesEdit" >
               <input type="submit" value="+<?=$_ADD;?>" />
               </form>
		</div>
        <table id="loglist" class="tablesorter" width="100%" border="0">
         <thead>
          <tr>
            <th width="10%" scope="col"><?=$_GROUPED;?></th>
            <th width="45%" scope="col"><?=$_MESSAGE;?></th>
            <th width="16%" scope="col"><?=$_PUBLISHED;?></th>
            <th width="16%" scope="col"><?=$_CLOSED;?></th>
            <th width="13%" scope="col" colspan="2"><?=$_ACTION;?></th>
          </tr>
         </thead>
    	 <tbody>
        <?php
		$db = $messages->sql();
		$i = 0;
		while ($db->next_record()) {
			$i++;
			$ID = $db->f("ID");
		?>
          <tr class="<?=($i%2==0?'even':'odd'); ?>">
            <td><?=$db->f('grouped')=='0'?$_GP_ALL:$_GP_GROUP; ?></td>
            <td><?=$db->f('message'); ?></td>
            <td><?=$db->f('published')>0?date("Y-m-d",strtotime($db->f('published'))):"&nbsp;"; ?></td>
            <td><?=$db->f('closed')>0?date("Y-m-d",strtotime($db->f('closed'))):"&nbsp;"; ?></td>
            <td align="center">
               <form action="index.php" method="post" name="edit<?=$ID; ?>" >
               <input type="hidden" name="page" value="messagesEdit" >
               <input type="hidden" name="ID" value="<?=$ID; ?>" >
               <input type="submit" value="<?=$_EDIT;?>" />
               </form>
            </td>
            <td align="center">
               <form action="index.php" method="post" name="delete<?=$ID; ?>" >
               <input type="hidden" name="page" value="messagesList" >
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
               <input type="hidden" name="page" value="messagesEdit" >
               <input type="submit" value="+<?=$_ADD;?>" />
               </form>
        </div>
	<script type="text/javascript">
	$(function() {		
		$("#loglist").tablesorter({headers: {4:{sorter: false}}});
	});	
	</script>
