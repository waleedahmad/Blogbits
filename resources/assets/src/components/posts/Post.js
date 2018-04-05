import React from 'react';
import TagsInput from 'react-tagsinput';
import 'react-tagsinput/react-tagsinput.css';
import Link from "react-router-dom/es/Link";


class Post extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            publish: false,
            delete: false,
            tags : this.props.post.tags.split(','),
            caption : this.props.post.caption
        }
    }

    delete(id) {
        this.setState({
            delete: true
        });
        this.props.delete(id);
    }

    publish(id, type) {
        this.setState({
            publish: true
        });

        this.props.publish(id, type);
    }

    busy(action) {
        toastr.warning(`Unable to ${action}, Resource is busy!`);
    }

    handleChange(id, tags) {
        $.ajax({
            type : 'POST',
            url : '/content/update/tags',
            data : {
                tags : tags.join(','),
                post_id : id,
                _token : $("meta[name=csrf_token]").attr('content')
            },
            success : function(updated){
                if(updated){
                    this.setState({tags : tags});
                    toastr.success('Post tags updated');
                }
            }.bind(this)
        });
    }

    handleCaptionChange(e){
        this.setState({[e.target.name]: e.target.value});
    }

    updateCaption(e){
        e.preventDefault();
        $.ajax({
            type : 'POST',
            url : '/content/update',
            data : {
                id : this.props.post.id,
                caption : this.state.caption,
                _token : $("meta[name=csrf_token]").attr('content')
            },
            success : function(res){
                if(res){
                    toastr.success('Post caption updated');
                }
            }
        })
    }

    render() {
        return (
            <div className="post">
                <div className="thumbnail">
                    {
                        this.props.action === 'view' ? (
                            <Link to={'/post/edit/' + this.props.post.id} className="edit-post">
                                <span className="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            </Link>
                        ) : ''
                    }

                    <img src={'/storage/' + this.props.post.uri}/>
                    {
                        this.props.action === 'view' ?
                            <div className="caption">
                                <h3>{this.props.post.caption}</h3>
                                <button
                                    onClick={!(this.state.delete) ? this.delete.bind(this, this.props.post.id) : this.busy.bind(this, 'delete')}
                                    className="btn btn-danger btn-sm pull-right delete-now"> Delete
                                </button>
                                <button
                                    onClick={!(this.state.publish) ? this.publish.bind(this, this.props.post.id, this.props.post.type) : this.busy.bind(this, 'publish')}
                                    className="btn btn-primary btn-sm pull-right post-now">Post Now
                                </button>
                            </div>
                            :
                            <div className="caption-edit">
                                <input type="text"
                                       className="form-control"
                                       value={this.state.caption}
                                       name="caption"
                                       onChange={this.handleCaptionChange.bind(this)}
                                />

                                <button
                                    className="btn btn-primary btn-sm pull-right"
                                    style={{marginTop: '10px', marginBottom: '10px'}}
                                    onClick={this.updateCaption.bind(this)}
                                >Update</button>
                            </div>
                    }

                    <div className="actions" style={{clear : 'both'}}>
                        <TagsInput value={this.state.tags} onChange={this.handleChange.bind(this, this.props.post.id)} />
                    </div>
                </div>
            </div>
        );
    }
}

export default Post;
