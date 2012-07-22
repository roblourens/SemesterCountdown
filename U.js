var U = {};

U.init = function(d)
{
    this.detailLevel = 4;
    this.isArgyled = false;

    // timeline canvas props
    this.w = 800;
    this.h = 75 ;
    this.tickH = 20;
    this.margin = 5;
    this.timelineEndDateFmtStr = "MMM D, YYYY";
    this.timelineMidDateFmtStr = "MMM D";

    DateSpan.init(d['timezone']);
    this.d = d;
}

U.moreDetails = function()
{
    this.detailLevel = Math.min(this.detailLevel+1, 4);
    this.update();
}

U.fewerDetails = function()
{
    this.detailLevel = Math.max(this.detailLevel-1, 0);
    this.update();
}

U.update = function()
{
    this.semester = DateSpan.priceIsRightSem(this.d['semesters']);
    if (!this.semester)
        return;

    var first = true;

    var ctnt = "<div class='tableDiv'>";
    var firstT;
    $.each(this.semester, function(eventName, e)
    {
        if (eventName == 'ref')
            return;

        // the days, hours, minutes, seconds until the time
        var t = DateSpan.timeTilData(e['time']);

        // if the time finished more than 1 day ago, don't output the event
        if (t['d'] <= -1)
            return;
        else
        {
            for (k in t)
                t[k] = Math.max(0, t[k]);
        }

        if (first)
            ctnt += '<div class="group" id="firstGroup">';
        else
            ctnt += '<div class="group">';

        // There are # days ...
        var roundedDays = DateSpan.roundedDays(t);
        ctnt += "<div class='row main'>";
        ctnt += '<div class="l">There '+U.isAre(roundedDays)+' <span class="days">'+roundedDays+'</span></div>';

        ctnt += "<div class='r'>";
        // /total
        if (e['ref'])
        {
            var totalDays = DateSpan.roundedDays(DateSpan.timeInDataInterval(e['ref'], e['time']));
            ctnt += '<span class="total">/'+totalDays+'</span> ';
        }

        // # days phrase
        ctnt += U.pluralize(roundedDays, 'day')+' '+e['phrase']+'</div>';
        ctnt += '</div>';

        $.each(['day', 'hour', 'minute', 'second'], function(i, v) {
            if (i < U.detailLevel)
            {
                ctnt += "<div class='row subtime'>";
                ctnt += '<div class="l num">'+t[v[0]]+'</div>';
                ctnt += '<div class="r">'+U.pluralize(t[v[0]], v)+'.</div>';
                ctnt += '</div>';
            }
        });

        // % complete
        if (e['ref'])
        {
            var refTime = DateSpan.dataToTime(e['ref']);
            var endTime = DateSpan.dataToTime(e['time']);
            var now = moment();

            var complete = DateSpan.diff(refTime, now);
            var total = DateSpan.diff(refTime, endTime);

            var percStr = U.zeroFill(Math.floor(100000*complete/total)/1000, 3);

            ctnt += '<div class="row special"><div class="num l">'+percStr+'</div>';
            ctnt += '<div class="r">% complete.</div></div>';
        }

        // # school days
        var schoolDays = DateSpan.schoolDaysTilData(e['time']);
        ctnt += '<div class="row special"><div class="l num">'+schoolDays+'</div>';
        ctnt += '<div class="r">school days.</div></div>';

        ctnt += '</div>';

        if (first)
        {
            firstT = t;
            first = false;
        }
    });

    $('#wrapper').html(ctnt);

    $("canvas").clearCanvas();

    // draw timeline
    this.drawLine({
        x1: this.margin, y1: this.h/2,
        x2: this.w-this.margin, y2: this.h/2,
        strokeStyle: Conf.timelineFuture
    });

    // draw beginning and end ticks
    this.drawLine({
        x1: this.margin-.5, y1: this.h/2-this.tickH/2,
        x2: this.margin-.5, y2: this.h/2+this.tickH/2,
        strokeStyle: Conf.timelinePast
    });
    this.drawLine({
        x1: this.w-this.margin+.5, y1: this.h/2-this.tickH/2,
        x2: this.w-this.margin+.5, y2: this.h/2+this.tickH/2,
        strokeStyle: Conf.timelineFuture
    });

    var refDate = DateSpan.dataToTime(this.semester['ref']);
    var refDateStr = this.format(refDate, this.timelineEndDateFmtStr);

    // write ref event time
    this.drawText({
        x: this.margin, y: this.h/2+this.tickH/2,
        text: refDateStr,
        align: "left", baseline: "top"
    });

    // find the last event
    var lastEvent = this.semester[Object.keys(this.semester)[0]];
    for (i in this.semester)
    {
        var e = this.semester[i];
        var date = DateSpan.dataToTime(e['time']);
        if (date > DateSpan.dataToTime(lastEvent['time']))
            lastEvent = e;
    }

    // draw the last event time
    var endDateStr = this.format(DateSpan.dataToTime(lastEvent['time']), this.timelineEndDateFmtStr);
    this.drawText({
        x: this.w-this.margin, y: this.h/2+this.tickH/2,
        text: endDateStr,
        align: "right", baseline: "top"
    });

    // draw other events
    $.each(this.semester, function(eventName, e)
    {
        if (eventName == 'ref')
            return;

        if (e != lastEvent)
        {
            var isPast = false;
            if (DateSpan.dataToTime(e['time']) < moment())
                isPast = true;

            U.drawEvent(e, refDate, lastEvent, isPast);
        }
    });

    // draw now dot
    var totalSecs = DateSpan.diff(refDate, DateSpan.dataToTime(lastEvent['time']));
    var nowPosPercent = DateSpan.diff(refDate, moment())/totalSecs;

    // draw past line
    this.drawLine({
        x1: this.margin, y1: this.h/2,
        x2: this.w*nowPosPercent, y2: this.h/2,
        strokeStyle: Conf.timelinePast
    });
    $("canvas").drawEllipse({
        fillStyle:Conf.secondaryColor,
        x: this.w*nowPosPercent, y: this.h/2, width: 10, height: 10, 
        fromCenter:true
    });
}

