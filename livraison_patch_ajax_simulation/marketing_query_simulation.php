<div class="flexigrid" style="width: 100%;" data-unique-hash="ecd0d0e5a802371936b151b3bc1b7449">
    <div id="main-table-box" class="main-table-box">
        <div class="tDiv">
            <div class="tDiv3">
                <?php if (isset($current_table) && isset($ids)): ?>
                    <a class="export-anchor" data-url="" target="_blank">
                        <div class="fbutton">
                            <div>
                                <a href="<?php echo site_url('user/ajax/export_all?table=' . $current_table . '&ids=' . $ids) ?>"><span class="export">Exporter</span></a>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
                <div class="btnseparator"></div>
            </div>
            <div class="clear"></div>
        </div>
        
        <div id="ajax_list" class="ajax_list">
            <div class="bDiv">
                <?php $total_col = 6; ?>
                <table cellspacing="0" cellpadding="0" border="0" id="flex1">
                    <thead>
					
						<?php if(!empty($result[$indice])){//foreach ($result[$indice] AS $entity): ?>
                            <tr class="hDiv">
                                <?php
                                $item = get_object_vars($result[$indice]);
                                $keys = array_keys($item);
                                ?>
                                <?php foreach ($keys AS $key): ?>
                                    <th>
                                        <div class="text-left"><?php echo lang($key) ?></div>
                                    </th>
                                <?php endforeach; ?>
                                <?php ?>    
                            </tr>
                        <?php } //endforeach; ?>
						
						<?php //foreach ($result AS $entity): ?>
						<tr>
							<?php
							if(!empty($result[$indice])){
                                $item = get_object_vars($result[$indice]);
                                $keys = array_keys($item);
                                ?>
							<?php foreach ($keys AS $key): ?>
                            <th class="action_toogle">
								<input type="text" name="<?php echo $key; ?>" placeholder="<?php echo "rechercher"; ?>" class="get_action_toogle_search seach_flexigrid search_<?php echo $key; ?>"/>
								<i class="action_toogle_search fa fa-search" aria-hidden="true" style="color: blue;" onClick="action_toogle_search()"></i>
							</th>
							<?php endforeach; ?>
							<?php }  ?>  
						</tr>
                        <?php  ?>
					
					
					
                        
                    </thead>
                    <tbody>
						<?php $list_client = array(); ?>
                        <?php foreach ($result AS $entity): ?>
                            <tr>
                                <?php
                                $item = get_object_vars($entity);
                                $keys = array_keys($item);
                                ?>
                                <?php foreach ($keys AS $key): 
								
									if($key=="client_id")
									{
									   if(!in_array($entity->$key,$list_client)){
												$list_client[] = $entity->$key;
									   }
									}
								?>
                                    <td>
                                        <div class="text-left"><?php echo $entity->$key ?></div>
                                    </td>
                                <?php endforeach; ?>                                
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
	<form id="swit_form_chien">
	<?php if(sizeof($list_client)) {
	
	foreach($list_client as $client_id){ ?>
	
			<input type="hidden" name="client_id[]" value="<?php echo $client_id; ?>" />

	
	<?php }
	
	$switch_on = 0;
	$switch_off = 1;
	} ?>
	
		</form>
</div><?php if(sizeof($list_client)) { ?>
<div class="flexigrid content-wrapper">
        <div class="mDiv">
            <div class="ftitle">
                <div class="ftitle-left">Afficher les chiens </div>
				
					<div class="switch clear">
						<input class="switch-input" type="radio" id="switch-on" name="switch-alert" value="1"<?php echo isset($switch_on) && $switch_on ? ' checked="checked"' : '' ?>/>
						<label for="switch-on" class="switch-label switch-label-off">oui</label>
						<input class="switch-input" type="radio" id="switch-off" name="switch-alert" value="0"<?php echo isset($switch_off) && $switch_off ? ' checked="checked"' : '' ?>/>
						<label for="switch-off" class="switch-label switch-label-on">non</label>
						<span class="switch-selection"></span>
					</div>
				
            </div>
        </div>
    </div>
	<?php } ?>

<input type="hidden" id="namesearch" name="namesearch" value="" />
<input type="hidden" id="namesearchval" name="namesearchval" value="" />
<script>

		//$("input[type='radio'][name='switch-alert']").val(0);
        $("input[type='radio'][name='switch-alert']").on("change",function () {
		
		
            if ($(this).val() == 1 || $(this).val() == "1")
			{
               $.ajax({
					url: "<?php echo site_url('user/ajax/get_list_des_chien_par_client') ?>",
					type: "post",
					data: $("#swit_form_chien").serialize(),
					success: function (html) {
						$html = html;
					   $("#list_chien").html($html);
						
					},
					error: function () {
						alert("Erreur pendant le chargement...");
					}
				});
            } else {
               $("#list_chien").html("");
            }
        });
		console.log("fkdjfdkj");
  



$('.ajax_list').on('click','.action_toogle_search',function(){
	
		var is_show = $(this).parent('.action_toogle').find('.get_action_toogle_search').css('display');
		var name_val = $("#namesearchval").val();
		
		console.log(name_val);
		if(name_val!=='')
		{
		
				
				$('#search-results').html('recherche en cours');
				$.ajax({
					url: "<?php echo site_url('user/ajax/query_simulation') ?>",
					type: "post",
					data: $("#search-form").serialize(),
					success: function (html) {
						$html = html;
						$('#ajax-loader').show();
						$("#namesearchval").val("");
						setTimeout(function () {
							$('#ajax-loader').hide();
							$('#search-results').html($html);
						}, 2000);
					},
					error: function () {
						alert("Erreur pendant le chargement...");
					}
				});
	
			
			
			
		}
		if(is_show == 'none'){
			$(this).parent('.action_toogle').find('.get_action_toogle_search').show();
		}else{
			$(this).parent('.action_toogle').find('.get_action_toogle_search').hide();
        }
	});
	
	
	$('.ajax_list').on('keyup','.seach_flexigrid', function(){
		
			
		$("#namesearch").val($(this).attr('name'));
		$("#namesearchval").val($(this).val());
		

	});
	
	
	function recherche_par_ajax()
	{
	
	}



</script>

<style>
.get_action_toogle_search {
    display: none;
    width: 80%;
}
</style>