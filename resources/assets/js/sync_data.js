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
     * Delete all Synced content
     * @param _this
     */
    initDeleteSync(_this){
        $("#delete-sync").on('click', function(e){
            e.preventDefault();
            toastr.info('Deleting all synced content...');
            $(this).unbind('click');
            $.ajax({
                type : 'DELETE',
                url : '/content/deleteAll',
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
        });
    }

    constructor(){
        this.token = $("meta[name=csrf_token]").attr('content');
        this.flash_message = $("#flash-message");
        $("#sync-data").on('click', {context : this}, this.requestSync);
        this.initDeleteSync(this);
    }
}

new ContentSync();