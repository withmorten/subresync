var timePlaceHolder = "00:00:00,000";
var timeMask = "99:99:99,999";
var timeFormId = "timeform";
var timePlusOneId = "plus";
var timeClassId = "time";
var timeLineClassId = "timeline";
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

$.fn.tagName = function() {
  return this.prop("tagName").toLowerCase();
};

$.fn.forceCursorPosition = function(pos) {
  this.each(function(index, elem) {
    if (elem.setSelectionRange) {
      elem.setSelectionRange(pos, pos);
    } else if (elem.createTextRange) {
      var range = elem.createTextRange();
      range.collapse(true);
      range.moveEnd('character', pos);
      range.moveStart('character', pos);
      range.select();
    }
  });
  return this;
};

function addTimeLine(event = false) {
    var timeForm = $("#"+timeFormId);
    var timeFormTable = $("#formtable");
    
    var timeLine = $("<tr>");
    timeLine.addClass(timeLineClassId);
    
    var timeLineNumberSpan = $("<td>");
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
        timeInput.focus(focusHandler);
        // timeInput.click(clickHandler);
        
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
        var timeLineNum = (i+1);
        timeLine.attr("id", timeLineClassId+timeLineNum);
        $(timeLine.find('td')[0]).text(timeLineNum);
        
        var timeInputs = timeLine.find('input');
        
        $.each(timeInputTypes, function(i, timeInputType) {
            $(timeInputs[i]).attr("name", timeLineClassId+"["+i+"]["+timeInputType+"]");
        });
    });;
}

function sleep(time) {
  return new Promise((resolve) => setTimeout(resolve, time));
}

function focusHandler(event) {
    event.preventDefault();
    console.log(eventSrc = event.target || event.srcElement);
}

function clickHandler(event) {
    // $(event.target || event.srcElement).trigger();
    eventSrc = event.target || event.srcElement;
    // sleep(1000).then(() => {
        // eventSrc.click();
    // });
    // eventSrc.setCursorPosition(3);
    // console.log(event.target || event.srcElement);
    // event.preventDefault();
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