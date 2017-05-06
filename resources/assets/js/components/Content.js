import {Route} from 'react-router'

import React from 'react';

import Posts from './Posts';
import SocialPosts from './SocialPosts';

class Content extends React.Component{
    render(){
        return (
            <main>
                <Route exact path="/" render={(props) => (
                    <Posts countUpdate={this.props.countUpdate} {...props}/>
                )}/>

                <Route exact path="/facebook" render={(props) => (
                    <Posts countUpdate={this.props.countUpdate} {...props}/>
                )}/>

                <Route exact path="/pinterest" render={(props) => (
                    <Posts countUpdate={this.props.countUpdate} {...props}/>
                )}/>

                <Route exact path="/tumblr" render={(props) => (
                    <SocialPosts countUpdate={this.props.countUpdate} {...props}/>
                )}/>
            </main>
        )
    }
}

export default Content;