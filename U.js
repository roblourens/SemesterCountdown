var U = {};

U.init = function(d)
{
    this.data = d;
    this.timezone = d['timezone'];
    this.events = d['events'];
}

U.update = function()
{
    console.log('updating');

    for (eventName in this.events)
    {
        // the event object
        var e = this.events[eventName];

        // the days, hours, minutes, seconds until the time
        var t = DateSpan.timeTilData(e['time']);

        // update the main event row
        $('#'+eventName+'_isAre').html(this.isAre(t['d']));
        $('#'+eventName+'_daysTil').html(t['d']);
        $('#'+eventName+'_days').html(this.pluralize(t['d'], 'day'));

        // if a refDate is set, update the 'time since' fields
        if (e['refDate'])
        {
            var refDateTime = this.dataToTime(e['refDate']);
            var eTime = this.dataToTime(e['time']);
            
            var totalDays = this.timeInInterval(refDateTime, eTime)['d'];
            $('#'+eventName+'_total').html(totalDays);
        }
        //$('#'+eventName+)
    }
}

U.isAre = function(n)
{
    return n == 1 ? "is" : "are";
}

U.pluralize = function(n, w)
{
    return n == 1 ? w : w+"s";
}
