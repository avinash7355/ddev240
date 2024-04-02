define(['ko'],function(ko){
    'use strict';
    return function (){
        const viewModel={
            exchanges_rates: ko.observableArray([
                {
                    currency_to:'USD',
                    rate: 1.0
                }
            ])
        }
        return viewModel;
    }
    });