DateSpan = {};

// timezone should be the non-daylight savings offset (e.g. -0600 for central US)
// e.g. user says event is at T:00 central. That's either DST or not, depending on the date. The timezone here is the non-DST offset.
// So initially parse the date using the non-DST offset for T:00. If it's not DST on this date, that is right. But if it is, the parsed date is wrong - adjust to (T-1):00.

// example:
// 8:00, March 20
//      parsed to 8:00, -0600   =>  i.e. 9:00, -0500
//      adjust to 7:00, -0600   =>  i.e. 8:00, -0500
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
    var days = this.roundTowardsZero(end.diff(start, 'days', true));
    var hours = this.roundTowardsZero(end.diff(start, 'hours', true) % 24);
    var minutes = this.roundTowardsZero(end.diff(start, 'minutes', true) % 60);
    var seconds = this.roundTowardsZero(end.diff(start, 'seconds', true) % 60);

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
    if (start > end)
        return 0;

    var days = 0;

    // this function isn't completely generalized for a school day that goes
    // through midnight
    var startHour = 8;
    var endHour = 17;

    var date = moment(start);

    // normalize so we're counting the same thing
    if (date.hours() < endHour)
        date.hours(startHour);
    else
    {
        date.add('days', 1);
        date.hours(startHour);
    }

    while (date < end)
    {
        // If the day is a weekday... (Sun: 0, Sat: 6)
        if (date.day() % 6 != 0)
            days++;

        date.add('days', 1);
    }
    
    return days;
}

// takes the result of one the above timeInInterval function
DateSpan.roundedDays = function(interval)
{
    if (interval['h'] > 0 || interval['m'] > 0 || interval['s'] > 0)
        return interval['d'] + 1;
    else
        return interval['d'];
}

DateSpan.roundTowardsZero = function(t)
{
    if (t > 0)
        return Math.floor(t);
    else
        return Math.ceil(t);
}
