var timePlaceHolder = "00:00:00,000";
var timeMask = "99:99:99,999";
var timeFormId = "timeform";
var timePlusOneId = "plus";
var timeClassId = "time";
var timeLineClassId = "timeline";
var timeLineNumberClassId = "linenumber";
var theaderId = 'theader';
var defaultLines = 5;
var timeInputTypes = ["sync", "for"];

$(function(){
    $('#'+theaderId).append(createPlusOneButton('h'));
    
    $('input[class="'+timePlusOneId+'"]').click(addTimeLine);
    for(var i = 0; i < defaultLines; i++) {
        addTimeLine();
    }
});

function addTimeLine(event = false) {
    var timeForm = $("#"+timeFormId);
    var timeFormTable = $("#formtable");
    
    var timeLine = $("<tr>");
    timeLine.addClass(timeLineClassId);
    
    var timeLineNumberSpan = $("<td>");
    timeLineNumberSpan.addClass(timeLineNumberClassId);
    timeLineNumberSpan.addClass(timeFormId);
    timeLine.append(timeLineNumberSpan);
    
    $.each(timeInputTypes, function(i, timeInputType) {
        var timeInputTd = $("<td>");
        var timeInput = $("<input>");
        
        timeInput.attr("type", "text");
        timeInput.addClass(timeClassId);
        timeInput.addClass(timeFormId);
        timeInput.attr("placeholder", timePlaceHolder);
        timeInput.attr("value", timePlaceHolder);
        timeInput.mask(timeMask);
        timeInput.off('focus');
        
        timeInputTd.append(timeInput);
        timeLine.append(timeInputTd);
    });
    
    timeLine.append(createPlusOneButton());
    
    if(event !== false) {
        var timeLineSource = $((event.target || event.srcElement).parentElement.parentElement);
        timeLineSource.after(timeLine);
    } else {
        $('#'+theaderId).after(timeLine);
    }
    
    var timeLines = timeFormTable.find("."+timeLineClassId);
    
    $.each(timeLines, function(i, timeLine) {
        timeLine = $(timeLine);
        var timeLineNum = i+1;
        timeLine.attr("id", timeLineClassId+timeLineNum);
        $(timeLine.find('td')[0]).text(timeLineNum);
        
        var timeInputs = timeLine.find('input');
        
        $.each(timeInputTypes, function(j, timeInputType) {
            $(timeInputs[j]).attr("name", timeLineClassId+"["+i+"]["+timeInputType+"]");
        });
    });;
}

function createPlusOneButton(tag = 'd') {
    var plusOneTd = $("<t"+tag+">");
    var plusOneButton = $("<input>");
    
    plusOneButton.attr("type", "button");
    plusOneButton.attr("value", "+");
    plusOneButton.addClass(timeFormId);
    plusOneButton.addClass(timePlusOneId);
    plusOneButton.click(addTimeLine);
    
    plusOneTd.append(plusOneButton);
    
    return plusOneTd;
}