<?php $this->load->view("partial/header"); ?>
<div id="page_title" style="margin-bottom:8px;"><?php echo $this->lang->line('sales_register'); ?></div>
<?php
if(isset($error))
{
	echo "<div class='error_message'>".$error."</div>";
}
?>
<div id="register_wrapper">
<?php echo form_open("sales/add_item",array('id'=>'add_item_form')); ?>
<label id="item_label" for="item"><?php echo $this->lang->line('sales_find_or_scan_item'); ?></label>
<?php echo form_input(array('name'=>'item','id'=>'item','size'=>'40'));?>
<div id="new_item_button_register" >
		<?php echo anchor("items/view/-1/",
		"<div class='small_button'><span>".$this->lang->line('items_new')."</span></div>",
		array('class'=>'thickbox none','title'=>$this->lang->line('items_new')));
		?>
	</div>

</form>
<table id="register">
<thead>
<tr>
<th style="width:11%;"><?php echo $this->lang->line('common_delete'); ?></th>
<th style="width:30%;"><?php echo $this->lang->line('items_name'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('sales_price'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('sales_tax_percent'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('sales_quantity'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('sales_total'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('sales_edit'); ?></th>
</tr>
</thead>
<tbody id="cart_contents">
<?php
if(count($cart)==0)
{
?>
<tr><td colspan='7'>
<div class='warning_message' style='padding:7px;'><?php echo $this->lang->line('sales_no_items_in_cart'); ?></div>
</tr></tr>
<?php
}
else
{
	foreach($cart as $item_id=>$item)
	{
		echo form_open("sales/edit_item/$item_id");
	?>
		<tr>
		<td><?php echo anchor("sales/delete_item/$item_id",'['.$this->lang->line('common_delete').']');?></td>
		<td><?php echo $item['name']; ?></td>
		<td><?php echo form_input(array('name'=>'price','value'=>$item['price'],'size'=>'6'));?></td>
		<td><?php echo form_input(array('name'=>'tax','value'=>$item['tax'],'size'=>'3'));?></td>
		<td><?php echo form_input(array('name'=>'quantity','value'=>$item['quantity'],'size'=>'2'));?></td>
		<td><?php echo to_currency($item['price']*$item['quantity']*(1+($item['tax']/100))); ?></td>
		<td><?php echo form_submit("edit_item", $this->lang->line('sales_edit_item'));?></td>
		</tr>
		</form>
	<?php
	}
}
?>
</tbody>
</table>
</div>
<div id="overall_sale">
	<?php
	if(isset($customer))
	{
		echo $this->lang->line("customers_customer").': <b>'.$customer. '</b><br />';
		echo anchor("sales/delete_customer",'['.$this->lang->line('common_delete').' '.$this->lang->line('customers_customer').']');
	}
	else
	{
		echo form_open("sales/select_customer",array('id'=>'select_customer_form')); ?>
		<label id="customer_label" for="customer"><?php echo $this->lang->line('sales_select_customer'); ?></label>
		<?php echo form_input(array('name'=>'customer','id'=>'customer','size'=>'30','value'=>$this->lang->line('sales_start_typing_customer_name')));?>
		</form>
		<div style="margin-top:5px;text-align:center;">
		<h3 style="margin: 5px 0 5px 0"><?php echo $this->lang->line('common_or'); ?></h3>
		<?php echo anchor("customers/view/-1/",
		"<div class='small_button' style='margin:0 auto;'><span>".$this->lang->line('customers_new')."</span></div>",
		array('class'=>'thickbox none','title'=>$this->lang->line('customers_new')));
		?>
		</div>
		<div class="clearfix">&nbsp;</div>
		<?php
	}
	?>

	<div id='sale_details'>
		<div class="float_left" style="width:38%;"><?php echo $this->lang->line('sales_sub_total'); ?>:</div>
		<div class="float_left" style="width:45%;font-weight:bold;"><?php echo $subtotal; ?></div>
		
		<div class="float_left" style='width:38%;'><?php echo $this->lang->line('sales_tax'); ?>:</div>
		<div class="float_left" style="width:45%;font-weight:bold;"><?php echo $tax; ?></div>
		
		<div class="float_left" style='width:38%;'><?php echo $this->lang->line('sales_total'); ?>:</div>
		<div class="float_left" style="width:45%;font-weight:bold;"><?php echo $total; ?></div>
	</div>
</div>
<div class="clearfix" style="margin-bottom:30px;">&nbsp;</div>


<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
$(document).ready(function()
{
    $("#item").autocomplete('<?php echo site_url("sales/item_search"); ?>',
    {
    	minChars:0,
    	max:100,
       	delay:10,
    	formatItem: function(row) {
			return row[1];
		}
    });
       
    $("#item").result(function(event, data, formatted)
    {
		$("#add_item_form").submit();
    });
    
	$('#item').focus();
	
	$('#item').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_item_name'); ?>");
    });

	$('#item,#customer').click(function()
    {
    	$(this).attr('value','');
    });  
    
    $("#customer").autocomplete('<?php echo site_url("sales/customer_search"); ?>',
    {
    	minChars:0,
    	delay:10,
    	max:100,
    	formatItem: function(row) {
			return row[1];
		}
    });
    
    $("#customer").result(function(event, data, formatted)
    {
		$("#select_customer_form").submit();
    });

    $('#customer').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_customer_name'); ?>");
    });
});

function post_item_form_submit(response)
{
	if(response.success)
	{
		$("#item").attr("value",response.item_id);
		$("#add_item_form").submit();		
	}
}

function post_person_form_submit(response)
{
	if(response.success)
	{
		$("#customer").attr("value",response.person_id);
		$("#select_customer_form").submit();		
	}
}

</script>