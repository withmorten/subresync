var timePlaceHolder = "00:00:00,000";
var timeMask = "99:99:99,999";
var timeFormId = "timeform";
var timePlusOneId = "plus";
var timeClassId = "time";
var timeLineClassId = "timeline";
var timeLineNumberClassId = "linenumber";
var timeLineSignClassId = "sign";
var timeTableId = "timetable";
var theaderId = 'theader';
var tfooterId = 'tfooter';
var fileRowId = 'filerow';
var timeInputTypes = ["dura", "sign", "sync"];
var defaultTimeSign = getDefaultSign();

$(function(){
    initGui();
    // create x timelines by default
    for(var i = 0; i < 6; i++) addTimeLine();
});

function initGui() {
    var timeForm = $("<form>");
    timeForm.attr("method", "post");
    timeForm.attr("enctype", "multipart/form-data");
    timeForm.attr("id", timeFormId);

    var maxFileSizeInput = $("<input>");
    maxFileSizeInput.attr("type", "hidden");
    maxFileSizeInput.attr("name", "max_file_size");
    maxFileSizeInput.attr("value", "2097152");
    timeForm.append(maxFileSizeInput);

    var timeTable = $("<table>");
    timeTable.attr("id", timeTableId);

    var tHeaderRow = $("<tr>");
    tHeaderRow.attr("id", theaderId);

    tHeaderRow.append($("<td>"));

    var tHeaderField = $("<th>");
    tHeaderField.attr("colspan", "3");
    tHeaderField.text("resyncs subs via timings");
    tHeaderRow.append(tHeaderField);
    tHeaderRow.append(plusOneButton().click(addTimeLine));

    timeTable.append(tHeaderRow);

    var fileRow = $("<tr>");
    fileRow.attr("id", fileRowId);
    fileRow.append($("<td>"));

    var fileField = $("<td>");
    fileField.attr("colspan", "4");

    var fileInput = $("<input>");
    fileInput.attr("type", "file");
    fileInput.attr("accept", ".srt");
    fileInput.attr("name", "srtfiles[]");
    fileInput.attr("multiple", "multiple");

    fileField.append(fileInput);
    fileRow.append(fileField);

    timeTable.append(fileRow);

    var tSubmit = $("<td>");
    var inputSubmit = $("<input>");
    inputSubmit.attr("name", "submit");
    inputSubmit.attr("type", "submit");
    inputSubmit.attr("value", "resync");
    tSubmit.append(inputSubmit);

    var tReset = $("<td>");
    var inputReset = $("<input>");
    inputReset.attr("type", "reset");
    inputReset.attr("value", "reset");
    inputReset.attr("onclick", "return confirm('Really?');");
    tReset.append(inputReset);

    var tFooterRow = $("<tr>");
    tFooterRow.attr("id", tfooterId);
    tFooterRow.append($("<td>").attr("colspan", "1"));
    tFooterRow.append(tSubmit);
    tFooterRow.append($("<td>"));
    tFooterRow.append(tReset);
    tFooterRow.append($("<td>"));

    timeTable.append(tFooterRow);
    timeForm.append(timeTable);

    var textArea = $("<textarea>");
    textArea.attr("id", "jsonarea");
    textArea.attr("name", "jsonarea");
    textArea.val($("#phparea").val());

    timeForm.append(textArea);

    $("body").append(timeForm);
}

function addTimeLine(event = false) {
    // gets timeTable
    var timeFormTable = $("#"+timeTableId);

    // initialising new table row
    var timeLine = $("<tr>");
    timeLine.addClass(timeLineClassId);

    // td for timeline Number in front
    var timeLineNumberTd = $("<td>");
    timeLineNumberTd.addClass(timeLineNumberClassId);
    timeLineNumberTd.addClass(timeFormId);
    timeLine.append(timeLineNumberTd);

    // add the timings input fields
    $.each(timeInputTypes, function(i, timeInputType) {
        var timeInputTd = $("<td>");
        var timeInput = $("<input>");

        timeInput.attr("type", "text");

        timeInput.addClass(timeClassId);
        timeInput.addClass(timeFormId);

        if(timeInputType === "sign") {
            timeInput.attr("value", defaultTimeSign);
            timeInput.attr("readonly", "readonly");
            timeInput.addClass(timeLineSignClassId);
            timeInput.click(toggleSignage);
        } else {
            timeInput.attr("placeholder", timePlaceHolder);
            timeInput.attr("value", timePlaceHolder);

            // focus off AFTER so it doesn't get selected onclick
            timeInput.mask(timeMask);
            timeInput.off('focus');
        }

        timeInputTd.append(timeInput);
        timeLine.append(timeInputTd);
    });

    // + button
    timeLine.append(plusOneButton().click(addTimeLine));

    // depending on whether it was created by default or by click,
    // append the timeLine at the bottom or below the clicked one
    if(event !== false) {
        var timeLineSource = $((event.target || event.srcElement).parentElement.parentElement);
        timeLineSource.after(timeLine);
    } else {
        $('#'+theaderId).after(timeLine);
    }

    // number all the timelines properly, especially after adding one inbetween
    var timeLines = timeFormTable.find("."+timeLineClassId);
    $.each(timeLines, function(i, timeLine) {
        timeLine = $(timeLine);
        var timeLineNum = i+1;
        timeLine.attr("id", timeLineClassId+timeLineNum);
        $(timeLine.find('td')[0]).text(timeLineNum);

        var timeInputs = timeLine.find('input');

        $.each(timeInputTypes, function(j, timeInputType) {
            $(timeInputs[j]).attr("name", timeLineClassId+"s["+i+"]["+timeInputType+"]");
        });
    });
}

function toggleSignage(event) {
    signInput = $(event.target || event.srcElement);
    if(signInput.attr("value") === "+") signInput.attr("value", "-");
    else signInput.attr("value", "+");
}

function plusOneButton() {
    var plusOneTd = $("<td>");
    var plusOneButton = $("<input>");

    plusOneButton.attr("type", "button");
    plusOneButton.attr("value", "+");

    plusOneButton.addClass(timeFormId);
    plusOneButton.addClass(timePlusOneId);

    return plusOneTd.append(plusOneButton);
}

function getDefaultSign() {
    if(getUrlParameter("s") === "p") {
        var sign = "+";
    } else {
        var sign = "-";
    }

    return sign;
}
