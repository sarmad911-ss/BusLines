/*--------------------------SCROLLING-PLUGIN-ARGE-ACSIMA-----------------------------------*/
(function($){

    $.fn.customSelect = function(options){

        options = $.extend({
            columns:1,
            maxActiveItems:false
        },options);

        function init(object) {

            var _this = $(object);
            var selectItems = _this.find('option');
            var disabledStatus = _this.prop('disabled') ? 'select-disabled' : '';
            var multipleStatus = _this.prop('multiple') ? 'select-multiple' : '';
            var requireStatus = _this.prop('require') ? 'select-require' : '';
            var autocompleteStatus = _this.prop('autocomplete') ? 'select-autocomplete' : '';
            var maxActiveItemsCounter = options.maxActiveItems !== false ? 'select-max-items="'+options.maxActiveItems+'"' : '';
            var customSelectParent = $('<div class="customSelect"'
                +disabledStatus+' '+
                +multipleStatus+' '+
                +requireStatus+' '+
                +autocompleteStatus+' '+
                +maxActiveItemsCounter+'></div>');
            var customSelectValueList = $('<div class="customSelect-values__list"></div>');
            var customSelectSelectedItems = _this.find('option:select');
            var customSelectSelectedLabel = [];
            var customSelectItemWidth = 100/ options.columns;

            customSelectSelectedItems.each(function (index, option) {
                customSelectSelectedLabel.push($(option).text());
            });

            customSelectSelectedLabel = customSelectSelectedLabel.join(", ");

            customSelectSelectedLabel = (customSelectSelectedLabel.length <= 0) ? 'None' : customSelectSelectedLabel;

            (!_this.prop('autocomplete'))
                ? customSelectParent.append('<span class="customSelect__current-value">'+customSelectSelectedLabel+'</span>')
                : customSelectParent.append('<input class="customSelect__current-value" placeholder="Search..." value="'+customSelectSelectedLabel+'">');


            selectItems.each(function (index, option) {

                var activeState = ($(option).prop("selected")) ? 'active' : '';

                customSelectValueList.append(
                    '<div class="customSelect-value '+activeState+'" select-item="'+index+'" style="width:'+customSelectItemWidth+'%">'+
                    $(option).text()+
                    '</div>'
                );

            });

            customSelectParent.append(customSelectValueList);

            customSelectParent.insertBefore(_this);

            _this.remove();

            /* TODO: Придумать что-то с селектами без имени
            * В случае отсутствия имени не генерируется for для лейбла,
            * по этому не происходит клик по опции из списка
            **/

            //TODO: Сделать возможность генерации опций селекта без value

        }

        this.each(function (index, object) {
            init(object);
        });

        $('.customSelect__current-value').unbind('click').bind('click', function () {

            var opened = $(this).closest(".customSelect").hasClass("opened");
            $('.customSelect').removeClass('opened');

            if(!$(this).closest('.customSelect').hasClass("disabled"))
                if(opened)
                    $(this).closest('.customSelect').removeClass('opened');
                else
                    $(this).closest(".customSelect").addClass("opened");


        });

        //TODO Сделать массив с элементами автокомплита, что бы уменьшить лаги при поиске

        $('.customSelect__current-value').unbind('keyup').bind('keyup', function () {

            var selectItemsList = $('.customSelect').find('.customSelect-values__list .autoComplete-item');
            var inputValue = $(this).val();

            $(selectItemsList).each(function (index, item) {

                var matchStartIndex = parseInt($(item).find('span').text().toLowerCase().indexOf(inputValue.toLowerCase()));

                if( matchStartIndex >= 0){

                    var matchedString = $(item).find('span').text().slice(
                        matchStartIndex , matchStartIndex + (parseInt(inputValue.toLowerCase().length = 1) ? parseInt(inputValue.toLowerCase().length) : 1));

                    var newString = $(item).find('span').text().slice(0, matchStartIndex)
                        +'<strong>'+matchedString+'</strong>'+
                        $(item).find('span').text().substr(
                            matchStartIndex+parseInt(inputValue.toLowerCase().length),
                            $(item).find('span').text().length-parseInt(inputValue.toLowerCase().length)-matchStartIndex
                        );

                    $(item).find('span').empty().append(newString);

                    $(item).addClass('available');

                }else{

                    $(item).removeClass('available');

                    return;

                }

            });

        });

        $(document).click(function(event) {
            if (!$(event.target).closest(".customSelect__current-value, .customSelect-values__list .customSelect-value").length) {
                $("body").find(".customSelect").removeClass("opened");
            }
        });

        $('.customSelect-values__list .customSelect-value').unbind('click').bind('click', function () {
            var selectParent =  $(this).closest('.customSelect');
            var currentValue = [];
            var maxActiveItems = selectParent.attr("maxItems");

            if(typeof maxActiveItems === "undefined") maxActiveItems = false;

            var targetInput = $(this).find('input');

            if(selectParent.hasClass('multipleItems')){

                if(selectParent.find('.customSelect-values__list .customSelect-value.active').length < maxActiveItems || $(this).closest('.customSelect-value').hasClass('active') || maxActiveItems === false){

                    selectParent.find('.customSelect-values__list .customSelect-value').each(function (index, option) {
                        $(option).removeClass('disabled');
                    });

                    if($(this).hasClass('active')){
                        $(this).removeClass('active');
                        targetInput.prop( "checked", false );
                    }else{
                        $(this).addClass('active');
                        targetInput.prop( "checked", true );
                    }

                    selectParent.find('.customSelect-values__list .customSelect-value.active').each(function (index, option) {
                        currentValue.push($(option).find('span').text());
                    });

                    currentValue = currentValue.join(', ');

                    currentValue = (currentValue.length <= 0) ? 'None' : currentValue;

                    if(!options.autoComplete){
                        selectParent.find('.customSelect__current-value').text(
                            currentValue
                        );
                    }else{
                        selectParent.find('.customSelect__current-value').val(
                            currentValue
                        );
                    }

                    if(selectParent.find('.customSelect-values__list .customSelect-value.active').length === maxActiveItems && maxActiveItems !== false){
                        selectParent.find('.customSelect-values__list .customSelect-value').each(function (index, option) {
                            if(!$(option).hasClass('active')){
                                $(option).addClass('disabled');
                            }
                        });
                    }

                }

            }else{

                selectParent.removeClass('opened');

                selectParent.find('.customSelect-values__list .customSelect-value').removeClass('active');
                selectParent.find('.customSelect-values__list .customSelect-value input').prop('checked',false);

                $(this).addClass('active');
                targetInput.prop( "checked", true );

                if(!options.autoComplete){
                    selectParent.find('.customSelect__current-value').text(
                        $(this).closest('.customSelect-value').find('span').text()
                    );
                }else{
                    selectParent.find('.customSelect__current-value').val(
                        $(this).closest('.customSelect-value').find('span').text()
                    );
                }
            }

        });
    };
    return this;

})(jQuery);