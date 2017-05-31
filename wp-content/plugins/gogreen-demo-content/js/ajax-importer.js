(function ($) {
    "use strict";
    function gogreenImporter() {

        this.import_url = "";
        this.data_dir = "";
        this.demoId = "";
        this.tasks = [];
        this.steps = [];

        if (typeof gogreen_ajax_importer_settings == "object") $.extend(this, gogreen_ajax_importer_settings);

        var self = this;

        var $panel = null;

        this.initImporter = function () {

            // Demo Content
            $(".demo-item").on("click", function () {

                if (typeof gogreen_ajax_importer_settings == "object") {

                    if ( confirm(self.messages.confirm_import_demo_content) ) {

                        var $el = $(this);
                        
                        self.demoId = $el.attr("id").replace(/\D/g, '');    
                        self.tasks = [];
                        self.steps = [];  

                        switch(self.demoId){
                            case '1':
                            case '2':
                            self.demoType = 'multi-pages';
                            break;                       
                            case '3':
                            self.demoType = 'one-page';
                            break;
                        }


                        $panel = $el.parents(".import-wrapper");

                        self.hideOptions();

                        $panel.append("<p class=\"import-message\"><strong>"+self.messages.loading+"</strong></p>");

                        self.progress = $("<ul class=\"import-progress\"></ul>");
                        $panel.append(self.progress);  

                        $(".content-options > p input").each(function(i, v){                                                        
                            if( $(this).is(":checked") ){  
                                var task = $(this).val();            
                                if(task) self.addTask(task, $(this).parent().text());                                                           
                            }
                        });

                        if (self.tasks.length == 1 && self.tasks[self.tasks.length - 1] == "settings" ) {
                            self.importSettings(self.tasks.length);
                        }

                        $panel.append("<p class=\"panel-cancel\"><a href=\"" + window.location.href + "&action=cancel\" class=\"button-cancel\"><i class=\"el el-remove-sign\"></i>Cancel</a></p>");

                    }

                } else {
                    alert(self.messages.can_not_import_demo_content);
                }

                return false;

            });
           
            
        };

        this.hideOptions = function(){
            $(".import-wrapper").find("h4, .content-options, .demo-content-list, .import-buttons").slideUp(); 
        };

        this.showOptions = function(){
            $(".import-wrapper").find("h4, .content-options, .demo-content-list, .import-buttons").slideDown();
            $(".import-message").remove();
        };
       
        this.addTask = function (task, text) {            
            self.tasks.push(task);
            var id = self.tasks.length;
            self.progress.append("<li class=\"task-"+id+"\"><span class=\"status\"></span> " + text + "</li>"); 
            if(task != "settings"){             
                self.importContent(id, task);      
            }      
        };

        this.importContent = function (id, task) {
            setTimeout(function () {
                self.progress.find("li.task-"+id).find(".status").html("<span class=\"w-loader\"></span>");
                self.beginImport(task, function () {
                    self.success(id);
                }, function () {
                    self.fail(id);
                });
            }, 1000 * id);

        };

        this.beginImport = function (task, success, fail) {

            var processes = task.split(',');            

            var requests = [];
            var responses = [];
            
            $.each(processes, function(i, v){    
                
                if(!v) return;  

                var async = (v.match(/(\-[0-9]+)$/) == null);

                requests[i] = function(){

                    var data = { action: "gogreen_import", demo: self.demoId, demo_type: self.demoType, type: v };          

                    $.ajax({
                        url: self.import_url,
                        data: data
                    }).done(function (response) {
                        
                        var responseObj = jQuery.parseJSON(response);                   

                        responses.push(responseObj.code);

                        if(responses.length == requests.length){                            

                            if (responseObj.code == "1") {
                                if (typeof success == "function") {
                                    success();
                                }
                            } else {
                                if (typeof fail == "function") {
                                    fail();
                                }
                            }

                            self.steps.push( v+i+":"+responseObj.code );

                            if (self.steps.length == self.tasks.length - 1 && self.tasks[self.tasks.length - 1] == "settings" ) {
                                self.importSettings(self.steps.length);
                            }else if( self.steps.length == self.tasks.length ){
                                $(".panel-cancel").remove();
                                self.showOptions();
                            }                        

                        }else{
                            if( !async ){
                                requests[responses.length]();
                            }
                        }                  

                    }).fail(function (response) {

                        responses.push(0);

                        if(responses.length == requests.length){   

                            self.steps.push( v+i+":0" );

                            if (typeof fail == "function") {
                                fail();
                            }

                            console.log( "Import fail:" + response );

                        }else{
                            if( !async ){
                                requests[responses.length]();
                            }
                        }
                        
                    });                   


                };

                if( async ){
                    requests[i]();
                }else if(i == 0){
                    requests[0]();
                }
            
            });            

        };

        this.importSettings = function (id) {


            self.progress.find("li.task-"+id).find(".status").html("<span class=\"w-loader\"></span>");

            self.beginImport("settings", function () {

                setTimeout(function () {
                    
                    if (self.data_dir) {
                        var optionsUrl = self.data_dir + "/" + self.demoId + "/theme_options.txt";
                        $("#import-link-value").val(optionsUrl);
                        var formOptions = $("#redux-form-wrapper");
                        var hiddenAction = $("<input type=\"hidden\" id=\"import-hidden\" />");
                        hiddenAction.attr("name", $("#redux-import").attr("name")).val("true");
                        formOptions.append(hiddenAction);
                        formOptions.submit();
                    }

                }, 1000); 

                self.success(id);  
                             
            }, function () {
                self.fail(id);
            });

        };

        this.success = function (name) {
            self.progress.find("li.task-"+name).find(".status").html("<i class=\"el el-ok-sign\"></i>");
        }

        this.fail = function (name) {
            self.progress.find("li.task-"+name).find(".status").html("<i class=\"el el-remove-sign\"></i>");
        };

        $(document).ready(function () {

            self.initImporter();

        });

    }

    new gogreenImporter();


})(jQuery);