U.drawEvent = function(e, refDate, lastEvent, isPast)
{
    // position of event e between refDate and lastEvent
    var totalSecs = DateSpan.diff(DateSpan.dataToTime(lastEvent['time']), refDate);
    var secsToE = DateSpan.diff(DateSpan.dataToTime(e['time']), refDate);
    var posPercent = secsToE/totalSecs;
    var args = {
        x1: this.w*posPercent+.5, y1: this.h/2-this.tickH/2,
        x2: this.w*posPercent+.5, y2: this.h/2
    };

    if (isPast)
        args['strokeStyle'] = Conf.timelinePast;

    // draw half-tick
    this.drawLine(args);

    // draw text
    var eStr = e['name'] + ", " + this.format(DateSpan.dataToTime(e['time']), this.timelineMidDateFmtStr);
    this.drawText({
        x: this.w*posPercent, y: this.h/2-this.tickH/2,
        align: 'center', baseline: 'bottom',
        text: eStr
    });
}

U.drawLine = function(args)
{
    var baseProps = {
        strokeStyle: "#000",
        strokeWidth: 1,
        lineCap: 'square'
    };
    
    $("canvas").drawLine($.extend(baseProps, args));
}

U.drawText = function(args)
{
    var textBaseProps = {
        fillStyle: Conf.secondaryColor,
        font: "normal 10pt Telex, sans-serif",
        baseline: "middle"
    };

    $("canvas").drawText($.extend(textBaseProps, args));
}

U.isAre = function(n)
{
    return n == 1 ? "is" : "are";
}

U.pluralize = function(n, w)
{
    return n == 1 ? w : w+"s";
}

// fill w/zeros to d digits after the .
U.zeroFill = function(number, d)
{
    d -= number.toString().split('.')[1].length;
    if (d > 0)
        return number + new Array(d+1).join('0');

    return number;
}

U.argyle = function()
{
    var imageName = '';
    if (this.isArgyled)
        imageName = 'grid_noise.png';
    else
        imageName = 'argyle.png';

    $('#main').css('background-image', 'url('+imageName+')');
    this.isArgyled = !this.isArgyled;
}

U.format = function(d, formatStr)
{
    var u = moment(d);
    u.utc();
    u.add('minutes', d.zone());

    return u.format(formatStr);
}
