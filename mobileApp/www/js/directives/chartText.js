app.directive('chartText', function($timeout) {
  	return {
	    restrict: 'A',
	    replace: true,
	    link: function(scope, elem, attrs) {
			/*scope.$on('animation_completed', function(){
		     	var ctx = elem[0].getContext("2d");
		     	var canvasWidth = elem[0].width;
		     	
     			var canvasHeight = elem[0].height;

     			var receiptsNumber = attrs.receiptsNumber;
     			var receiptsValue = attrs.receiptsValue;

			
				var constant = 60;
			    var fontsize = (canvasHeight/constant).toFixed(2);
			    ctx.font=fontsize +"em Verdana";
			    ctx.textBaseline="middle"; 

				var textWidth = ctx.measureText(receiptsNumber).width;


   				var txtPosx = Math.round((canvasWidth - textWidth)/4);
   		
				ctx.fillText(receiptsNumber, txtPosx, canvasHeight/2.2);

				var textOffset = (14 * fontsize) / 1.3;
				ctx.font= fontsize/3 + "em Verdana";
				textWidth = ctx.measureText(receiptsValue).width;
				txtPosx = Math.round((canvasWidth - textWidth)/2);
				ctx.fillText(receiptsValue, txtPosx, (canvasHeight/2.2)+textOffset);
		    });*/
		}
  	};
});
