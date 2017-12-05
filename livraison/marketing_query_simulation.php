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
                        <?php 
						
						
						
						//foreach ($result[$indice] AS $entity): ?>
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
                                <?php// break; ?>    
                            </tr>
                        <?php //endforeach; ?>
						
						<?php //foreach ($result AS $entity): ?>
						<tr>
							<?php
                                $item = get_object_vars($result[$indice]);
                                $keys = array_keys($item);
                                ?>
							<?php foreach ($keys AS $key): ?>
                            <th class="action_toogle">
								<input type="text" name="<?php echo $key; ?>" placeholder="<?php echo "rechercher"; ?>" class="get_action_toogle_search seach_flexigrid search_<?php echo $key; ?>"/>
								<i class="action_toogle_search fa fa-search" aria-hidden="true" style="color: blue;" onClick="action_toogle_search()"></i>
							</th>
							<?php endforeach; ?>
                                <?php //break; ?>  
						</tr>
                        <?php //endforeach; ?>
                    </thead>
                    <tbody>
                        <?php foreach ($result AS $entity): ?>
                            <tr>
                                <?php
                                $item = get_object_vars($entity);
                                $keys = array_keys($item);
                                ?>
                                <?php foreach ($keys AS $key): ?>
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
</div>
<script>
$('.ajax_list').on('click','.action_toogle_search',function(){
	
		var is_show = $(this).parent('.action_toogle').find('.get_action_toogle_search').css('display');
		var name_val = $("#namesearchval").val();
		if(name_val!=='')
		{
		
			$("#search_field").val($("#namesearch").val());
			$("#search_text").val(name_val);
			$(this).closest('.flexigrid').find('.filtering_form').trigger('submit');
			
		}
		if(is_show == 'none'){
			$(this).parent('.action_toogle').find('.get_action_toogle_search').show();
		}else{
			$(this).parent('.action_toogle').find('.get_action_toogle_search').hide();
        }
	});



</script>

<style>
.get_action_toogle_search {
    display: none;
    width: 80%;
}
</style>