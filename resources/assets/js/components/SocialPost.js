import React from 'react';
const striptags = require('striptags');

class SocialPost extends React.Component{
    constructor(props){
        super(props);
    }

    render(){
        return (
            <div className="post">
                <div className="thumbnail">
                    <img src={this.props.post.photos[0].original_size.url}/>
                        <div className="caption">
                            <h3>{striptags(this.props.post.caption)} <small>{this.props.post.note_count} Notes</small></h3>
                        </div>
                </div>
            </div>
        );
    }
}

export default SocialPost;
