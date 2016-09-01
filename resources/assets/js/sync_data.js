class ContentSync{

    /**
     * Sync all content
     * @param e
     */
    requestSync(e){
        e.preventDefault();
        var _this = e.data.context;

        toastr.info('Sync in progress, please wait...')
        $(this).unbind('click');
        $.ajax({
            type : 'GET',
            url : '/content/sync',
            data : {
                _token : _this.token
            },
            success: function(res){
                if(res){
                    toastr.success('success','Content successfully synced...')
                    setTimeout(function(){
                        window.location.reload();
                    },3000);
                }
            }
        });
    }

    /**
     * Sync Social Content
     * @param e
     */
    requestSocialSync(e){
        e.preventDefault();
        var _this = e.data.context;

        toastr.info('Social sync in progress, please wait...')
        $(this).unbind('click');
        $.ajax({
            type : 'GET',
            url : '/content/sync/social',
            data : {
                _token : _this.token
            },
            success: function(res){
                if(res){
                    toastr.success('success','Social Content successfully synced...')
                    setTimeout(function(){
                        window.location.reload();
                    },3000);
                }
            }
        });
    }

    /**
     * Delete all Synced content
     * @param _this
     */
    initDeleteSync(_this){
        $("#delete-sync").on('click', function(e){
            e.preventDefault();
            var ask = confirm('Are you sure you want to delete all synced content?');

            if(ask){
                toastr.info('Deleting all synced content...');
                $(this).unbind('click');
                $.ajax({
                    type : 'DELETE',
                    url : '/content/deleteAll/blog',
                    data : {
                        _token : _this.token
                    },
                    success : function(res){
                        if(res){
                            toastr.success('Content successfully removed...');
                            $(".grid").fadeOut(function(){
                                $(this).remove();
                            });
                        }
                    }
                });
            }else{
                toastr.info('Deleting content cancelled..');
            }
        });
    }

    initSocialDeleteSync(_this){
        $("#social-delete-sync").on('click', function(e){
            e.preventDefault();
            var ask = confirm('Are you sure you want to delete all synced content?');

            if(ask){
                toastr.info('Deleting all social synced content...');
                $(this).unbind('click');
                $.ajax({
                    type : 'DELETE',
                    url : '/content/deleteAll/social',
                    data : {
                        _token : _this.token
                    },
                    success : function(res){
                        if(res){
                            toastr.success('Content successfully removed...');
                            $(".grid").fadeOut(function(){
                                $(this).remove();
                            });
                        }
                    }
                });
            }else{
                toastr.info('Deleting social content cancelled..');
            }
        });
    }

    constructor(){
        this.token = $("meta[name=csrf_token]").attr('content');
        this.flash_message = $("#flash-message");
        $("#sync-data").on('click', {context : this}, this.requestSync);
        $("#social-sync").on('click', {context : this}, this.requestSocialSync);
        this.initDeleteSync(this);
        this.initSocialDeleteSync(this);
    }
}

new ContentSync();