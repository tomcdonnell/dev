/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(initForm);

function initForm() {

    // init datepicker
    $("#timesheetCal").datepicker({
        autoSize: true,
        showOn: 'button',
        buttonText: 'Calendar',
        firstDay: 1,
        showAnim: 'fadeIn',
        showButtonPanel: true,
        showMonthAfterYear: true,
        dateFormat: "yy-mm-dd",
        onClose: function() {
            var date = $(this).val().split('-');
            $(location).attr('href', "/timesheet/day-view/y/"+date[0]+"/m/"+date[1]+"/d/"+date[2]);
        }
    });

    $(".ui-dialog-titlebar-close").hide();
    $(".hidden").hide();
    $(".timesheetDayTable input:button").button();
    populateActivityList();

    // View & Calendar Button Bar
    $("#day").button().click(function() {
        $(location).attr('href', '/timesheet/day-view');
        return false;
    }).next().button().click(function() {
        alert('Week view coming soon');
        return false;
    }).next().button({
        text: false,
        icons: {
            primary: "ui-icon-calendar"
        }
    }).click(function() {
        $("#timesheetCal").datepicker("show");
        return false;
    }).parent().buttonset();

    // Action Bar for each entry
    $(".entryEdit").button({
        text: false,
        icons: {
            primary: "ui-icon-pencil"
        }
    }).next().button({
        text: false,
        icons: {
            primary: "ui-icon-closethick"
        }
    }).parent().buttonset();

    $(".entryEdit").live('click', toggleRowToEdit);
    $(".entryRemove").live('click', removeTaskRow);
    $("#saveEntryBtn").click(saveNewTaskRow);

    $(".hours").live('keydown', function(ev) {
        var keyCode = ev.which;
        
        if($(this).val().indexOf('.'));

        var isStandard = (keyCode > 47 && keyCode < 58);
        var isExtended = (keyCode > 95 && keyCode < 106);

        if($(this).val().indexOf('.') == -1) {
            validKeyCodes = ',8,37,38,39,40,46,110,190,';
        } else {
            validKeyCodes = ',8,37,38,39,40,46,';
        }
        var isOther = (validKeyCodes.indexOf(',' + keyCode + ',') > -1);

        if (isStandard || isExtended || isOther) {
            return true;
        } else {
            return false;
        }
    }).live('blur', function() {
        var $input = $(this);
        var value = $input.val();

        value = Math.round(value*100)/100;
        $input.val(value);
    });

    calculateTotalHours();
}

function toggleRowToEdit() {

    var editTaskRow = $(this).parent().parent().parent();
    var timelogId =  editTaskRow.attr('id').replace("taskRow-", "");

    var origTaskRowHtml = editTaskRow.html();

    origActivityId = editTaskRow.find(".activityIdLabel").val();
    origTaskId = editTaskRow.find(".taskIdLabel").val();
    origTaskHours = editTaskRow.find(".entryHours").html();
    origTaskComment = $.trim(editTaskRow.find(".entryComment").html());

    editingTaskHtml  = '<td class="activityCol">';
    editingTaskHtml += '<select id="activityId-'+timelogId+'">';
    editingTaskHtml += '</select>';
    editingTaskHtml += '<br/>';
    editingTaskHtml += '<select id="taskId-'+timelogId+'">';
    editingTaskHtml += '</select>';
    editingTaskHtml += '<p>';
    editingTaskHtml += '<input id="saveEditBtn-'+timelogId+'" type="button" value="Save" />';
    editingTaskHtml += '<input id="cancelEditBtn-'+timelogId+'" type="button" value="Cancel" />';
    editingTaskHtml += '</p>';
    editingTaskHtml += '</td>';
    editingTaskHtml += '<td class="hoursCol">';
    editingTaskHtml += '<input class="hours" id="editHours-'+timelogId+'" type="text" value="'+parseFloat(origTaskHours).toFixed(2)+'" />';
    editingTaskHtml += '</td>';
    editingTaskHtml += '<td class="commentCol" colspan="2">';
    editingTaskHtml += '<textarea class="comment" id="editComment-'+timelogId+'" rows="2">'+origTaskComment+'</textarea>';
    editingTaskHtml += '</td>';

    editTaskRow.html(editingTaskHtml);
    populateActivityList(timelogId, origActivityId, origTaskId);

    $(".timesheetDayTable input:button").button();

    $("#cancelEditBtn-"+timelogId).live('click', function() {
        editTaskRow.html(origTaskRowHtml);
    });

    $("#saveEditBtn-"+timelogId).bind('click', {
        "origTaskRowHtml" : origTaskRowHtml
    }, saveEditTaskRow);

    return false;
}

