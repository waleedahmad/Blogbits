import React from 'react';
import Post from './Post';
require('./Posts.scss');


class Posts extends React.Component{
    constructor(props) {
        super(props);
        this.state = this.getInitialState();
        this._isMounted = false;
        window.document.title = 'Posts - Blogbits';
    }

    getInitialState(){
        return {
            posts : [],
            count : 0,
            take : 10,
            message : 'Loading posts',
            loading : false,
        };
    }

    componentDidMount(){
        this._isMounted = true;
        this.getPosts(this.state.take);
    }

    componentWillUnmount(){
        this._isMounted = false;
    }

    onRouteChanged() {
        this.setState(this.getInitialState(), () => {
            this.getPosts(this.state.take);
        });
    }

    componentDidUpdate(prevProps, prevState){
        if (this.props.location !== prevProps.location) {
            this.onRouteChanged();
        }
    }

    getPosts(offset){
        this.removeScrollEvent();
        this.setState({
            loading : true
        });

        $.ajax({
            type : 'GET',
            url : this.getApiEndPoint(this.props.location.pathname),
            data : {
                skip : this.state.count,
                take :  offset
            },
            success : function(res){
                if(this._isMounted){
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
                    this.setState({
                        loading: false
                    })
                }
            }.bind(this)

        });
    }

    getApiEndPoint(url){
        switch(url){
            case '/':
                return '/api/posts/tumblr';
                break;
            case '/posts/facebook':
                return '/api/posts/facebook';
                break;
            case '/posts/pinterest' :
                return '/api/posts/pinterest';
                break;
            case '/tumblr' :
                return '/api/blog/feed';
                break;
        }
        return null;
    }

    isBottom(el) {
        return el.getBoundingClientRect().bottom <= window.innerHeight;
    }

    trackScrolling(){
        const wrappedElement = document.getElementById('posts');
        if (this.isBottom(wrappedElement)) {
            if(!this.state.loading){
                this.getPosts(this.state.take);
            }
        }
    };

    registerScrollEvent(){
        document.addEventListener('scroll', this.trackScrolling.bind(this));
    }

    removeScrollEvent(){
        document.removeEventListener('scroll', this.trackScrolling.bind(this));
    }

    delete(id){
        toastr.info('Deleting...');
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
        toastr.info('Publishing...');
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
            <div id="posts" className="posts col-xs-12 col-sm-12 col-md-5 col-lg-4">
                {this.state.posts.length ? this.state.posts.map(function(post){
                    return <Post key={post.id} action={'view'} post={post} delete={this.delete.bind(this)} publish={this.publish.bind(this)}/>
                }.bind(this)) : <div className="alert alert-info">{this.state.message}</div>}
            </div>
        );
    }
}


export default Posts;