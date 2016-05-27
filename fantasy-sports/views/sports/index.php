<div class="wrap">

    <h2>
        <?php echo __("Organization Settings", FV_DOMAIN);?>
        <a class="add-new-h2" href="<?php echo self::$urladdnew;?>"><?php echo __("Add New", FV_DOMAIN);?></a>
        <a class="add-new-h2" onclick="jQuery.admin.showInfoManageSport();" href="#"><?php echo __("Read me", FV_DOMAIN);?></a>
    </h2>
    
    <?php echo settings_errors();?>

    <form name="adminForm" action="<?php echo self::$url;?>" method="post">

        <input id="submitTask" type="hidden" name="task">

        <?php $myListTable->display();?>

        <input type="button" value="<?php echo __("Delete Selected");?>" class="button button-primary"  onclick="return jQuery.admin.action('', 'delete');">

    </form>

</div>

<div id="resultDialog" title="" style="display: none"></div>
<div id="readmeDialog" title="" style="display: none">
    <p>On this page, you will set up your sports and organization. A sport would be something like "Football" and an organization would be "NFL". An organization belongs to a sport.</p>
    <p>First you must create your sport. So you would type FOOTBALL and keep the drop down as ROOT. This will create the Sport Football. It will be an option added to the Sport dropdown.</p>
    <p>Now you MUST create an organization that belongs to that sport. In this example, you would type NFL and select FOOTBALL from the drop down. Now, you have created an organization under a sport.</p>
    <p>
        Set up a Fantasy Contest</br>
        As easy as: Add Sport</br>
        Add Organization to sport</br>
        Add Team</br>
        Add Positions</br>
        Add Players to team</br>
        Add Scoring Category</br>
        Add Event and Add Fixture</br>
        Add Contest based off event/Fixtures</br>
    </p>
</div>