function saveEditTaskRow(ev) {
    var timelogId = ev.target.id.replace("saveEditBtn-", "");

    editActivitySelect = $("#taskRow-"+timelogId).find("#activityId-"+timelogId);

    $.ajax({
        url: "/timesheet/save-time-log",
        type: "POST",
        data: {
            "id": timelogId,
            "date": $("#timesheetCal").val(),
            "deleted": 0,
            "idStaff": $("#idStaff").val(),
            "idTask" : $("#taskId-"+timelogId+" option:selected").val(),
            "hours" : $("#taskRow-"+timelogId).find("#editHours-"+timelogId).val(),
            "comment" : $("#taskRow-"+timelogId).find("#editComment-"+timelogId).val()
        },
        dataType: "json",
        async:false,
        success: function(json){

            editedActivityIdLabel = $("#taskRow-"+timelogId).find("#activityId-"+timelogId+" option:selected").val();
            editedTaskIdLabel = $("#taskRow-"+timelogId).find("#taskId-"+timelogId+" option:selected").val();
            editedActivityId = $("#taskRow-"+timelogId).find("#activityId-"+timelogId+" option:selected").text();
            editedTaskId = $("#taskRow-"+timelogId).find("#taskId-"+timelogId+" option:selected").text();
            editedHours = $("#taskRow-"+timelogId).find("#editHours-"+timelogId).val();
            editedComment = $("#taskRow-"+timelogId).find("#editComment-"+timelogId).val();

            $("#taskRow-"+timelogId).html(ev.data.origTaskRowHtml);

            $("#taskRow-"+timelogId).find(".activityIdLabel").val(editedActivityIdLabel);
            $("#taskRow-"+timelogId).find(".taskIdLabel").val(editedTaskIdLabel);
            $("#taskRow-"+timelogId).find(".activityLabel").text(editedActivityId);
            $("#taskRow-"+timelogId).find(".taskLabel").html(editedTaskId);
            $("#taskRow-"+timelogId).find(".entryHours").html(parseFloat(editedHours).toFixed(2));
            $("#taskRow-"+timelogId).find(".entryComment").html(editedComment);

            calculateTotalHours();
        }
    });
}

function saveNewTaskRow(ev) {

    if($(".taskAdderRow").find("#activityId option:selected").val() == 0) {
        alert('Please select an activity');
        return false;
    } else if($(".taskAdderRow").find("#taskId option:selected").val() == 0) {
        alert('Please select a task');
        return false;
    } else if ($(".taskAdderRow").find("#addHours").val() <= 0){
        alert('Please enter the hours spent on task');
        return false;
    } else {
        $.ajax({
            url: "/timesheet/save-time-log",
            type: "POST",
            data: {
                "date": $("#timesheetCal").val(),
                "deleted": 0,
                "idStaff": $("#idStaff").val(),
                "idTask" : $("#taskId option:selected").val(),
                "hours" : $("#addHours").val(),
                "comment" : $("#addComment").val()
            },
            dataType: "json",
            async:false,
            success: function(json){
                newRow = $("#blankEntry").clone().attr("id","taskRow-"+json.timelog.id);

                newActivityIdLabel = $(".taskAdderRow").find("#activityId option:selected").val();
                newTaskIdLabel = $(".taskAdderRow").find("#taskId option:selected").val();
                newActivityId = $(".taskAdderRow").find("#activityId option:selected").text();
                newTaskId = $(".taskAdderRow").find("#taskId option:selected").text();
                newHours = $(".taskAdderRow").find("#addHours").val();
                newComment = $(".taskAdderRow").find("#addComment").val();

                newRow.clone().appendTo(".timesheetDayTable tbody").fadeIn(500);

                $("#taskRow-"+json.timelog.id).find(".activityIdLabel").val(newActivityIdLabel);
                $("#taskRow-"+json.timelog.id).find(".taskIdLabel").val(newTaskIdLabel);
                $("#taskRow-"+json.timelog.id).find(".activityLabel").html(newActivityId);
                $("#taskRow-"+json.timelog.id).find(".taskLabel").html(newTaskId);
                $("#taskRow-"+json.timelog.id).find(".entryHours").html(parseFloat(newHours).toFixed(2));
                $("#taskRow-"+json.timelog.id).find(".entryComment").html(newComment);

                $("#taskRow-"+json.timelog.id).find(".entryHours").addClass("realHours");

                calculateTotalHours();

                $(".taskAdderRow").find("#activityId").empty();
                $(".taskAdderRow").find("#taskId").empty();
                $(".taskAdderRow").find("#activityId").empty();
                $(".taskAdderRow").find("#taskId").empty();
                $(".taskAdderRow").find("#addHours").val('');
                $(".taskAdderRow").find("#addComment").val('');

                populateActivityList();
            }
        });

        return true;
    }
}

