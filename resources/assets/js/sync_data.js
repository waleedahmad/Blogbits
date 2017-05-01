class ContentSync{

    /**
     * Sync all content
     * @param e
     */
    requestSync(e){
        e.preventDefault();
        var uri = e.data.uri,
            name = e.data.name;

        toastr.info('Sync in progress, please wait...')
        $(this).unbind('click');
        $.ajax({
            type : 'GET',
            url : uri,
            data : {
                _token : this.token
            },
            success: function(res){
                if(res){
                    toastr.success('success', name + ' Content successfully synced...')
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
        $("#delete-sync, #facebook-delete-sync, #pinterest-delete-sync").on('click', function(e){
            e.preventDefault();
            var service = $(this).attr('data-service'),
                server_name = $(this).attr('data-name'),
                ask = confirm(`Are you sure you want to delete all ${server_name} synced content?`);

            if(ask){
                toastr.info('Deleting all synced content...');
                $(this).unbind('click');
                $.ajax({
                    type : 'DELETE',
                    url : `/content/deleteAll/${service}`,
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
        $("#sync-data").on('click', {uri : '/content/sync', name : 'Tumblr'}, this.requestSync);
        $("#facebook-sync").on('click', {uri : '/content/sync/facebook', name : 'Facebook'}, this.requestSync);
        $("#pinterest-sync").on('click', {uri : '/content/sync/pinterest', name : 'Pinterest'} , this.requestSync);
        this.initDeleteSync(this);
    }
}

new ContentSync();