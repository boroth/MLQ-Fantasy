<div class="wrap">
    <h2>
        <?php echo !$bIsEdit ? __("Add Player Position", FV_DOMAIN) : __("Edit Player Position", FV_DOMAIN);?>
        <a class="add-new-h2" href="<?php echo self::$url;?>"><?php echo __("Manage Player Positions", FV_DOMAIN);?></a>
        <?php if($bIsEdit):?>
        <a class="add-new-h2" href="<?php echo self::$url;?>"><?php echo __("Add New", FV_DOMAIN);?></a>
        <?php endif;?>
    </h2>
    <?php echo settings_errors();?>
    <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="val[id]" value="<?php echo $aForms['id'];?>" />
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php echo __("Sport");?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>
                <td>
                    <?php if($aSports != null):?>

                        <select name="val[org_id]">

                        <?php foreach($aSports as $aSport):?>

                            <?php if(is_array($aSport['child']) && $aSport['child'] != null):?>

                            <option disabled="true"><?php echo $aSport['name'];?></option>

                            <?php foreach($aSport['child'] as $aOrg):?>

                                <?php if($aOrg['is_active'] == 1):?>

                                <option value="<?php echo $aOrg['id'];?>" style="padding-left: 20px" <?php if($aForms['org_id'] == $aOrg['id']):?>selected="true"<?php endif;?>>

                                    <?php echo $aOrg['name'];?>

                                </option>

                                <?php endif;?>

                            <?php endforeach;?>

                            <?php endif;?>

                        <?php endforeach;?>

                        </select>

                    <?php endif;?>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __("Name");?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>
                <td>
                    <input type="text" name="val[name]" class="regular-text ltr" value="<?php echo $aForms['name'];?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __("Player Quantity");?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>
                <td>
                    <input type="text" name="val[default_quantity]" class="regular-text ltr" value="<?php echo empty($aForms['default_quantity']) ? 1 : $aForms['default_quantity'];?>" />
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
