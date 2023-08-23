$(document).ready(()=>{

    $.fn.extend({
        hasClasses: function (selectors) {
            var self = this;
            for (var i in selectors) {
                if ($(self).hasClass(selectors[i]))
                    return true;
            }
            return false;
        }
    });
    $.fn.extend({
        removeClasses: function (selectors) {
            var self = this;
            for (var i in selectors) {
                if ($(self).removeClass(selectors[i]))
                    return true;
            }
            return false;
        }
    });

    $("input[type='text']").on('focusout',function (e){
        let text = $(e.currentTarget).val();
        text = text.replace('ة','ه');
        text = text.replace('ي','ى');
        text = text.replace('أ','ا');
        text = text.replace('إ','ا');
        $(e.currentTarget).val(text);
    })
})
