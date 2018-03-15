import React from 'react';


class SocialConfig extends React.Component{
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
            url : '/config/social',
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
                    Social
                </h3>
                <form onSubmit={this.handleBlogConfig.bind(this)}>

                    <div className="form-group">
                        <label>Social Scheduler Frequency</label>
                        <select className="form-control"
                                name="social_scheduler_frequency"
                                onChange={this.handleInputChange.bind(this)}
                                value={this.state.social_scheduler_frequency}>
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
                        <label>Social Scheduler Starting Hour</label>
                        <input value={this.state.social_scheduler_start_time}
                               onChange={this.handleInputChange.bind(this)}
                               name="social_scheduler_start_time"
                               className="form-control" />
                    </div>

                    <div className="form-group">
                        <label>Social Scheduler Ending Hour</label>
                        <input value={this.state.social_scheduler_end_time}
                               onChange={this.handleInputChange.bind(this)}
                               name="social_scheduler_end_time"
                               className="form-control" />
                    </div>


                    <div className="form-group">
                        <label>Facebook Page ID</label>
                        <input className="form-control"
                               placeholder="Facebook Page ID"
                               name="facebook_pageid"
                               onChange={this.handleInputChange.bind(this)}
                               value={this.state.facebook_pageid}/>
                    </div>


                    <div className="form-group">
                        <label>Pinterest username</label>
                        <input className="form-control"
                               placeholder="Pinterest Username"
                               name="pinterest_username"
                               onChange={this.handleInputChange.bind(this)}
                               value={this.state.pinterest_username}/>
                    </div>

                    <div className="form-group">
                        <label>Pinterest Board</label>
                        <input className="form-control"
                               placeholder="Pinterest Board"
                               name="pinterest_board"
                               onChange={this.handleInputChange.bind(this)}
                               value={this.state.pinterest_board}/>
                    </div>

                    <div className="form-group">
                        <label>Pinterest Token</label>
                        <input className="form-control"
                               placeholder="Pinterest Token"
                               name="pinterest_token"
                               onChange={this.handleInputChange.bind(this)}
                               value={this.state.pinterest_token}/>
                    </div>

                    <div className="form-group">
                        <label>Pinterest Sync Folder</label>
                        <input className="form-control"
                               placeholder="Pinterest Sync Folder"
                               name="pinterest_sync_folder"
                               onChange={this.handleInputChange.bind(this)}
                               value={this.state.pinterest_sync_folder}/>
                    </div>

                    <div className="form-group">
                        <label>Facebook Sync Folder</label>
                        <input className="form-control"
                               placeholder="Facebook Sync Folder"
                               name="facebook_sync_folder"
                               onChange={this.handleInputChange.bind(this)}
                               value={this.state.facebook_sync_folder}/>
                    </div>

                    <div className="form-group">
                        <button
                            type="submit"
                            className="btn btn-default">
                            Update Social Settings
                        </button>
                    </div>
                </form>
            </div>
        );
    }
}


export default SocialConfig;