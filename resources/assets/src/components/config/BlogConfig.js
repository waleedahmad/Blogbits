import React from 'react';
import toastr from 'toastr';


class BlogConfig extends React.Component{
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
            url : '/config/blog',
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

    render(){

        return (
            <div className="block">
                <h3>
                    Tumblr Blog
                </h3>
                <form onSubmit={this.handleBlogConfig.bind(this)}>
                    <div className="form-group">
                        <label>Active Blog</label>
                        <input type="text"
                               className="form-control"
                               placeholder="Active Tumblr blog"
                               name="active_blog"
                               onChange={this.handleInputChange.bind(this)}
                               value={this.state.active_blog}/>
                    </div>
                    <div className="form-group">
                        <label>Post Link</label>
                        <input type="text"
                               className="form-control"
                               placeholder="Post Photo link"
                               name="post_link"
                               onChange={this.handleInputChange.bind(this)}
                               value={this.state.post_link}/>
                    </div>
                    <div className="form-group">
                        <label>Pinterest</label>
                        <input type="text"
                               className="form-control"
                               placeholder="Pinterest Profile"
                               name="pinterest"
                               onChange={this.handleInputChange.bind(this)}
                               value={this.state.pinterest}/>
                    </div>
                    <div className="form-group">
                        <label>Facebook</label>
                        <input type="text"
                               className="form-control"
                               placeholder="Facebook Page"
                               name="facebook"
                               onChange={this.handleInputChange.bind(this)}
                               value={this.state.facebook}/>
                    </div>

                    <div className="form-group">
                        <label>Tumblr Sync Folder</label>
                        <input type="text"
                               className="form-control"
                               placeholder="Sync Folder (File System)"
                               name="sync_folder"
                               onChange={this.handleInputChange.bind(this)}
                               value={this.state.sync_folder}/>
                    </div>

                    <div className="form-group">
                        <label>Default Tags</label>
                        <input type="text"
                               className="form-control"
                               placeholder="Default Tags (separated by commas)"
                               name="default_tags"
                               onChange={this.handleInputChange.bind(this)}
                               value={this.state.default_tags}/>
                    </div>

                    <button type="submit" className="btn btn-default">Update Blog Settings</button>
                </form>
            </div>
        );
    }
}


export default BlogConfig;