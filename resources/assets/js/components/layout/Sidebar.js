import React from 'react';
import {Link} from 'react-router-dom';

class Sidebar extends React.Component{

    constructor(props){
        super(props);
        this.state = {
            counts : {
                facebook : this.props.counts.facebook,
                tumblr : this.props.counts.tumblr,
                pinterest : this.props.counts.pinterest
            }
        }
    }

    componentWillReceiveProps(nextProps){
        this.setState({
            counts : nextProps.counts
        });
    }


    render(){
        return(
            <div id="sidebar" className="col-xs-12 col-sm-12 col-lg-2 col-md-3">
                <li>
                    <Link to="/"> <i className="fa fa-tumblr-square" aria-hidden="true"></i>
                        Tumblr Content <div className="badge pull-right">{this.state.counts.tumblr}</div>
                    </Link>
                </li>

                <li >
                    <Link to="/facebook"> <i className="fa fa-facebook" aria-hidden="true"></i>
                        Facebook Content <div className="badge pull-right">{this.state.counts.facebook}</div>
                    </Link>
                </li>

                <li >
                    <Link to="/pinterest"> <i className="fa fa-pinterest-p" aria-hidden="true"></i>
                        Pinterest Content <div className="badge pull-right">{this.state.counts.pinterest}</div>
                    </Link>
                </li>

                <li >
                    <Link to="/tumblr"> <i className="fa fa-tumblr-square" aria-hidden="true"></i>
                        Tumblr Feed
                    </Link>
                </li>
            </div>
        );
    }
}

export default Sidebar;