DateSpan = {};

DateSpan.timeInInterval = function(start, end)
{
    // normalize out of DST
    if (start.isDST())
        start.add('hours', 1);

    if (end.isDST())
        end.add('hours', 1);

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
    return moment(data+", "+this.timezone, "YYYY-MM-DD, HH:mm, Z");
}

DateSpan.timeTilData = function(data)
{
    time = this.dataToTime(data);
    return this.timeTilTime(time);
}
