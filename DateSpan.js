DateSpan = {};

// timezone should be the non-daylight savings offset (e.g. -0600 for central US)
// e.g. user says event is at T:00 central. That's either DST or not, depending on the date. The timezone here is the non-DST offset.
// So initially parse the date using the non-DST offset for T:00. If it's not DST on this date, that is right. But if it is, the parsed date is wrong - adjust to (T+1):00.
// So now we have a time which is right for the non-DST offset timezone.

// example:
// 8:00, March 20
//      parsed to 8:00, -0600   =>  displayed as 9:00, -0500
//      adjust to 7:00, -0600   =>  displayed as 8:00, -0500
//      considered as 8:00, -0500 by moment
//      compare with moment() -> 8:00, -0500 at the clock's 8:00 on that day
DateSpan.init = function(timezone)
{
    this.timezone = timezone;
}

DateSpan.timeInDataInterval = function(start, end)
{
    return this.timeInInterval(this.dataToTime(start), this.dataToTime(end));
}

DateSpan.timeInInterval = function(start, end)
{
    var days = Math.floor(end.diff(start, 'days', true));
    var hours = Math.floor(end.diff(start, 'hours', true) % 24);
    var minutes = Math.floor(end.diff(start, 'minutes', true) % 60);
    var seconds = Math.floor(end.diff(start, 'seconds', true) % 60);

    return {'d': days,
            'h': hours,
            'm': minutes,
            's': seconds};
}

DateSpan.timeTilTime = function(t)
{
    return this.timeInInterval(moment(), t);
}

DateSpan.dataToTime = function(data)
{
    var time = moment(data+", "+this.timezone, "YYYY-MM-DD, HH:mm, Z");

    // adjust since it's parsed with non-DST timezone - see note on init
    if (time.isDST())
        time.subtract('hours', 1);

    return time;
}

DateSpan.timeTilData = function(data)
{
    var time = this.dataToTime(data);
    return this.timeTilTime(time);
}

DateSpan.schoolDaysTilData = function(end)
{
    return this.schoolDaysInInterval(moment(), this.dataToTime(end));
}

DateSpan.schoolDaysInInterval = function(start, end)
{
    var days = 0;

    while (start < end)
    {
        // If the day is a weekday... (Sun: 0, Sat: 6)
        if (start.day() % 6 != 0) {
            days++;
        }

        start.add('days', 1);
    }

    return days;
}
