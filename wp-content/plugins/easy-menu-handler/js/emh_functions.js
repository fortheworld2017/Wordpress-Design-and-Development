(function($){
	
    var EMH_JS = {
        init: function() {
            this.wp_menu_item = "#menu-to-edit .menu-item";
            this.wp_delete_btn = ".item-delete";
			
            this.emh_delete_btn = "emh-menu-delete";
			this.pluginURL = currentjspath.pluginURL;
			
            this.bin_single = this.pluginURL + "/images/bin.png";
            this.bin_single_desc = "Delete current menu item.";

            this.bin_all = this.pluginURL + "/images/redbin.png";
            this.bin_all_desc = "Delete current menu tree.";
			
            this.customRemove();
        },

        customRemove:function(){
            var self = this;
			var templvl = 0;
			var len = $(self.wp_menu_item).length;
			var hassub = false;
			
			var elements = document.getElementsByClassName("emh-menu-delete");
			while(elements.length > 0){
				elements[0].parentNode.removeChild(elements[0]);
			}				
				
            $(self.wp_menu_item).each(function(index, element){
				
                var curret_menu_item = $(this);
                var item_controls = curret_menu_item.find('.item-controls').find('.item-type');
				hassub = false;

				if (!(index == len - 1)) {
					
					var currentlevel = self.getLevel(curret_menu_item);
					var nextlevel = self.getLevel(curret_menu_item.next('li'));
					
					if(currentlevel>=nextlevel)
						hassub = false;
					else
						hassub = true;
					
				}
			
                $( "<img/>", {
                    "class": self.emh_delete_btn,
                    title: self.bin_single_desc,
					src: self.bin_single,
					id: "emh-menu-delete",
                    click:function(){
                        curret_menu_item.find('.menu-item-settings').find(self.wp_delete_btn).trigger('click');
                        return false;
                    }
                }).insertBefore(item_controls);
				
				if(hassub){
				
					$( "<img/>", {
						"class": self.emh_delete_btn,
						title: self.bin_all_desc,
						src: self.bin_all,
						id: "emh-menu-delete",
						click:function(){
							var menu_level = self.getLevel(curret_menu_item);
							curret_menu_item.nextAll().each(function() {
									if (self.getLevel($(this)) <= menu_level) { return false; }
									$(this).find('.menu-item-settings').find(self.wp_delete_btn).trigger('click');
								});
							curret_menu_item.find('.menu-item-settings').find(self.wp_delete_btn).trigger('click');
							return false;
						}
					}).insertBefore(item_controls);
				
				}
            });
        },

        getLevel:function(wp_menu_item){
            var tclass = wp_menu_item.attr("class");
            var level_class = tclass.match(/menu-item-depth-[0-9]+/);
            var level = level_class[0];
            level = parseInt(level.replace("menu-item-depth-",""));
            return level;
        }
		
	}
	
    EMH_JS.init();

    var ulContent;
    $(document).ajaxStop(function () {
        var $ul = $('.menu');
        if(ulContent !== $ul.html()){
            ulContent = $ul.html();
            $ul.trigger('contentChanged');
        }
    });
	
	$('.menu').on('contentChanged',function(){EMH_JS.init();});
	
	$( "<a/>", {
		"style": "color:grey;cursor:pointer;font-weight:bold;",
		title: "Refresh Data",
		text: "Click here to refresh menu structure. (if needed)",
		click:function(){
			EMH_JS.init();
		}
		
	}).insertBefore($("#menu-to-edit"));
	
})(jQuery);