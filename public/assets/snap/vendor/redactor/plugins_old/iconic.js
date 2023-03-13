(function($)
{
    $.Redactor.prototype.iconic = function()
    {
        return {
            init: function ()
            {
                var icons = {
                    'html': '<i class="fa fa-code"></i>',
                    'format': '<i class="fa fa-paragraph"></i>',
                    'lists': '<i class="fa fa-list"></i>',
                    'snaplink': '<i class="fa fa-link"></i>',
                    'horizontalrule': '<i class="fa fa-minus"></i>',
                    'assets': '<i class="fa fa-picture-o"></i>'
                };
 
                $.each(this.button.all(), $.proxy(function(i,s)
                {
                    var key = $(s).attr('rel');
 
                    if (typeof icons[key] !== 'undefined')
                    {
                        var icon = icons[key];
                        var button = this.button.get(key);
                        this.button.setIcon(button, icon);
                    }
 
                }, this));
            }
        };
    };
})(jQuery);