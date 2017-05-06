import React from 'react';
import SocialPost from './SocialPost';
import 'bootstrap-tagsinput';


class SocialPosts extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            posts : [],
            offset : 0,
            url : props.match.url
        };

    }

    componentDidMount(){
        this.getPosts(this.state.offset);
    }

    getPosts(offset){
        this.removeScrollEvent();
        $.ajax({
            type : 'GET',
            url : '/api/blog/feed',
            data : {
                offset :  offset
            },
            success : function(res){
                this.setState({
                    posts : this.state.posts.concat(res.posts),
                    offset : this.state.offset + res.posts.length + 1
                });

                if(res.posts.length){
                    this.registerScrollEvent()
                }
            }.bind(this)
        });
    }


    registerScrollEvent(){
        $(window).on('scroll', function() {
            if($(window).scrollTop() + $(window).height() === $(document).height()) {
                this.getPosts(this.state.offset)
            }
        }.bind(this));
    }

    removeScrollEvent(){
        $(window).off('scroll');
    }

    render(){

        return (
            <div className="grid col-xs-12 col-sm-12 col-lg-4 col-md-4">
                {this.state.posts.length ? this.state.posts.map(function(post){
                    return <SocialPost key={post.id} post={post}/>
                }.bind(this)) : <div className="alert alert-info">Fetching Tumblr content, please wait...</div>}
            </div>
        );
    }
}


export default SocialPosts;