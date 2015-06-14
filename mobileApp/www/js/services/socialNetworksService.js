app.factory('SocialNetworks', function() {
    return {
    	isFacebookEnabled : function () {
    		if(window.localStorage['facebook']==1)
    			return true;
    		else
    			return false;
    	},
    	enableFacebook : function () {
    		window.localStorage['facebook'] = 1;
    	},
    	disableFacebook : function () {
    		window.localStorage['facebook'] = 0;
    	},
    	isTwitterEnabled : function () {
    		if(window.localStorage['twitter']==1)
    			return true;
    		else
    			return false;
    	},
    	enableTwitter : function () {
    		window.localStorage['twitter'] = 1;
    	},
    	disableTwitter : function () {
    		window.localStorage['twitter'] = 0;
    	}
    }
});