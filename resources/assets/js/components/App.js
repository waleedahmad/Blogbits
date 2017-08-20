import React from "react";
import Content from './Content';
import Sidebar from './layout/Sidebar';
import Navbar from './layout/Navbar';
import {Route} from 'react-router';

class App extends React.Component{

    constructor(props){
        super(props);
        this.state = {
            sidebar : {
                counts : {
                    tumblr : 0,
                    facebook : 0,
                    pinterest : 0
                }
            }
        };
        this.getPostsCount();
    }

    updatePostsCount(counts){
        this.setState({
            sidebar : {
                counts : counts
            }
        })
    }

    getPostsCount(){
        $.ajax({
            type : 'GET',
            url : '/api/posts/count',
            success : function(count){
                this.updatePostsCount(count);
            }.bind(this)
        });
    }

    render(){
        return (
            <div>
                <Navbar counts={this.state.sidebar.counts} countUpdate={this.getPostsCount.bind(this)}/>
                <div className="container-fluid">

                    <Route path="/" render={(props) => (
                        <Sidebar counts={this.state.sidebar.counts} {...props}/>
                    )}/>
                    <Content countUpdate={this.getPostsCount.bind(this)}/>
                </div>
            </div>
        );
    }
}

export default App;