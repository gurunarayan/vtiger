{strip}
    <div style="width: 270px;">
        <div class="modelContainer" >
           
            {if $ACTIVE_TIMER}
                <div class="modal-header contentsBackground" style="text-align: center; border-bottom: none; "><h3 style="color: #004123;">{'Active Timers'}</h3></div>
                <table class="table table-bordered listViewEntriesTable" id="listActiveTimers">

                    {foreach from=$ACTIVE_TIMER  item=TIMER_DATA }
                        <tr id="record_{$TIMER_DATA['recordid']}">
                            <td class="summaryViewEntries">
                            <span class="alignCenter " style="color: #004123;">
                                <a class="record_name" href="index.php?module={$TIMER_DATA['formdata']['module']}&record={$TIMER_DATA['recordid']}&view=Detail" style="display:inline-block;overflow: hidden;width: 145px;white-space: nowrap;text-overflow: ellipsis;-o-text-overflow: ellipsis;-ms-text-overflow: ellipsis;" title="{if $TIMER_DATA['name'] neq ''} {$TIMER_DATA['name']} {else} - {/if}">
                                    {if $TIMER_DATA['name'] neq ''} {$TIMER_DATA['name']} {else} - {/if}
                                </a>
                            </span>
                            </td>
                            <td class="summaryViewEntries " >
                            <span  class="alignCenter" style="color: #2787e0;">
                                {if $TIMER_DATA['timeTrackerTotal'] neq ''} {$TIMER_DATA['timeTrackerTotal']} {else} - {/if}
                            </span>
                            </td>
                            <td class="summaryViewEntries ">
                             <span class="alignMiddle">
                                <a class="play_icon" href="index.php?module={$TIMER_DATA['formdata']['module']}&record={$TIMER_DATA['recordid']}&view=Detail&go_back=1">
                                 <img {if $TIMER_DATA['status'] eq 'pause'} src="layouts/v7/modules/Settings/OS2TimeTracker/images/pause.png"  {elseif $TIMER_DATA['status'] eq 'running'} src="layouts/v7/modules/Settings/OS2TimeTracker/images/play.png" {else} {/if} alt="Go back record" style="width:20px;height:20px;"/>
                                </a>
                             </span>
                            </td>
                        </tr>
                    {/foreach}
                    <tr class="hide row_base">
                        <td class="summaryViewEntries">
                        <span class="alignCenter " style="color: #004123;">
                            <a class="record_name" href="javascript:voice(0)" style="display:inline-block;overflow: hidden;width: 145px;white-space: nowrap;text-overflow: ellipsis;-o-text-overflow: ellipsis;-ms-text-overflow: ellipsis;" title=""></a>
                        </span>
                        </td> 
                        <td class="summaryViewEntries" >
                            <span class="alignCenter timeTrackerTotal timeValue" style="color: #2787e0;"></span>
                        </td>
                        <td class="summaryViewEntries ">
                         <span class="alignMiddle">
                            <a class="play_icon" href="javascript:voice(0)">
                                <img src="layouts/v7/modules/Settings/OS2TimeTracker/images/pause.png" alt="Go back record" style="width:20px;height:20px;"/>
                            </a>
                         </span>
                        </td>
                    </tr>
                </table>
                <input type="hidden" id="listActiveTimer" value="1" />
            {else}
                <input type="hidden" id="listActiveTimer" value="0" />
            {/if}
            
        </div>
    </div>
{/strip}
