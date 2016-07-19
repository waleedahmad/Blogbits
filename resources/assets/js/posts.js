class Posts{

    /**
     * Initialize Infinite scrolling
     */
    initInfiniteScroll(_this){
        var $grid = $(".grid");
        $($grid).jscroll({
            debug: false,
            autoTrigger: true,
            nextSelector: '.pager li:last a',
            contentSelector: '.row, .pager',
            callback: function() {
                $('ul.pager:visible:first').remove();
                $('.jscroll-added > *').unwrap();
                _this.initPostTags(_this);
                _this.initPost(_this);
            }
        });
    }

    /**
     * Initialize Boostrap TagsInput
     */
    initPostTags(_this){
        $('.tags').tagsinput({
            allowDuplicates: false,
            trimValue: true
        });

        $('.tags').off('itemAdded').on('itemAdded', function(event) {

            var tags = $(this).tagsinput('items').join(),
                post_id = $(this).attr('data-id');

            _this.updatePostTags(post_id, tags, _this);
        });

        $('.tags').off('itemRemoved').on('itemRemoved', function(event) {
            var tags = $(this).tagsinput('items').join(),
                post_id = $(this).attr('data-id');

            _this.updatePostTags(post_id, tags, _this);
        });
    }

    /**
     * Update post tags
     * @param post_id
     * @param tags
     * @param _this
     */
    updatePostTags(post_id, tags, _this){
        $.ajax({
            type : 'POST',
            url : '/content/update/tags',
            data : {
                post_id : post_id,
                tags : tags,
                _token : _this.token,
            }
        });
    }

    /**
     * Initalize Post (Post-Now and Delete)
     */
    initPost(){
        $('.delete-now').off('click').on('click', {context : this}, this.deletePost);
        $('.post-now').off('click').on('click', {context : this}, this.createPost);
        $("#post-batch").off('click').on('click', {context : this}, this.createPostBatch);
    }

    /**
     * Post a batch of posts
     * @param e
     */
    createPostBatch(e){
        e.preventDefault();
        var _this = e.data.context;
        toastr.info('Posting post batch...');
        $(this).unbind('click');
        $.ajax({
            type : 'POST',
            url : '/content/post/batch',
            data : {
                _token : _this.token
            },
            success : function(res){
                if(res){
                    $(this).unbind('click');
                    toastr.success('Post batch posted...');
                    $(".row:lt(5)").slideUp();
                }
            }
        });
    }

    /**
     * Show action status message
     * @param message
     * @param rm_class
     * @param add_class
     * @param icon
     */
    showMessage(message, rm_class, add_class, icon){
        var _this = this;
        this.flash_message.removeClass(rm_class);
        this.flash_message.html('<i class="fa '+icon+'" aria-hidden="true"> </i> ' + message).addClass(add_class).slideDown('fast');
    }

    /**
     * Post Image
     * @param e
     */
    createPost(e){
        var _this = e.data.context;
        var post_now = $(this),
            post_id = $(this).attr('data-id');

        $(this).unbind('click');

        toastr.info('Posting new post...');

        $.ajax({
            type : 'POST',
            url : '/content/post',
            data : {
                _token : _this.token,
                post_id : post_id
            },
            success: function(res){
                if(res){
                    toastr.success('Posted...');
                    $(post_now).parents('.row').slideUp();
                }else{
                    $('.post-now').on('click', {context : this}, this.createPost);
                }
            }
        });
    }

    /**
     * Delete post
     * @param e
     */
    deletePost(e){
        var _this = e.data.context;

        var post_now = $(this),
            post_id = $(this).attr('data-id');

        $(this).unbind('click');

        $.ajax({
            type : 'DELETE',
            url : '/content/delete',
            data : {
                _token : _this.token,
                post_id : post_id
            },
            success: function(res){
                if(res){
                    $(post_now).parents('.row').slideUp(function(){
                        $(this).remove();
                    });
                }else{
                    $('.delete-now').on('click', {context : this}, this.deletePost);
                }
            }
        });
    }

    constructor(){
        this.token = $("meta[name=csrf_token]").attr('content');
        this.flash_message = $("#flash-message");
        this.initInfiniteScroll(this);
        this.initPostTags(this);
        this.initPost();
    }
}

new Posts();