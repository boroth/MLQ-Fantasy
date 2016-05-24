<?php if($allow_statistic):?><?php getMessage();?><style>	#fsbox form > div{		margin-bottom: 6px;	}	.loading{		border: 2px solid orange;	}	.dib{		display: inline-block;		color:#3d7db3;	}	.dib.active{ 		color: #737A7D !important;	}	/*.dib:after{	content: '';	display: inline-block;	width: 100%;	}*/	.hide{		display: none;	}	.pg-wrapper .prev,.pg-wrapper .next{		visibility: hidden;	}	.pg-wrapper .dib,.tab-btn{		cursor: pointer	}	.tab-wrapper,.pg-wrapper,.filter-wrapper,.tbl-wrapper,#emptybox{		background: #eee;	}	.filter-wrapper{		padding:15px;	}	.pg-wrapper{		text-align: center;		margin:7px 0;	}	.pg-wrapper div{		padding: 4px 10px;		margin-right:2px;	}	.pg-wrapper .dib:hover,.pg-wrapper .active{		background: #E7E7E7	}	.tbl-wrapper{		overflow-x: auto;	}	.tbl-wrapper th{		padding: 0 2px;		border:none;        width: 52px;        text-transform: capitalize;        cursor: pointer;		font-size:13px;	}    .tbl-wrapper thead{        background: #ececec none repeat scroll 0 0;    }    .tbl-wrapper th:hover{        text-decoration: underline;    }    .tbl-wrapper tr{        display: inline-block;		margin: 0;		padding: 0;    }	.tbl-wrapper tbody{/*		border-top:solid 1px #ADADAD;*/	}		.tbl-wrapper th:first-child{/*		width:20%;*/	}    .tbl-wrapper th:first-child, .tbl-wrapper td:first-child{        text-align: left;    }    .tbl-wrapper td{        min-width: 52px;    }	.tbl-wrapper td:first-child{		color:#3D7DB3;	}	.tbl-wrapper table{		border:none;	}    .tbl-wrapper .frozen{        left: 0;        position: absolute;        top: auto;        z-index: 99999;    }    .tbl-wrapper th:nth-child(2), .tbl-wrapper td:nth-child(2){        left: 150px;    }    .tbl-wrapper th:nth-child(3), .tbl-wrapper td:nth-child(3){        left: 202px;    }	th{		text-align: center;	}	.entry-content table{		margin: 0px;	}	.entry-content td{		padding: 2px;		text-align: center;		border:none;	}	.tbl-wrapper .tr0 td{		background: #fff	}	.tbl-wrapper .tr1 td{		background: #ECECEC;	}	.tab-wrapper{		padding-bottom: 3px;	}	.tab-btn{		padding: 0 8px;		margin-right: 2px;		border-left: 1px solid #f0f0f0;		border-top: 1px solid #ddd;		border-right: 2px solid #bbb;		border-bottom: none;		color: #fff;		background: #000;		cursor: pointer;	}	.tab-wrapper .active{		background: #eee;		color: #000	}    .outerdiv {        left: 0;        position: absolute;        right: 370px;        top: 0;    }    .innerdiv {        margin-left: 370px;        overflow-x: scroll;        overflow-y: visible;        padding-bottom: 1px;        width: 100%;    }    #wrap_outerdiv{        background: #ececec none repeat scroll 0 0;        position: relative;        min-height: 715px;    }</style><div id="fsbox">	<form id="fstat" method="post">        <input id="sort_name" type="hidden" name="sort_name" value="" />        <input id="sort_value" type="hidden" name="sort_value" value="" />        <input id="login_url" type="hidden" value="<?php echo wp_login_url(); ?>" />		<?php		if($aSports):?>        <div <?php if(count($aSports) <= 2):?>style="display: none"<?php endif;?>>			<label for="sports"><?php echo __('Sport', FV_DOMAIN);?>			</label>			<select id="sports" name="sid" name="organizationID" onchange="jQuery.stats.loadPools(this.value, jQuery('option:selected', this).data('isround'))">				<?php				foreach($aSports as $sport)				{					if(!empty($sport['child']) && is_array($sport['child']) AND $sport['child']):?>					<optgroup label="<?php echo $sport['name'];?>">						<?php						foreach($sport['child'] as $org)						{							?>							<option value="<?php echo $org['id'];?>" data-isround="<?php echo $sport['is_round'];?>"><?php echo $org['name'];?>							</option>							<?php							$selected = '';						}?>					</optgroup>					<?php					endif;				}?>			</select>		</div>		<?php endif;?>		<!--//sport-->        <div id="hidbox">			<div class="tab-wrapper" id="postab">			</div>			<!--//tab-->			<input type="hidden" name="posid" id="posid" value="0" />			<div class="filter-wrapper">				<div class="dib">					<div>						<select name="pid" id="psb">						</select>					</div>				</div>                <div class="dib hide">					<div>                        <select name="team_id" id="cbTeam" onchange="jQuery('#pgv').val('1');jQuery.stats.getStat()"></select>					</div>				</div>                <div class="dib">					<div>						<select name="position_id" id="cbPosition" onchange="jQuery('#pgv').val('1');jQuery.stats.getStat()"></select>					</div>				</div>				<div class="dib hide">					<div>						<select name="round" id="rndsb" disabled>						</select>					</div>				</div>				<!--<div class="dib">				<label for="tsb">				<?php echo __('Team', FV_DOMAIN);?>				</label>				<div>				<select name="" id="tsb">				<option value="">All</option>				</select>				</div>				</div>				<div class="dib">				<label for="tsb">				<?php echo __('Stat type', FV_DOMAIN);?>				</label>				<div>				<select name="" id="tsb">				<option value=""></option>				</select>				</div>				</div>				<div class="dib">				<button id="btnSearch">Search</button>				</div>-->			</div>			<!--//filter-->            <div id="wrap_outerdiv">                <div class="outerdiv">                    <b class="data_status" style="display: none"></b>                    <div id="tbl" class="tbl-wrapper innerdiv">                        <table border="1">                            <thead></thead>                            <tbody></tbody>                        </table>                    </div>                </div>            </div>			<!--//table-->			<div id="pg" class="pg-wrapper"></div>			<!--//pagination-->			<input type="hidden" name="pg" id="pgv" value="1" />		</div><!--#hidbox-->		<div id="emptybox" class="hide">No event</div>	</form></div><script>	jQuery.stats.init('<?php echo json_encode($aPools)?>', '<?php echo json_encode($aPos)?>', '<?php echo json_encode($aTeams)?>', '<?php echo json_encode($aRounds)?>', '<?php echo $is_loggedin;?>');	jQuery("#fstat").submit(function(){jQuery.stats.getStat();return false;});</script><?php else:?>    <?php echo __("Only premium user can see this function, please contact with site admin for more detail", FV_DOMAIN);?><?php endif;?>