function removeTaskRow(ev) {

    var taskId =  $(this).parent().parent().parent().attr('id').replace("taskRow-", "");

    $.ajax({
        url: "/timesheet/set-deleted",
        global: false,
        type: "POST",
        data: {
            "taskId" : taskId,
            "deleted" : 1
        },
        dataType: "json",
        async:false,
        success: function(json){
            if(json.success) {
                $("#taskRow-"+taskId).fadeOut(500, function() {
                    $(this).remove();
                    calculateTotalHours();
                });
            }
        }
    });
    return false;
}

function calculateTotalHours() {

    var totalHours = 0;

    $(".realHours").each(function(index,ele) {
        var taskHours = parseFloat(ele.innerHTML);
        totalHours += taskHours;
    });

    $("#dayTotal").html(parseFloat(totalHours).toFixed(2));
}

function populateActivityList(timelogId, activityId, taskId) {

    if(timelogId) {
        selectEleId = "#activityId-"+timelogId;
        selectedActivityId = activityId;
        selectedTaskId = taskId;
    } else {
        selectEleId = "#activityId";
        selectedActivityId = null;
        selectedTaskId = null;
    }

    $(selectEleId).bind('change', {
        "timelogId": timelogId,
        "selectedActivityId": selectedActivityId,
        "selectedTaskId": selectedTaskId
    }, populateTaskList);
    
    $.ajax({
        url: "/activity/get-activity-by-staff-id",
        global: false,
        type: "POST",
        dataType: "json",
        async:false,
        success: function(json){
            $("<option/>").attr("value", 0).text("Select Activity...").appendTo(selectEleId);
            for(i=0; i < json.length; i++) {
                var optionName = json[i].activityNo+" - "+json[i].activityTitle;
                var optionValue = json[i].id;

                $("<option/>").attr("value", optionValue).text(optionName).appendTo(selectEleId);
            }

            $(selectEleId).trigger('change');
            $(selectEleId).unbind('change');
            $(selectEleId).bind('change', {
                "timelogId": timelogId
            }, populateTaskList);
        }
    });
}

function populateTaskList(ev) {

    if(ev.data.timelogId) {
        taskSelectEleId = "#taskId-"+ev.data.timelogId;
        activitySelectEleId = "#activityId-"+ev.data.timelogId;
    } else {
        taskSelectEleId = "#taskId";
        activitySelectEleId = "#activityId";
    }

    $(taskSelectEleId).empty();

    if(ev.data.selectedActivityId) {
        $(activitySelectEleId).val(ev.data.selectedActivityId);
    }

    $.ajax({
        url: "/task/get-tasks-by-activity-id",
        global: false,
        type: "POST",
        data: {
            "idActivity" : $(activitySelectEleId+" option:selected").val()
        },
        dataType: "json",
        async:false,
        success: function(json){
            $("<option/>").attr("value", 0).text("Select Task...").appendTo(taskSelectEleId);
            for(i=0; i < json.length; i++) {
                var optionName = json[i].taskTitle;
                var optionValue = json[i].id;

                $("<option/>").attr("value", optionValue).text(optionName).appendTo(taskSelectEleId);
            }

            if(ev.data.selectedTaskId) {
                $(taskSelectEleId).val(ev.data.selectedTaskId);
            }
        }
    });
}