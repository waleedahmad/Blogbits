import React from 'react';


class SchedulerConfig extends React.Component{
    constructor(props) {
        super(props);
        this.state = this.props.config;
    }

    componentWillReceiveProps(nextProps){
        this.setState(nextProps.config);
    }


    handleBlogConfig(e){
        e.preventDefault();
        $.ajax({
            type : 'POST',
            url : '/config/scheduler',
            data : Object.assign(
                this.state,
                {_token : $("meta[name=csrf_token]").attr('content')}
            ),
            success : function(res){
                if(res.updated){
                    toastr.success('Updated');
                }

            }
        });
    }

    handleInputChange(e){
        this.setState({[e.target.name]: e.target.value});
    }

    capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    render(){

        return (
            <div className="block">
                <h3>
                    Scheduler
                </h3>
                <form onSubmit={this.handleBlogConfig.bind(this)}>

                    <div className="form-group">
                        <label>Scheduler Frequency</label>
                        <select className="form-control"
                                name="scheduler_frequency"
                                onChange={this.handleInputChange.bind(this)}
                                value={this.state.scheduler_frequency}>

                            {this.props.frequencies.map(function(frequency,index){
                                return (
                                    <option key={index}
                                            value={frequency}>
                                        {this.capitalizeFirstLetter(frequency.replace(/([a-z](?=[A-Z]))/g, '$1 '))}
                                    </option>
                                )
                            }.bind(this))}
                        </select>
                    </div>

                    <div className="form-group">
                        <label>Batch Posts Limit</label>
                        <input type="text"
                               className="form-control"
                               name="batch_post_limit"
                               placeholder="Batch Post limit (default 5)"
                               onChange={this.handleInputChange.bind(this)}
                               value={this.state.batch_post_limit}/>
                    </div>


                    <div className="form-group">
                        <label>Scheduler Starting Hour</label>
                        <input type="text"
                               name="scheduler_start_time"
                               className="form-control"
                               onChange={this.handleInputChange.bind(this)}
                               value={this.state.scheduler_start_time}
                        />
                    </div>

                    <div className="form-group">
                        <label>Scheduler Ending Hour</label>
                        <input type="text"
                               name="scheduler_end_time"
                               className="form-control"
                               onChange={this.handleInputChange.bind(this)}
                               value={this.state.scheduler_end_time}
                        />
                    </div>

                    <div className="form-group">
                        <button type="submit" className="btn btn-default">Update Scheduler Settings</button>
                    </div>
                </form>
            </div>
        );
    }
}


export default SchedulerConfig;