Conf = {};

// apply custom styles and whatnot
Conf.init = function(confJson)
{
    this.primaryColor = confJson['color1'];     // gradient top
    this.gradBottom = confJson['gradBottom'];
    this.secondaryColor = confJson['color2'];   // text
    this.shadowColor = confJson['shadowColor']; // text-shadow
    this.detailColor = confJson['color3'];      // % and school days
    this.timelinePast = confJson['timelinePast'];
    this.timelineFuture = confJson['timelineFuture'];
    this.name = confJson['name'];

    /* Old browsers */
    $('body').css('background', this.primaryColor);
    /* FF3.6+ */
    $('body').css('background', '-moz-linear-gradient(top, '+this.primaryColor+' 0%, '+this.gradBottom+' 100%)');
    /* Chrome,Safari4+ */
    $('body').css('background', '-webkit-gradient(linear, left top, left bottom, color-stop(0%,'+this.primaryColor+'), color-stop(100%,'+this.gradBottom+'))'); 
    /* Chrome10+,Safari5.1+ */
    $('body').css('background', '-webkit-linear-gradient(top, '+this.primaryColor+' 0%,'+this.gradBottom+' 100%)');
    /* Opera 11.10+ */
    $('body').css('background', '-o-linear-gradient(top, '+this.primaryColor+' 0%,'+this.gradBottom+' 100%)');
    /* IE10+ */
    $('body').css('background', '-ms-linear-gradient(top, '+this.primaryColor+' 0%,'+this.gradBottom+' 100%)');
    /* IE10+ */
    $('body').css('background', '-ms-linear-gradient(top, rgba('+this.primaryColor+') 0%,rgba('+this.gradBottom+') 100%)');
    /* W3C */
    $('body').css('background', 'linear-gradient(top, '+this.primaryColor+' 0%,'+this.gradBottom+' 100%)');
    /* IE6-9 */
    $('body').css('filter', 'progid:DXImageTransform.Microsoft.gradient( startColorstr='+this.primaryColor+', endColorstr='+this.gradBottom+',GradientType=0 )'); 

    $('body').css('color', this.secondaryColor);
    $('body').css('text-shadow', '1px 1px 0px '+this.shadowColor);
    $('.detailsButton').css('color', this.secondaryColor);

    $('#title').html(this.name);
    $('#title').css('color', this.detailColor);
}

// stuff that directly affects dynamic elements
Conf.doCssUpdate = function()
{
    $('.special').css('color', this.detailColor);
}