define([],function(){
	'use strict';
	return function(Component){
		return Component.extend({
			isItemsBlockExpanded: function(){
				console.log("Avinash");
				return	true;
			}
		})
	}
})