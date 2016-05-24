<div class="f-lobby" data-filter="true">
    <input type="hidden" id="submitUrl" value="<?php echo FANVICTOR_URL_SUBMIT_PICKS;?>" />
    <div class="f-title">
        <div>
            <?php if(get_option('fanvictor_create_contest')):?>
            <a class="f-create-contest f-button f-primary f-right" href="<?php echo FANVICTOR_URL_CREATE_CONTEST;?>"><?php echo __('Create Contest', FV_DOMAIN)?></a>
            <?php endif;?>
            <div class="f-nextgame" id="contestCountdown">
                <span class="f-next-game-label"><?php echo __('Next contest starts in:', FV_DOMAIN);?></span>
                <span class="f-value" id="lobbyCountdown"></span>
            </div>
            <h1><?php echo __('Lobby', FV_DOMAIN);?></h1>
        </div>
    </div>
    <div data-filter-group="true" class="f-text-search">
        <ul class="f-unstyled">
            <li>
                <form onsubmit="return false;" action="#" class="f-search-os" id="f-foo">
                    <input type="text" data-filter-type="search" placeholder="<?php echo __('Search all contests...', FV_DOMAIN)?>" class="f-search-input">
                    <input type="reset" value="" class="f-search-reset">
                </form>
            </li>
        </ul>
    </div>
    <div data-filter-group="true" data-filter-hide="true" data-filter-condition="search=" class="f-filter">
        <h2><?php echo __('Sport', FV_DOMAIN);?></h2>
        <div data-filter-group="true" class="f-sport f-selector">
            <ul>
                <label class="f-all f-checked">
                    <li>
                        <input type="radio" checked="checked" value="" name="sport-select" data-filter-type="sport"><?php echo __('All sports', FV_DOMAIN);?>
                    </li>
                </label>
                <?php if(!empty($aSports)):?>
                <?php foreach($aSports as $aSport):?>
                    <?php if(!empty($aSport['child'])):?>
                    <?php foreach($aSport['child'] as $sport):?>
                    <label>
                        <li>
                            <input type="radio" value="<?php echo $sport['id'];?>" name="sport-select" data-filter-type="sport"><?php echo $sport['name'];?>	
                        </li>
                    </label>
                    <?php endforeach;?>
                    <?php endif;?>
                <?php endforeach;?>
                <?php endif;?>
            </ul>
        </div>
        <div class="f-separator"></div>
        <div class="f-type f-selector">
            <h2><?php echo ('Contest Type');?></h2>
            <ul>
                <label class="f-checked">
                    <li>
                        <input type="radio" checked="checked" value="all" name="contest-type" data-filter-type="type"><?php echo __('All contest types', FV_DOMAIN);?>
                    </li>
                </label>
                <label>
                    <li>
                        <input type="radio" value="headtohead" name="contest-type" data-filter-type="type"><?php echo __('Head-to-head', FV_DOMAIN)?>
                    </li>
                </label>
                <label>
                    <li>
                        <input type="radio" value="league" name="contest-type" data-filter-type="type"><?php echo __('Leagues', FV_DOMAIN)?>
                    </li>
                </label>
            </ul>
        </div>
        <div class="f-separator"></div>
        <div class="f-size f-selector f-filter-condition-hidden">
            <h2><?php echo __('Size', FV_DOMAIN);?></h2>
            <ul>
                <label class="f-checked">
                    <li>
                        <input type="radio" checked="checked" value="all" name="contest-size" data-filter-type="size"><?php echo __('All', FV_DOMAIN);?>
                    </li>
                </label>
                <label>
                    <li>
                        <input type="radio" value="3" name="contest-size" data-filter-type="size">3
                    </li>
                </label>
                <label>
                    <li>
                        <input type="radio" value="4-10" name="contest-size" data-filter-type="size">4-10
                    </li>
                </label>
                <label>
                    <li>
                        <input type="radio" value="11+" name="contest-size" data-filter-type="size">11+
                    </li>
                </label>
            </ul>
        </div>
        <div data-filter-hide="true" data-filter-condition="type=league" class="f-separator f-filter-condition-hidden"></div>
        <!--<div class="f-multientry f-filter-condition-hidden f-selector f-user">
            <ul>
                <label>
                    <li>
                        <input type="checkbox" value="1">
                        <?php echo __('Multi Entry', FV_DOMAIN);?>
                    </li>
                </label>
            </ul>
        </div>-->
        <?php if(get_option('fanvictor_no_cash') == 0):?>
        <div class="f-entryfee">
            <div>
                <div id="rangeSlider"></div>
                <div class="ui-rangeSlider-label ui-rangeSlider-leftLabel">
                    <div class="ui-rangeSlider-label-value"></div>
                    <div class="ui-rangeSlider-label-inner"></div>
                </div>
                <div class="ui-rangeSlider-label ui-rangeSlider-rightLabel">
                    <div class="ui-rangeSlider-label-value"></div>
                    <div class="ui-rangeSlider-label-inner"></div>
                </div>
            </div>
        </div>
        <?php endif;?>
        <section data-filter-group="true">
            <div data-filter-persist="custom" class="f-panel active">
                <div class="f-separator"></div>
                <div class="f-startTime f-selector">
                    <h2><?php echo ('Start time');?></h2>
                    <ul>
                        <label class="f-checked">
                            <li>
                                <input type="radio" checked="checked" value="all" name="startTime" data-filter-type="start"><?php echo __('All start times', FV_DOMAIN)?>
                            </li>
                        </label>
                        <label class="f-">
                            <li>
                                <input type="radio" value="next" name="startTime" data-filter-type="start"><?php echo __('Next available', FV_DOMAIN);?>
                            </li>
                        </label>
                        <label class="f-">
                            <li>
                                <input type="radio" value="today" name="startTime" data-filter-type="start"><?php echo __('Today', FV_DOMAIN)?>
                            </li>
                        </label>
                    </ul>
                </div>
            </div>
        </section>
        <div class="f-clear"></div>
    </div>
    <div class="f-filter f-filters-disabled">
        <p class="f-filter-warning"><?php echo __('Filters are disabled while searching', FV_DOMAIN)?></p>
        <button class="f-button f-primary green" id="f-clear-search-link"><?php echo __('Clear search', FV_DOMAIN)?></button>
    </div>
    <div class="f-items">
        <div id="wrapContest">
            <div class="f-body">
                <div class="f-updatesAvailable">
                    <?php echo __('New contests are available.');?> <a href="#"><?php echo __('Refresh', FV_DOMAIN);?></a>
                </div>
                <div class="f-inner" style="">
                    <div class="f-items" id="lobbyContent">
                        <div class="lobbyHeader">
                            <div>
                                <div class="f-title" style="width: <?php echo get_option('fanvictor_no_cash') == 0 ? '34%' : '50%';?>">
                                    <?php echo __('Contest', FV_DOMAIN);?>
                                </div>
                                <div class="f-gametype" style="width: 12%">
                                    <?php echo __('Type', FV_DOMAIN);?>
                                </div>
                                <div class="f-entries" style="width: 12%">
                                    <?php echo __('Entries', FV_DOMAIN);?>
                                </div>
                                <?php if(get_option('fanvictor_no_cash') == 0):?>
                                <div class="f-entryfee" style="width: 8%">
                                    <?php echo __('Entry', FV_DOMAIN);?>
                                </div>
                                <div class="f-prizes" style="width: 8%">
                                    <?php echo __('Prizes', FV_DOMAIN);?>
                                </div>
                                <?php endif;?>
                                <div class="f-starttime" style="width: 18%">
                                    <?php echo __('Starts&nbsp;(ET)', FV_DOMAIN);?>
                                </div>
                                <div class="f-entry" style="width: 8%">&nbsp;</div>
                            </div>
                        </div>
                        <div id="lobbyData"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="f-empty-view">
            <h1><?php echo __('No contests match your search or filter settings', FV_DOMAIN);?></h1>
        </div>
        <div class="f-clear"></div>
    </div>
    
    <div class="f-pager"></div>
</div>
<?php require_once('dlg_info.php');?>