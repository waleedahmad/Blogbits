'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Config = function () {
    _createClass(Config, [{
        key: 'initHashNavigate',
        value: function initHashNavigate() {
            var url = document.location.toString();
            if (url.match('#')) {
                $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
            }

            $('.nav-tabs a').on('shown.bs.tab', function (e) {
                window.location.hash = e.target.hash;
            });
        }

        /**
         * Get scheduler start time
         * @returns {*}
         */

    }, {
        key: 'getSchedulerStartTime',
        value: function getSchedulerStartTime(target) {
            var value = $.trim($(target).val());
            return value ? parseInt(value.match(/\d+/)[0]) : 0;
        }

        /**
         * Get scheduler end time
         * @returns {*}
         */

    }, {
        key: 'getSchedulerEndTime',
        value: function getSchedulerEndTime(target) {
            var value = $.trim($(target).val());
            return value ? parseInt(value.match(/\d+/)[0]) : 0;
        }

        /**
         * Init scheduler timing settings
         * @param _this
         */

    }, {
        key: 'initSchedulerTimings',
        value: function initSchedulerTimings(_this) {
            $('#scheduler_start_time, #scheduler_end_time, #social_scheduler_start_time, #social_scheduler_end_time').timepicker({
                'timeFormat': 'H:i',
                'step': function step(i) {
                    return 60;
                }
            }).keypress(function (e) {
                e.preventDefault();
            });

            // update scheduler timings
            $("#up-sch-timings, #up-sch-timings-social").on('click', function (e) {
                e.preventDefault();

                var type = $(this).attr('data-type');

                var start_target;
                var end_target;

                if (type === 'blog') {
                    start_target = '#scheduler_start_time';
                    end_target = '#scheduler_end_time';
                } else if (type === 'social') {
                    start_target = '#social_scheduler_start_time';
                    end_target = '#social_scheduler_end_time';
                }

                var start = _this.getSchedulerStartTime(start_target),
                    end = _this.getSchedulerEndTime(end_target);

                $.ajax({
                    type: 'POST',
                    url: '/config/scheduler/timings',
                    data: {
                        _token: _this.token,
                        start: start,
                        end: end,
                        type: type
                    },
                    success: function success(res) {
                        if (res) {
                            toastr.success('Scheduler timings updated');
                        } else {
                            toastr.error('Unable to update Scheduler timings');
                        }
                    }
                });
            });
        }
    }, {
        key: 'initFbAlbumSync',
        value: function initFbAlbumSync(_this) {
            $("#sync-fb-albums").on('click', function (e) {

                toastr.info('Syncing facebook albums');
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '/social/sync/fbAlbums',
                    data: {
                        _token: _this.token
                    },
                    success: function success(res) {
                        if (res) {
                            toastr.success('Facebook albums synced');
                        }
                    }
                });
            });
        }
    }, {
        key: 'initPostBackup',
        value: function initPostBackup(_this) {
            $("#backup-content").on('click', function (e) {
                e.preventDefault();

                toastr.info('Backing up all posts!');

                $.ajax({
                    type: 'POST',
                    'url': '/content/backup',
                    data: {
                        _token: _this.token
                    },
                    success: function success(res) {
                        if (res) {
                            toastr.success('All Photos backed up!');
                        }
                    }
                });
            });
        }
    }]);

    function Config() {
        _classCallCheck(this, Config);

        var _this = this;
        this.token = $("meta[name=csrf_token]").attr('content');
        this.initHashNavigate();
        this.initSchedulerTimings(this);
        this.initFbAlbumSync(this);
        this.initPostBackup(this);
    }

    return Config;
}();

new Config();

