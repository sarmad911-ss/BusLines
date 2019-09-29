/*--------------------------SCROLLING-PLUGIN-ARGE-ACSIMA-----------------------------------*/
(function($){

    $.fn.customSelect = function(options){

        options = $.extend({
            columns:1,
            maxActiveItems:false,
            debugMode:false,
            clearButton:false
        },options);


        function init(object) {

            var _this = $(object);
            var selectItems = _this.find('option');
            var disabledStatus = _this.prop('disabled') ? 'disabled' : '';

           /* TODO Поправить механику required
           *  Селект имеет required и работает в случае если он не disabled,
           *  нужно отловить событие required и добавить нужный класс,
           *  сделать модификатор под класс или же дать возможность добавлять его юзеру
           *  DONE
           */

            var maxActiveItems = (options.maxActiveItems !== false) ? 'select-max-items="'+options.maxActiveItems+'"' : '';
            var customSelectContainer = $('<div class="customSelect-container"></div>');
            var customSelectParent = $('<div class="customSelect-object'+disabledStatus+'" '+maxActiveItems+'><i class="customSelect-icon arrow-icon"></i></div>');
            var customSelectValueList = $('<div class="customSelect-values__list"></div>');
            var customSelectSelectedItems = _this.find('option:selected');
            var customSelectSelectedLabel = [];
            var customSelectItemWidth = 100/ options.columns;

            customSelectSelectedItems.each(function (index, option) {
                customSelectSelectedLabel.push($(option).text());
            });

            customSelectSelectedLabel = customSelectSelectedLabel.join(", ");

            customSelectSelectedLabel = (customSelectSelectedLabel.length <= 0) ? 'None' : customSelectSelectedLabel;

            (typeof _this.attr('autocomplete') !== 'undefined')
                ? customSelectParent.append('<input class="customSelect__current-value" placeholder="Search..." value="'+customSelectSelectedLabel+'">')
                : customSelectParent.append('<span class="customSelect__current-value">'+customSelectSelectedLabel+'</span>');

            var autoCompleteClass =  (typeof _this.attr('autocomplete') !== 'undefined') ? 'customSelect-autocomplete__item available': 'customSelect-default__item ';

            selectItems.each(function (index, option) {

                var activeState = ($(option).prop("selected")) ? 'active' : '';

                customSelectValueList.append(
                    '<div class="customSelect-value '+autoCompleteClass+activeState+'" select-item="'+index+'" style="width:'+customSelectItemWidth+'%">'+
                    $(option).text()+
                    '</div>'
                );

            });

            customSelectParent.append(customSelectValueList);
            (options.clearButton) ?  customSelectParent.append('<i class="customSelect-icon clear-icon"></i>') : null ;
            customSelectContainer.append(customSelectParent);
            customSelectContainer.insertBefore(_this);
            _this.appendTo(customSelectContainer);

            (options.debugMode) ? _this.removeClass('debugMode') : _this.addClass('debugMode');

        }

        /* TODO Проблема с отображением скрола
        *  Моргает скрол из-за увеличения высоты элемента с транзишеном
        */

        function bindCurrentValueClick(object) {
            $(object).closest('.customSelect-container').find('.customSelect__current-value').bind('click', function () {

                var opened = $(this).closest(".customSelect-object").hasClass("opened");
                $('.customSelect-object').removeClass('opened');

                if(!$(this).closest('.customSelect-object').hasClass("disabled"))
                    if(opened)
                        $(this).closest('.customSelect-object').removeClass('opened');
                    else
                        $(this).closest(".customSelect-object").addClass("opened");

                if($(this).is('input')){
                    $(this).val('');
                }

            });
        }

        function bindSelectValuesListValueClick(object) {
            $(object).closest('.customSelect-container').find('.customSelect-value ').bind('click', function () {

                var selectParent = $(this).closest('.customSelect-container');
                var customSelectObj = selectParent.find('.customSelect-object');
                var initialSelect = selectParent.find('select');
                var clickedItem = $(initialSelect.children()[$(this).attr('select-item')]) ;
                var maxItemsCounter = $(customSelectObj).attr('select-max-items');


                if(customSelectObj.attr('select-multiple')){



                }else{

                    customSelectObj.removeClass('opened');
                    customSelectObj.find('.customSelect-value').removeClass('active');
                    $(initialSelect.find('option')).prop('selected',false);

                    if(typeof initialSelect.attr('autocomplete') == 'undefined'){
                        customSelectObj.find('.customSelect__current-value').text(
                            $(this).text()
                        );
                    }else{
                        customSelectObj.find('.customSelect__current-value').val(
                            $(this).text()
                        );
                    }

                    $(this).addClass('active');
                    clickedItem.prop('selected', !clickedItem.prop('selected'));
                }

                if(maxItemsCounter === 'infinite'){

                }



            });
        }

        function bindClearValueButton(object){
            $(object).closest('.customSelect-container').find('.customSelect-icon.clear-icon').bind('click', function () {

                var selectParent = $(this).closest('.customSelect-container');
                var customSelectObj = selectParent.find('.customSelect-object');
                var initialSelect = selectParent.find('select');

                customSelectObj.find('.customSelect-value').removeClass('active');

                (typeof initialSelect.attr('autocomplete') == 'undefined')
                    ? customSelectObj.find('.customSelect__current-value').text('Choose value')
                    : customSelectObj.find('.customSelect__current-value').val('Choose value');

                $(initialSelect.find('option')).prop('selected',false);

            });
        }

        function bindCurrentValueKeyUp(object) {

            $(object).closest('.customSelect-container').find('.customSelect__current-value').bind('keyup', function () {

                var selectParent = $(this).closest('.customSelect-container');
                var customSelectValueList = selectParent.find('.customSelect-object .customSelect-autocomplete__item');
                var autocompleteInputValue = $(this).val();

                $(customSelectValueList).each(function (index, item) {

                    var matchStartIndex = parseInt($(item).text().toLowerCase().indexOf(autocompleteInputValue.toLowerCase()));

                    if(matchStartIndex >= 0){

                        var matchedString = $(item).text().slice(
                          matchStartIndex , matchStartIndex + (parseInt(autocompleteInputValue.toLowerCase().length = 1)
                                                                ? parseInt(autocompleteInputValue.toLowerCase().length)
                                                                : 1)
                        );

                        var newString = $(item).text().slice(0, matchStartIndex)
                            + '<strong>' + matchedString + '</strong>' +
                            $(item).text().substr(
                                matchStartIndex + parseInt(autocompleteInputValue.toLowerCase().length),
                                $(item).text().length - parseInt(autocompleteInputValue.toLowerCase().length) - matchStartIndex
                            );

                        $(item).empty().append(newString);
                        $(item).addClass('available');

                    }else{

                        $(item).removeClass('available');

                        return;

                    }

                });

                /* TODO Обработчик на отсутствие совпадений
                * Добавить обработчик на отсутствие матчей в селектах
                */

                // (selectParent.find('.customSelect-autocomplete__item.available').length <= 0)
                //     ? (selectParent.find('.customSelect-noresults'))
                //         ? selectParent.find('.customSelect-values__list').append('<div class="customSelect-noresults">No results</div>')
                //         : false
                //     : selectParent.find('.customSelect-noresults').remove();


            });

        }

        this.each(function (index, object) {
            init(object);
            bindCurrentValueClick(object);
            bindSelectValuesListValueClick(object);
            bindClearValueButton(object);
            bindCurrentValueKeyUp(object);
        });



        //TODO Сделать массив с элементами автокомплита, что бы уменьшить лаги при поиске

        // $('.customSelect__current-value').unbind('keyup').bind('keyup', function () {
        //
        //     var selectItemsList = $('.customSelect').find('.customSelect-values__list .autoComplete-item');
        //     var inputValue = $(this).val();
        //
        //     $(selectItemsList).each(function (index, item) {
        //
        //         var matchStartIndex = parseInt($(item).find('span').text().toLowerCase().indexOf(inputValue.toLowerCase()));
        //
        //         if( matchStartIndex >= 0){
        //
        //             var matchedString = $(item).find('span').text().slice(
        //                 matchStartIndex , matchStartIndex + (parseInt(inputValue.toLowerCase().length = 1) ? parseInt(inputValue.toLowerCase().length) : 1));
        //
        //             var newString = $(item).find('span').text().slice(0, matchStartIndex)
        //                 +'<strong>'+matchedString+'</strong>'+
        //                 $(item).find('span').text().substr(
        //                     matchStartIndex+parseInt(inputValue.toLowerCase().length),
        //                     $(item).find('span').text().length-parseInt(inputValue.toLowerCase().length)-matchStartIndex
        //                 );
        //
        //             $(item).find('span').empty().append(newString);
        //
        //             $(item).addClass('available');
        //
        //         }else{
        //
        //             $(item).removeClass('available');
        //
        //             return;
        //
        //         }
        //
        //     });
        //
        // });

        $(document).click(function(event) {
            if (!$(event.target).closest(".customSelect__current-value, .customSelect-values__list .customSelect-value").length) {
                $("body").find(".customSelect-object").removeClass("opened");
            }
        });



        // $('.customSelect-values__list .customSelect-value').unbind('click').bind('click', function () {
        //     var selectParent =  $(this).closest('.customSelect');
        //     var currentValue = [];
        //     var maxActiveItems = selectParent.attr("maxItems");
        //
        //     if(typeof maxActiveItems === "undefined") maxActiveItems = false;
        //
        //     var targetInput = $(this).find('input');
        //
        //     if(selectParent.hasClass('multipleItems')){
        //
        //         if(selectParent.find('.customSelect-values__list .customSelect-value.active').length < maxActiveItems || $(this).closest('.customSelect-value').hasClass('active') || maxActiveItems === false){
        //
        //             selectParent.find('.customSelect-values__list .customSelect-value').each(function (index, option) {
        //                 $(option).removeClass('disabled');
        //             });
        //
        //             if($(this).hasClass('active')){
        //                 $(this).removeClass('active');
        //                 targetInput.prop( "checked", false );
        //             }else{
        //                 $(this).addClass('active');
        //                 targetInput.prop( "checked", true );
        //             }
        //
        //             selectParent.find('.customSelect-values__list .customSelect-value.active').each(function (index, option) {
        //                 currentValue.push($(option).find('span').text());
        //             });
        //
        //             currentValue = currentValue.join(', ');
        //
        //             currentValue = (currentValue.length <= 0) ? 'None' : currentValue;
        //
        //             if(!options.autoComplete){
        //                 selectParent.find('.customSelect__current-value').text(
        //                     currentValue
        //                 );
        //             }else{
        //                 selectParent.find('.customSelect__current-value').val(
        //                     currentValue
        //                 );
        //             }
        //
        //             if(selectParent.find('.customSelect-values__list .customSelect-value.active').length === maxActiveItems && maxActiveItems !== false){
        //                 selectParent.find('.customSelect-values__list .customSelect-value').each(function (index, option) {
        //                     if(!$(option).hasClass('active')){
        //                         $(option).addClass('disabled');
        //                     }
        //                 });
        //             }
        //
        //         }
        //
        //     }else{
        //
        //         selectParent.removeClass('opened');
        //
        //         selectParent.find('.customSelect-values__list .customSelect-value').removeClass('active');
        //         selectParent.find('.customSelect-values__list .customSelect-value input').prop('checked',false);
        //
        //         $(this).addClass('active');
        //         targetInput.prop( "checked", true );
        //
        //         if(!options.autoComplete){
        //             selectParent.find('.customSelect__current-value').text(
        //                 $(this).closest('.customSelect-value').find('span').text()
        //             );
        //         }else{
        //             selectParent.find('.customSelect__current-value').val(
        //                 $(this).closest('.customSelect-value').find('span').text()
        //             );
        //         }
        //     }
        //
        // });
    };
    return this;

})(jQuery);