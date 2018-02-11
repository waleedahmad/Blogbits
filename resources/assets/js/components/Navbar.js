import React from 'react';
import {Link} from 'react-router-dom';

class Navbar extends React.Component{

    constructor(props){
        super(props);
        this.state = {
            counts : {
                facebook : this.props.counts.facebook,
                tumblr : this.props.counts.tumblr,
                pinterest : this.props.counts.pinterest
            }
        }
    }

    componentWillReceiveProps(nextProps){
        this.setState({
            counts : nextProps.counts
        });
    }

    postBatch(){
        this.props.countUpdate();
    }

    syncContent(service, uri){
        toastr.info(`Syncing ${service} content`);

        $.ajax({
            type : 'GET',
            url : `/content/sync${uri}`,
            success : function(done){
                if(done){
                    toastr.success(`${service} content synced`);
                }
            }
        });
    }

    deleteSync(service, service_name){
        console.log(service, service_name);
        bootbox.confirm({
            message: `Are you sure you want to delete ${service_name} Content?`,
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if(result){
                    $.ajax({
                        type : 'DELETE',
                        url : `/content/deleteAll/${service}`,
                        data : {
                            _token : $("meta[name=csrf_token]").attr('content')
                        },
                        success : function(done){
                            if(done){
                                toastr.success(`${service_name} local synced content deleted`);
                            }else{
                                toastr.info(`No synced content available for ${service_name}`);
                            }
                        }
                    });
                }
            }
        });
    }

    render(){
        return (
            <nav className="navbar navbar-fixed-top">
                <div className="container">
                    <div className="navbar-header">
                        <button type="button" className="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapsable" aria-expanded="false">
                            <span className="sr-only">Toggle navigation</span>
                            <span className="icon-bar"></span>
                            <span className="icon-bar"></span>
                            <span className="icon-bar"></span>
                        </button>
                        <Link className="navbar-brand" to="/">BlogBits</Link>
                    </div>

                    <div className="collapse navbar-collapse" id="collapsable">

                        <ul className="nav navbar-nav navbar-right">
                            <li>
                                <Link to="#" id="post-batch" onClick={this.postBatch.bind(this)}> <i className="fa fa-paper-plane" aria-hidden="true"></i> Post Batch {this.state.counts.tumblr}</Link>
                            </li>

                            <li>
                                <Link to="#" id="sync-data" onClick={this.syncContent.bind(this, 'Tumblr', '')}> <i className="fa fa-tumblr-square" aria-hidden="true"></i> Blog Sync</Link>
                            </li>

                            <li>
                                <Link to="#" id="facebook-sync" onClick={this.syncContent.bind(this, 'Facebook', '/facebook')}> <i className="fa fa-facebook" aria-hidden="true"></i> Facebook Sync</Link>
                            </li>

                            <li>
                                <Link to="#" id="pinterest-sync" onClick={this.syncContent.bind(this, 'Pinterest', '/pinterest')}> <i className="fa fa-pinterest" aria-hidden="true"></i> Pinterest Sync</Link>
                            </li>

                            <li className="dropdown">
                                <Link to="#"
                                   className="dropdown-toggle"
                                   data-toggle="dropdown"
                                   role="button"
                                   aria-haspopup="true"
                                   aria-expanded="false" onClick={this.openDropDown}>
                                    <i className="fa fa-user" aria-hidden="true"> </i>
                                </Link>
                                <ul className="dropdown-menu">
                                    <li>
                                        <Link to="#" id="delete-sync"  onClick={this.deleteSync.bind(this , 'tumblr', 'Tumblr')} > <i className="fa fa-tumblr-square" aria-hidden="true"></i> Delete Blog Content</Link>
                                    </li>

                                    <li>
                                        <Link to="#" id="pinterest-delete-sync"  onClick={this.deleteSync.bind(this, 'pinterest', 'Pinterest')} > <i className="fa fa-pinterest" aria-hidden="true"></i> Delete Pinterest Content</Link>
                                    </li>

                                    <li>
                                        <Link to="#" id="facebook-delete-sync"  onClick={this.deleteSync.bind(this, 'facebook', 'Facebook')} > <i className="fa fa-facebook" aria-hidden="true"></i> Delete Facebook Content</Link>
                                    </li>

                                    <li className="nav-divider"></li>

                                    <li><a href="/config">Settings</a></li>
                                    <li><a href="/logout">Logout</a></li>
                                </ul>
                            </li>
                            
                        </ul>
                    </div>
                </div>
            </nav>
        );
    }
}

export default Navbar;