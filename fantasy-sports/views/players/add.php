<div class="wrap">    <h2>        <?php echo !$bIsEdit ? __("Add Players", FV_DOMAIN) : __("Edit Players", FV_DOMAIN);?>        <a class="add-new-h2" href="<?php echo self::$url;?>"><?php echo __("Manage Players", FV_DOMAIN);?></a>        <?php if($bIsEdit):?>        <a class="add-new-h2" href="<?php echo self::$urladdnew;?>"><?php echo __("Add New", FV_DOMAIN);?></a>        <?php endif;?>    </h2>    <?php echo settings_errors();?>    <input type="hidden" id="teamsData" value='<?php echo str_replace("'", "*", json_encode($aTeams));?>' />    <input type="hidden" id="positionsData" value='<?php echo str_replace("'", "\'", json_encode($aPositions));?>' />    <input type="hidden" id="selectTeam" value='<?php echo $aForms['team_id'];?>' />    <input type="hidden" id="selectPosition" value='<?php echo $aForms['position_id'];?>' />    <form method="post" action="" enctype="multipart/form-data">        <input type="hidden" name="val[id]" value="<?php echo $aForms['id'];?>" />        <table class="form-table">			<?php if(isset($aForms) && $aForms['siteID'] > 0 || !$bIsEdit):;?>            <tr valign="top">                <th scope="row"><?php echo __("Image", FV_DOMAIN);?></th>                <td>                    <?php if(isset($aForms) && isset($aForms['image']) && $aForms['image'] != null):?>                        <div class="p_4" id="js_slide_current_image">                            <img src="<?php echo $aForms['full_image_path'];?>" width="80px" height="80px" alt="<?php echo $aForms['name'];?>" />                            <?php if(isset($aForms) && $aForms['siteID'] > 0):;?>                            <br />                            <a href="#" onclick="jQuery.admin.newImage(); return false;"><?php echo __("Click here to upload new image", FV_DOMAIN);?></a>                            <?php endif;?>                        </div>                    <?php endif;?>                    <div id="js_submit_upload_image" <?php if(isset($aForms) && isset($aForms['image']) && $aForms['image'] != null):?>style="display:none"<?php endif;?>>                        <input type="file" id='image' name="image" />                        <div class="extra_info">                            <?php echo __("You can upload a jpg, gif or png file", FV_DOMAIN);?>                        </div>                    </div>                </td>            </tr>            <tr valign="top">                <th scope="row"><?php echo __("Organization");?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>                <td>                    <?php if($aSports != null):?>                        <select id="org" name="val[org_id]" onchange="jQuery.players.loadTeams();jQuery.players.loadPositions();">                        <?php foreach($aSports as $aSport):?>                            <?php if(!empty($aSport['child'])):?>                            <option disabled="true"><?php echo $aSport['name'];?></option>                            <?php foreach($aSport['child'] as $aOrg):?>                                <option value="<?php echo $aOrg['id'];?>" style="padding-left: 20px" <?php if($aForms['org_id'] == $aOrg['id']):?>selected="true"<?php endif;?>>                                    <?php echo $aOrg['name'];?>                                </option>                            <?php endforeach;?>                            <?php endif;?>                        <?php endforeach;?>                        </select>                    <?php endif;?>                </td>            </tr>            <tr valign="top">                <th scope="row"><?php echo "Teams";?></th>                <td id="htmlTeams"></td>            </tr>            <tr valign="top">                <th scope="row"><?php echo "Position";?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>                <td id="htmlPositions"></td>            </tr>            <tr valign="top">                <th scope="row"><?php echo __("Indicator", FV_DOMAIN);?></th>                <td>                    <?php if($aIndicators != null):?>                    <select name="val[indicator_id]">                        <option value="0">None</option>                        <?php foreach($aIndicators as $aIndicator):?>                        <option <?php echo $aForms['indicator_id'] == $aIndicator['id'] ? 'selected="true"' : '';?> value="<?php echo $aIndicator['id'];?>">                            <?php echo $aIndicator['name'];?>                        </option>                        <?php endforeach;?>                    </select>                    <?php endif;?>                </td>            </tr>            <?php endif;?>            <tr valign="top">                <th scope="row"><?php echo __("Name");?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>                <td>                    <input type="text" name="val[name]" class="regular-text ltr" value="<?php echo $aForms['name'];?>" <?php if(isset($aForms) && $aForms['siteID'] == 0):;?>disabled="true"<?php endif;?> />                </td>            </tr>            <tr valign="top">                <th scope="row"><?php echo __("Salary");?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>                <td>                    <input type="text" name="val[salary]" class="regular-text ltr" value="<?php echo   number_format($aForms['salary']);?>" onkeyup="this.value = accounting.formatNumber(this.value)" />                </td>            </tr>        </table>        <?php submit_button(); ?>    </form></div><script type="text/javascript">jQuery(window).load(function(){    jQuery.players.setData();    jQuery.players.loadTeams();    jQuery.players.loadPositions();})</script>