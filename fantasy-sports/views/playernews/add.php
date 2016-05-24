<div class="wrap">
    <h2>
        <?php echo !$bIsEdit ? __("Add Player News", FV_DOMAIN) : __("Edit Player News", FV_DOMAIN);?>
        <a class="add-new-h2" href="<?php echo self::$url;?>"><?php echo __("Manage Player News", FV_DOMAIN);?></a>
        <?php if($bIsEdit):?>
        <a class="add-new-h2" href="<?php echo self::$url;?>"><?php echo __("Add New", FV_DOMAIN);?></a>
        <?php endif;?>
    </h2>
    <?php echo settings_errors();?>
    <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="val[id]" value="<?php echo $aForms['id'];?>" />
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php echo __("Player");?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>
                <td>
                    <?php if($aPlayers != null):?>
                        <select name="val[playerID]">
                            <option value="0"><?php echo __("Select a player", FV_DOMAIN);?></option>
                        <?php foreach($aPlayers as $aPlayer):?>
                            <option value="<?php echo $aPlayer['id'];?>" <?php if($aForms['playerID'] == $aPlayer['id']):?>selected="true"<?php endif;?>>
                                <?php echo $aPlayer['name'];?>
                            </option>
                        <?php endforeach;?>
                        </select>
                    <?php endif;?>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __("Date");?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>
                <td>
                    <input type="text" name="val[updated]" id="date" class="regular-text ltr" value="<?php echo $aForms['updated'];?>" />
                    <?php echo __("example", FV_DOMAIN);?>: 2015-05-08 20:10:00
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __("Title");?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>
                <td>
                    <input type="text" name="val[title]" class="regular-text ltr" value="<?php echo $aForms['title'];?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __("Content", FV_DOMAIN);?> <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span></th>
                <td>
                    <textarea rows="5" class="large-text code" name="val[content]"><?php echo $aForms['content'];?></textarea>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>