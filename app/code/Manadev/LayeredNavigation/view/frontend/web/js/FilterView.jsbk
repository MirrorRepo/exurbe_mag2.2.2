define(['jquery', 'Manadev_LayeredNavigation/js/vars/actionHelper',
    'Manadev_Core/js/functions/requestAnimationFrame'],
function($, actionHelper, requestAnimationFrame) {

    $(document).on('mana-layered-navigation-action', function(event, action) {
        actionHelper.forEachElement('.mana-filter-block .items li a', action, function(action) {
            var $a = $(this);
            var $li = $a.parent();
            //alert("ok");
            // if (!action.op) {
            //     if (action.clear) {
            //         $li.removeClass('mana-selected');
            //     }
            //     else {
            //         $li.addClass('mana-selected');
            //     }
            // }
            // else if (action.op == '+') {
            //     $li.addClass('mana-selected');
            //     requestAnimationFrame(function () {
            //         $a.data('action', '-' + action.param + '=' + action.value);
            //     });
            // }
            // else {
            //     $li.removeClass('mana-selected');
            //     requestAnimationFrame(function () {
            //         $a.data('action', '+' + action.param + '=' + action.value);
            //     });
            // }
        });
    });
    $(document).on("click",".remove_RG",function()
  {
    //alert("ok");
    var color= $(this).parent().find(".filter-value").text();
    $(".swatch-option.color.selected").each(function(index, el) {
      if($(this).attr("option-label")==color){
        $(this).removeClass('selected');
      }
    });
    $("li.mana-selected.item").each(function(index, el) {
      var split_val=$(this).find("label").text().trim();
      //alert("aa"+split_val+color+"cc");
      if(split_val==color){
      //  alert(split_val+color);
        $(this).removeClass('mana-selected');
      }
    });

    $(this).parent().remove();
  });


      $(document).on("click",".effect_RG",function()
    {
      if($(this).parent().hasClass('mana-selected')){
          $(this).parent().removeClass('mana-selected');
      }else {
        $(this).parent().addClass('mana-selected');

      }
      });

    return function(config, element) {
        $(element).on('click', 'li a', function() {
            $(document).trigger('mana-layered-navigation-action', [$(this).data('action')]);
        });
    };
});
