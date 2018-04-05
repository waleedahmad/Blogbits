import React from 'react';
import BlogConfig from './BlogConfig';
import SchedulerConfig from './SchedulerConfig';
import UserConfig from './UserConfig';
import SocialConfig from './SocialConfig';
require('./Config.scss');

class Config extends React.Component{
    constructor(props) {
        super(props);
        this.state = this.getInitialState();
        window.document.title = 'Config - Blogbits';
    }

    componentDidMount(){
        $.ajax({
            type : 'GET',
            url : '/config',
            success : function(config){
                this.setState(config);
            }.bind(this)
        });
    };

    getInitialState(){
        return {
            blog : {
                active_blog : '',
                default_tags : '',
                facebook : '',
                pinterest : '',
                post_link : '',
                sync_folder : '',
            },
            scheduler : {
                batch_post_limit : '',
                scheduler_end_time : '',
                scheduler_frequency : '',
                scheduler_start_time : '',
            },
            social : {
                facebook_pageid : '',
                facebook_sync_folder : '',
                pinterest_board : '',
                pinterest_sync_folder : '',
                pinterest_token : '',
                pinterest_username : '',
                social_scheduler_end_time : '',
                social_scheduler_frequency : '',
                social_scheduler_start_time : '',
            },
            frequencies : [],
            user : {
                name : '',
                email : '',
                username : '',
            }
        };
    }

    syncFacebookAlbums(e){
        e.preventDefault();
        toastr.info('Syncing facebook albums');

        $.ajax({
            type : 'POST',
            url : '/social/sync/fbAlbums',
            data : {
                _token : $("meta[name=csrf_token]").attr('content')
            },
            success : function(res){
                if(res){
                    toastr.success('Facebook albums synced');
                }
            }
        })
    }

    backupSyncedContent(e){
        e.preventDefault();

        toastr.info('Backing up all posts!');
        $.ajax({
            type : 'POST',
            'url' : '/content/backup',
            data : {
                _token : $("meta[name=csrf_token]").attr('content')
            },
            success : function(res){
                if(res){
                    toastr.success('All Photos backed up!');
                }
            }
        });
    }

    render(){
        return (
            <div className="col-xs-12 col-sm-12 col-md-6 col-lg-8 settings">

                <div className="page-header">
                    <h2>
                        Application Settings
                    </h2>
                </div>

                <div className="config">
                    <BlogConfig
                        config={this.state.blog}/>
                    <SchedulerConfig
                        config={this.state.scheduler}
                        frequencies={this.state.frequencies}/>
                    <SocialConfig
                        config={this.state.social}
                        frequencies={this.state.frequencies}/>
                    <UserConfig user={this.state.user}/>

                    <div className="form-group">
                        <h3>
                            Sync Facebook Albums
                        </h3>
                        <a className="btn btn-default"
                           href="#"
                           id="sync-fb-albums"
                           role="button"
                            onClick={this.syncFacebookAlbums.bind(this)}
                        >
                            Sync Albums
                        </a>
                    </div>

                    <div className="form-group">
                        <h3>
                            Backup Media
                        </h3>
                        <button className="btn btn-default"
                                id="backup-content"
                                onClick={this.backupSyncedContent.bind(this)}
                        >Backup Posts</button>
                    </div>
                </div>
            </div>
        );
    }
}


export default Config;