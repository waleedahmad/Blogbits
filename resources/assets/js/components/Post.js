import React from 'react';

class Post extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            publish: false,
            delete: false
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
        bootbox.alert(`Source is busy perform ${action} action on target post, please wait for refresh`);
    }

    onTagsChange(id) {
        console.log(this.value);
        console.log(id);
    }

    render() {
        return (
            <div className="post">
                <div className="thumbnail">
                    <a className="edit-post" target="_blank" href={'/content/edit/' + this.props.post.id}>
                        <span className="glyphicon glyphicon-edit" aria-hidden="true"></span>
                    </a>

                    <img src={'/storage/' + this.props.post.uri}/>
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

                    <div className="actions">
                        <input type="text" className="form-control tags" data-id={this.props.post.id}
                               data-role="tagsinput" value={this.props.post.tags}
                               onChange={this.onTagsChange.bind(this, this.props.post.id)} required/>
                    </div>
                </div>
            </div>
        );
    }
}

export default Post;
