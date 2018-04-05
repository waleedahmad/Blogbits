import React from 'react';
import 'react-tagsinput/react-tagsinput.css';
import Post from "./Post";


class EditPost extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            post : null,
            message : 'Loading post'
        };
    }

    componentDidMount(){
        $.ajax({
            type : 'GET',
            url : '/api/post/' + this.props.match.params.id,
            success : function(post){
                window.document.title = post.caption + ' - Blogbits';
                this.setState({
                    post : post
                });
            }.bind(this)
        })
    }

    render() {
        return (
            <div className="grid col-xs-12 col-sm-12 col-lg-4 col-md-4">
                {
                    this.state.post ? <Post action={'edit'} key={this.state.post.id} post={this.state.post}/> : <div className="alert alert-info">{this.state.message}</div>}
                }
            </div>
        );
    }
}

export default EditPost;
