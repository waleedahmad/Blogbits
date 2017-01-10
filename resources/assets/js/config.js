class Config{

    initHashNavigate(){
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
    getSchedulerStartTime(target){
        var value = $.trim($(target).val());
        return (value) ? parseInt(value.match(/\d+/)[0]) : 0;
    }

    /**
     * Get scheduler end time
     * @returns {*}
     */
    getSchedulerEndTime(target){
        var value = $.trim($(target).val());
        return (value) ? parseInt(value.match(/\d+/)[0]) : 0;
    }

    /**
     * Init scheduler timing settings
     * @param _this
     */
    initSchedulerTimings(_this){
        $('#scheduler_start_time, #scheduler_end_time, #social_scheduler_start_time, #social_scheduler_end_time').timepicker({
            'timeFormat': 'H:i',
            'step': function(i) {
                return 60;
            }
        }).keypress(function(e){
            e.preventDefault();
        });

        // update scheduler timings
        $("#up-sch-timings, #up-sch-timings-social").on('click', function(e){
            e.preventDefault();

            var type = $(this).attr('data-type');

            var start_target;
            var end_target;

            if(type === 'blog'){
                start_target = '#scheduler_start_time';
                end_target = '#scheduler_end_time'
            }else if(type === 'social'){
                start_target = '#social_scheduler_start_time';
                end_target = '#social_scheduler_end_time'
            }

            var start = _this.getSchedulerStartTime(start_target),
                end = _this.getSchedulerEndTime(end_target);

            $.ajax({
                type : 'POST',
                url : '/config/scheduler/timings',
                data : {
                    _token : _this.token,
                    start : start,
                    end : end,
                    type : type
                },
                success : function(res){
                    if(res){
                        toastr.success('Scheduler timings updated');
                    }else{
                        toastr.error('Unable to update Scheduler timings');
                    }
                }
            });
        });
    }

    initFbAlbumSync(_this){
        $("#sync-fb-albums").on('click', function(e){

            toastr.info('Syncing facebook albums');
            e.preventDefault();

            $.ajax({
                type : 'POST',
                url : '/social/sync/fbAlbums',
                data : {
                    _token : _this.token
                },
                success : function(res){
                    if(res){
                        toastr.success('Facebook albums synced');
                    }
                }
            })
        });
    }

    initPostBackup(_this) {
        $("#backup-content").on('click', function(e){
            e.preventDefault();

            toastr.info('Backing up all posts!');

            $.ajax({
                type : 'POST',
                'url' : '/content/backup',
                data : {
                    _token : _this.token
                },
                success : function(res){
                    if(res){
                        toastr.success('All Photos backed up!');
                    }
                }
            });
        });
    }

    constructor(){
        var _this = this;
        this.token = $("meta[name=csrf_token]").attr('content');
        this.initHashNavigate();
        this.initSchedulerTimings(this);
        this.initFbAlbumSync(this);
        this.initPostBackup(this);
    }
}

new Config();