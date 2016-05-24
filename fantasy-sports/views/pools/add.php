<div class="wrap">    <h2>        <?php echo !$bIsEdit ? __("Add Events", FV_DOMAIN) : __("Edit Events", FV_DOMAIN);?>        <a class="add-new-h2" href="<?php echo self::$url;?>"><?php echo __("Manage Events", FV_DOMAIN);?></a>        <?php if($bIsEdit):?>        <a class="add-new-h2" href="<?php echo self::$urladdnew;?>"><?php echo __("Add New", FV_DOMAIN);?></a>        <?php endif;?>    </h2>    <?php echo settings_errors();?>    <form method="post" action="" enctype="multipart/form-data">        <input type="hidden" id="sportData" value='<?php echo $aSports;?>' />        <input type="hidden" id="selType" value='<?php echo $aForms['type'];?>' />        <input type="hidden" id="selOrg" value='<?php echo $aForms['organization'];?>' />        <input type="hidden" id="positionData" value='<?php echo $aPositions;?>' />        <input type="hidden" id="lineupData" value='<?php echo $aForms['lineup'];?>' />        <input type="hidden" name="val[poolID]" value="<?php echo $aForms['poolID'];?>" />        <table class="form-table">            <tr valign="top">                <th scope="row"><?php echo __("Image", FV_DOMAIN);?></th>                <td>                    <?php if(isset($aForms) && isset($aForms['image']) && $aForms['image'] != null):?>                        <div class="p_4" id="js_slide_current_image">                            <img src="<?php echo $aForms['full_image_path'];?>" width="80px" height="80px" alt="<?php echo $aForms['poolName'];?>" />                            <br />                            <a href="#" onclick="jQuery.admin.newImage(); return false;"><?php echo __("Image");?><?php echo __("Click here to upload new image", FV_DOMAIN);?></a>                        </div>                    <?php endif;?>                    <div id="js_submit_upload_image" <?php if(isset($aForms) && isset($aForms['image']) && $aForms['image'] != null):?>style="display:none"<?php endif;?>>                        <input type="file" id='image' name="image" />                        <div class="extra_info">                            <?php echo __("You can upload a jpg, gif or png file", FV_DOMAIN);?>                        </div>                    </div>                </td>            </tr>            <tr valign="top">                <th scope="row"><?php echo __("Name");?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>                <td>                    <input type="text" name="val[poolName]" class="regular-text ltr" value="<?php echo $aForms['poolName'];?>" />                </td>            </tr>            <tr valign="top">                <th scope="row"><?php echo __("Sport");?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>                <td id="sportResult">                    <?php if($aSports != null):?>                        <select id="poolOrgs" name="val[organization]" onchange="jQuery.fight.displayType(); jQuery.fight.loadPosition(); jQuery.fight.loadFightersOrTeams();">                        <?php foreach($aSports as $aSport):?>                            <?php if(!empty($aSport['child']) && is_array($aSport['child']) && $aSport['child'] != null):?>                            <option disabled="true"><?php echo $aSport['name'];?></option>                            <?php foreach($aSport['child'] as $aOrg):?>                                <?php if($aOrg['is_active'] == 1):?>                                <option value="<?php echo $aOrg['id'];?>" is_team="<?php echo $aOrg['is_team'];?>" only_playerdraft="<?php echo $aOrg['only_playerdraft'];?>" is_round="<?php echo $aOrg['is_round'];?>" style="padding-left: 20px" <?php if($aForms['organization'] == $aOrg['id']):?>selected="true"<?php endif;?>>                                    <?php echo $aOrg['name'];?>                                </option>                                <?php endif;?>                            <?php endforeach;?>                            <?php endif;?>                        <?php endforeach;?>                        </select>                    <?php endif;?>                </td>            </tr>            <tr valign="top">                <th scope="row"><?php echo __("Start Date");?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>                <td>                    <input type="text" name="val[startDate]" value="<?php echo $aForms['startDateOnly'];?>" id="startDate" size="40" maxlength="150" />                    <?php echo __("Hour", FV_DOMAIN);?>:                    <select name="val[startHour]">                        <?php foreach($aPoolHours as $aPoolHour):?>                        <option value="<?php echo $aPoolHour;?>" <?php echo ($aForms['startHour'] == $aPoolHour) ? 'selected="true"' : '';?>><?php echo $aPoolHour;?></option>                        <?php endforeach;?>                    </select>                    <?php echo __("Minute", FV_DOMAIN);?>:                    <select name="val[startMinute]">                        <?php foreach($aPoolMinutes as $aPoolMinute):?>                        <option value="<?php echo $aPoolMinute;?>" <?php echo $aForms['startMinute'] == $aPoolMinute ? 'selected="true"' : '';?>><?php echo $aPoolMinute;?></option>                        <?php endforeach;?>                    </select>                </td>            </tr>            <tr valign="top">                <th scope="row"><?php echo __("Cut Date");?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>                <td>                    <input type="text" name="val[cutDate]" value="<?php echo $aForms['cutDateOnly'];?>" id="cutDate" size="40" maxlength="150" />                    <?php echo __("Hour", FV_DOMAIN);?>:                    <select name="val[cutHour]">                        <?php foreach($aPoolHours as $aPoolHour):?>                        <option value="<?php echo $aPoolHour;?>" <?php echo $aForms['cutHour'] == $aPoolHour ? 'selected="true"' : '';?>><?php echo $aPoolHour;?></option>                        <?php endforeach;?>                    </select>                    <?php echo __("Minute", FV_DOMAIN);?>:                    <select name="val[cutMinute]">                        <?php foreach($aPoolMinutes as $aPoolMinute):?>                        <option value="<?php echo $aPoolMinute;?>" <?php echo $aForms['cutMinute'] == $aPoolMinute ? 'selected="true"' : '';?>><?php echo $aPoolMinute;?></option>                        <?php endforeach;?>                    </select>                </td>            </tr>            <tr valign="top">                <th scope="row"><?php echo __("Live Event", FV_DOMAIN);?></th>                <td>                    <input type="checkbox" name="val[live_pool]" <?php echo $aForms['live_pool'] == 1 ? 'checked="true"' : '';?> value="1" />                </td>            </tr>            <tr valign="top" class="for_playerdraft salary_cap">                <th scope="row"><?php echo __("Salary Cap", FV_DOMAIN);?></th>                <td>                    <input type="text" name="val[salary_remaining]" value="<?php echo   number_format($aForms['salary_remaining']);?>" onkeyup="this.value = accounting.formatNumber(this.value)"/>                    <?php echo __('( 0 means unlimited )', FV_DOMAIN);?>                </td>            </tr>            <tr valign="top" class="for_playerdraft">                <th scope="row"><?php echo __("Lineup", FV_DOMAIN);?></th>                <td id="lineupResult"></td>            </tr>            <tr valign="top" class="for_round">                <th scope="row"><?php echo __("Rounds", FV_DOMAIN);?></th>                <td>                    <input type="text" name="val[rounds]" value="<?php echo $aForms['rounds'];?>" />                </td>            </tr>            <tr valign="top" class="exclude_fixture">                <th scope="row"><?php echo __("Fixture");?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>                <td>                    <?php require_once(FANVICTOR__PLUGIN_DIR_VIEW.'pools/fights.php');?>                </td>            </tr>        </table>        <?php submit_button(); ?>    </form></div>