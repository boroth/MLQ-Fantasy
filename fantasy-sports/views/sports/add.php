<div class="wrap">    <h2>        <?php echo !$bIsEdit ? __("Add Sport", FV_DOMAIN) : __("Edit Sport", FV_DOMAIN);?>        <a class="add-new-h2" href="<?php echo self::$url;?>"><?php echo __("Manage Sports", FV_DOMAIN);?></a>        <?php if($bIsEdit):?>        <a class="add-new-h2" href="<?php echo self::$urladdnew;?>"><?php echo __("Add New", FV_DOMAIN);?></a>        <?php endif;?>    </h2>    <?php echo settings_errors();?>    <form method="post" action="" enctype="multipart/form-data">        <input type="hidden" name="val[id]" value="<?php echo $aForms['id'];?>" />        <table class="form-table">                        <tr valign="top">                <th scope="row"><?php echo __("Image", FV_DOMAIN);?></th>                <td>                    <?php if(isset($aForms) && isset($aForms['image']) && $aForms['image'] != null):?>                        <div class="p_4" id="js_slide_current_image">                            <img src="<?php echo $aForms['full_image_path'];?>" width="80px" height="80px" alt="<?php echo $aForms['name'];?>" />                            <?php if(isset($aForms) && $aForms['siteID'] > 0):;?>                            <br />                            <a href="#" onclick="jQuery.admin.newImage(); return false;"><?php echo __("Click here to upload new image", FV_DOMAIN);?></a>                            <?php endif;?>                        </div>                    <?php endif;?>                    <div id="js_submit_upload_image" <?php if(isset($aForms) && isset($aForms['image']) && $aForms['image'] != null):?>style="display:none"<?php endif;?>>                        <input type="file" id='image' name="image" />                        <div class="extra_info">                            <?php echo __("You can upload a jpg, gif or png file", FV_DOMAIN);?>                        </div>                    </div>                </td>            </tr>            <tr valign="top">                <th scope="row"><?php echo __("Sport", FV_DOMAIN);?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>                <td>                    <select name="val[parent_id]">                        <option value="0">--<?php echo __('Root', FV_DOMAIN)?>--</option>                        <?php foreach($aSports as $aSport):?>                            <option <?php echo $aForms['parent_id'] == $aSport['id'] ? 'selected="true"' : '';?> value="<?php echo $aSport['id'];?>"><?php echo $aSport['name'];?></option>                        <?php endforeach;?>                    </select>                </td>            </tr>            <tr valign="top">                <th scope="row"><?php echo __("Name");?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>                <td>                    <input type="text" name="val[name]" class="regular-text ltr" value="<?php echo $aForms['name'];?>" />                </td>            </tr>            <tr valign="top">                <th scope="row"><?php echo __("Allow Playerdraft", FV_DOMAIN);?></th>                <td>                    <input type="checkbox" name="val[is_playerdraft]" <?php echo $aForms['is_playerdraft'] == 1 ? 'checked="true"' : '';?> value="1" />                </td>            </tr>                        <tr valign="top">                <th scope="row"><?php echo __("Team Sport", FV_DOMAIN);?></th>                <td>                    <input type="checkbox" name="val[is_team]" <?php echo $aForms['is_team'] == 1 ? 'checked="true"' : '';?> value="1" />                </td>            </tr>        </table>        <?php submit_button(); ?>    </form></div>