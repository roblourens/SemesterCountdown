var U = {};

U.init = function(d)
{
    this.data = d;
    this.events = d['events'];
    this.detailLevel = 4;

    DateSpan.init(d['timezone']);
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
    var first = true;

    var ctnt = "<div class='tableDiv'>";
    var firstT;
    for (eventName in this.events)
    {
        // the event object
        var e = this.events[eventName];

        // the days, hours, minutes, seconds until the time
        var t = DateSpan.timeTilData(e['time']);

        // if the time finished more than 1 day ago, don't output the event
        if (t['d'] <= -1)
            continue;
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
        ctnt += '<div class="l">There '+this.isAre(roundedDays)+' <span class="days">'+roundedDays+'</span></div>';

        ctnt += "<div class='r'>";
        // /total
        if (e['refDate'])
        {
            var totalDays = DateSpan.roundedDays(DateSpan.timeInDataInterval(e['refDate'], e['time']));
            ctnt += '<span class="total">/'+totalDays+'</span> ';
        }

        // # days phrase
        ctnt += this.pluralize(roundedDays, 'day')+' '+e['phrase']+'</div>';
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
        if (e['refDate'])
        {
            var refTime = DateSpan.dataToTime(e['refDate']);
            var endTime = DateSpan.dataToTime(e['time']);
            var now = moment();

            var complete = now.diff(refTime, 'seconds', true);
            var total = endTime.diff(refTime, 'seconds', true);

            var percStr = this.zeroFill(Math.floor(100000*complete/total)/1000, 3);

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
    }

    $('#wrapper').html(ctnt);
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

