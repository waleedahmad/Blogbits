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
    getSchedulerStartTime(){
        var value = $.trim($("#scheduler_start_time").val());
        return (value) ? parseInt(value.match(/\d+/)[0]) : 0;
    }

    /**
     * Get scheduler end time
     * @returns {*}
     */
    getSchedulerEndTime(){
        var value = $.trim($("#scheduler_end_time").val());
        return (value) ? parseInt(value.match(/\d+/)[0]) : 0;
    }

    /**
     * Init scheduler timing settings
     * @param _this
     */
    initSchedulerTimings(_this){
        $('#scheduler_start_time, #scheduler_end_time').timepicker({
            'timeFormat': 'H:i',
            'step': function(i) {
                return 60;
            }
        }).keypress(function(e){
            e.preventDefault();
        });

        // update scheduler timings
        $("#up-sch-timings").on('click', function(e){
            e.preventDefault();

            var start = _this.getSchedulerStartTime(),
                end = _this.getSchedulerEndTime();

            $.ajax({
                type : 'POST',
                url : '/config/scheduler/timings',
                data : {
                    _token : _this.token,
                    start : start,
                    end : end
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

    constructor(){
        var _this = this;
        this.token = $("meta[name=csrf_token]").attr('content');
        this.initHashNavigate();
        this.initSchedulerTimings(this);
    }
}

new Config();