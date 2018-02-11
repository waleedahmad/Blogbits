import {Route} from 'react-router'

import React from 'react';

import Posts from './Posts';

class PostContainer extends React.Component{
    render(){
        return (
            <main>
                <Route exact path="*" render={(props) => (
                    <Posts countUpdate={this.props.countUpdate} {...props}/>
                )}/>
            </main>
        )
    }
}

export default PostContainer;