var Posts = function () {
    _createClass(Posts, [{
        key: 'initInfiniteScroll',


        /**
         * Initialize Infinite scrolling
         */
        value: function initInfiniteScroll(_this) {
            var $grid = $(".grid");
            $($grid).jscroll({
                debug: false,
                autoTrigger: true,
                nextSelector: '.pagination li:last a',
                contentSelector: '.post, .pagination',
                callback: function callback() {
                    $('ul.pagination:visible:first').remove();
                    $('.jscroll-added > *').unwrap();
                    _this.initPostTags(_this);
                    _this.initPost(_this);
                }
            });
        }

        /**
         * Initialize Boostrap TagsInput
         */

    }, {
        key: 'initPostTags',
        value: function initPostTags(_this) {
            $('.tags').tagsinput({
                allowDuplicates: false,
                trimValue: true
            });

            $('.tags').off('itemAdded').on('itemAdded', function (event) {

                var tags = $(this).tagsinput('items').join(),
                    post_id = $(this).attr('data-id');

                _this.updatePostTags(post_id, tags, _this);
            });

            $('.tags').off('itemRemoved').on('itemRemoved', function (event) {
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

    }, {
        key: 'updatePostTags',
        value: function updatePostTags(post_id, tags, _this) {
            $.ajax({
                type: 'POST',
                url: '/content/update/tags',
                data: {
                    post_id: post_id,
                    tags: tags,
                    _token: _this.token
                }
            });
        }

        /**
         * Initalize Post (Post-Now and Delete)
         */

    }, {
        key: 'initPost',
        value: function initPost() {
            $('.delete-now').off('click').on('click', { context: this }, this.deletePost);
            $('.post-now').off('click').on('click', { context: this }, this.createPost);
            $("#post-batch").off('click').on('click', { context: this }, this.createPostBatch);
        }

        /**
         * Post a batch of posts
         * @param e
         */

    }, {
        key: 'createPostBatch',
        value: function createPostBatch(e) {
            e.preventDefault();
            var _this = e.data.context;
            toastr.info('Posting post batch...');
            $(this).unbind('click');
            $.ajax({
                type: 'POST',
                url: '/content/post/batch',
                data: {
                    _token: _this.token
                },
                success: function success(res) {
                    if (res) {
                        $(this).unbind('click');
                        toastr.success('Post batch posted...');
                        _this.removePostBatchFromDOM();
                    }
                }
            });
        }

        /**
         * Remove posted post batch from DOM
         */

    }, {
        key: 'removePostBatchFromDOM',
        value: function removePostBatchFromDOM() {
            $.ajax({
                type: 'GET',
                url: '/config/posts/batchLimit',
                success: function success(res) {
                    $('.row:lt(' + res.batch_limit + ')').slideUp();
                }
            });
        }

        /**
         * Post Image
         * @param e
         */

    }, {
        key: 'createPost',
        value: function createPost(e) {
            var _this = e.data.context;
            var post_now = $(this),
                post_id = $(this).attr('data-id'),
                type = $(this).attr('data-type');

            $(this).unbind('click');

            toastr.info('Posting new post...');

            $.ajax({
                type: 'POST',
                url: '/content/post/' + type,
                data: {
                    _token: _this.token,
                    post_id: post_id
                },
                success: function success(res) {
                    if (res) {
                        toastr.success('Posted...');
                        $(post_now).parents('.post').slideUp();
                    } else {
                        $('.post-now').on('click', { context: this }, this.createPost);
                    }
                }
            });
        }

        /**
         * Delete post
         * @param e
         */

    }, {
        key: 'deletePost',
        value: function deletePost(e) {
            var _this = e.data.context;

            var post_now = $(this),
                post_id = $(this).attr('data-id');

            $(this).unbind('click');

            $.ajax({
                type: 'DELETE',
                url: '/content/delete',
                data: {
                    _token: _this.token,
                    post_id: post_id
                },
                success: function success(res) {
                    if (res) {
                        $(post_now).parents('.post').slideUp(function () {
                            $(this).remove();
                        });
                    } else {
                        $('.delete-now').on('click', { context: this }, this.deletePost);
                    }
                }
            });
        }
    }]);

    function Posts() {
        _classCallCheck(this, Posts);

        this.token = $("meta[name=csrf_token]").attr('content');
        this.flash_message = $("#flash-message");
        this.initInfiniteScroll(this);
        this.initPostTags(this);
        this.initPost();
    }

    return Posts;
}();

new Posts();

var ContentSync = function () {
    _createClass(ContentSync, [{
        key: 'requestSync',


        /**
         * Sync all content
         * @param e
         */
        value: function requestSync(e) {
            e.preventDefault();
            var uri = e.data.uri,
                name = e.data.name;

            toastr.info('Sync in progress, please wait...');
            $(this).unbind('click');
            $.ajax({
                type: 'GET',
                url: uri,
                data: {
                    _token: this.token
                },
                success: function success(res) {
                    if (res) {
                        toastr.success('success', name + ' Content successfully synced...');
                        setTimeout(function () {
                            window.location.reload();
                        }, 3000);
                    }
                }
            });
        }
        /**
         * Delete all Synced content
         * @param _this
         */

    }, {
        key: 'initDeleteSync',
        value: function initDeleteSync(_this) {
            $("#delete-sync, #facebook-delete-sync, #pinterest-delete-sync").on('click', function (e) {
                e.preventDefault();
                var service = $(this).attr('data-service'),
                    server_name = $(this).attr('data-name'),
                    ask = confirm('Are you sure you want to delete all ' + server_name + ' synced content?');

                if (ask) {
                    toastr.info('Deleting all synced content...');
                    $(this).unbind('click');
                    $.ajax({
                        type: 'DELETE',
                        url: '/content/deleteAll/' + service,
                        data: {
                            _token: _this.token
                        },
                        success: function success(res) {
                            if (res) {
                                toastr.success('Content successfully removed...');
                                $(".grid").fadeOut(function () {
                                    $(this).remove();
                                });
                            }
                        }
                    });
                } else {
                    toastr.info('Deleting content cancelled..');
                }
            });
        }
    }, {
        key: 'initSocialDeleteSync',
        value: function initSocialDeleteSync(_this) {
            $("#social-delete-sync").on('click', function (e) {
                e.preventDefault();
                var ask = confirm('Are you sure you want to delete all synced content?');

                if (ask) {
                    toastr.info('Deleting all social synced content...');
                    $(this).unbind('click');
                    $.ajax({
                        type: 'DELETE',
                        url: '/content/deleteAll/social',
                        data: {
                            _token: _this.token
                        },
                        success: function success(res) {
                            if (res) {
                                toastr.success('Content successfully removed...');
                                $(".grid").fadeOut(function () {
                                    $(this).remove();
                                });
                            }
                        }
                    });
                } else {
                    toastr.info('Deleting social content cancelled..');
                }
            });
        }
    }]);

    function ContentSync() {
        _classCallCheck(this, ContentSync);

        this.token = $("meta[name=csrf_token]").attr('content');
        this.flash_message = $("#flash-message");
        $("#sync-data").on('click', { uri: '/content/sync', name: 'Tumblr' }, this.requestSync);
        $("#facebook-sync").on('click', { uri: '/content/sync/facebook', name: 'Facebook' }, this.requestSync);
        $("#pinterest-sync").on('click', { uri: '/content/sync/pinterest', name: 'Pinterest' }, this.requestSync);
        this.initDeleteSync(this);
    }

    return ContentSync;
}();

new ContentSync();
toastr.options = {
    "debug": false,
    "positionClass": "toast-bottom-right",
    "onclick": null,
    "fadeIn": 300,
    "fadeOut": 1000,
    "timeOut": 5000,
    "extendedTimeOut": 1000,
    "newestOnTop": false
};
//# sourceMappingURL=app.js.map
