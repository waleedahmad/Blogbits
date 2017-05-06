import React from 'react';
import Post from './Post';
import 'bootstrap-tagsinput';


class Posts extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            posts : [],
            count : 0,
            take : 10,
            url : props.match.url,
            message : 'Loading posts'
        };
    }

    componentDidMount(){
        this.getPosts(this.state.take);
    }

    componentDidUpdate(prevProps, prevState){
        this.initPostTags();
    }

    getPosts(offset){
        this.removeScrollEvent();
        $.ajax({
            type : 'GET',
            url : this.getApiEndPoint(this.state.url),
            data : {
                skip : this.state.count,
                take :  offset
            },
            success : function(res){
                this.setState({
                    posts : this.state.posts.concat(res.posts),
                    count : this.state.count + res.posts.length
                });

                if(res.total > this.state.count){
                    this.registerScrollEvent()
                }

                if(!res.posts.length){
                    this.setState({
                        message : 'No more posts to load'
                    });
                }
            }.bind(this)
        });
    }

    getApiEndPoint(url){
        switch(url){
            case '/':
                return '/api/posts/tumblr';
                break;
            case '/facebook':
                return '/api/posts/facebook';
                break;
            case '/pinterest' :
                return '/api/posts/pinterest';
                break;
            case '/tumblr' :
                return '/api/blog/feed';
                break;
        }
        return null;
    }

    registerScrollEvent(){
        $(window).on('scroll', function() {
            if($(window).scrollTop() + $(window).height() === $(document).height()) {
                this.getPosts(this.state.count + this.state.take)
            }
        }.bind(this));
    }

    removeScrollEvent(){
        $(window).off('scroll');
    }

    initPostTags(){
        let $tags = $('.tags');
        $($tags).tagsinput({
            allowDuplicates: false,
            trimValue: true
        });

        $($tags).off('itemAdded').on('itemAdded', function(event) {
            var tags = $(this).tagsinput('items').join();
            console.log(tags);
        });

        $($tags).off('itemRemoved').on('itemRemoved', function(event) {
            alert('Update tags');
        });
    }

    delete(id){
        $.ajax({
            type : 'DELETE',
            url : '/api/posts',
            data : {
                post_id : id,
                _token : $("meta[name=csrf_token]").attr('content')
            },
            success : function(done){
                if(done){
                    toastr.success('Successfully deleted');
                    this.updatePostsState(id);
                }
            }.bind(this)
        });
    }

    publish(id, service){
        $.ajax({
            type : 'POST',
            url : `/content/post/${service}`,
            data : {
                post_id : id,
                _token : $("meta[name=csrf_token]").attr('content')
            },
            success : function(done){
                if(done){
                    toastr.success('Successfully published');
                    this.updatePostsState(id);
                }
            }.bind(this)
        });
    }

    updatePostsState(id){
        this.setState({posts: this.state.posts.filter( el=> {
            return el.id !== id;
        })});
        this.props.countUpdate();
    }

    render(){

        return (
            <div className="grid col-xs-12 col-sm-12 col-lg-4 col-md-4">
                {this.state.posts.length ? this.state.posts.map(function(post){
                    return <Post key={post.id} post={post} delete={this.delete.bind(this)} publish={this.publish.bind(this)}/>
                }.bind(this)) : <div className="alert alert-info">{this.state.message}</div>}
            </div>
        );
    }
}


export default Posts;