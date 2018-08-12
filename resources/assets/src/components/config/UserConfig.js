import React from 'react';


class UserConfig extends React.Component{
    constructor(props) {
        super(props);
        this.state = Object.assign({}, this.props.user, {
            'password' : '',
        });
    }

    componentWillReceiveProps(nextProps){
        this.setState(Object.assign({}, nextProps.user, {
            'password' : '',

        }));
    }

    handleBlogConfig(e){
        e.preventDefault();
        $.ajax({
            type : 'POST',
            url : '/config/user',
            data : Object.assign(
                this.state,
                {_token : $("meta[name=csrf_token]").attr('content')}
            ),
            success : function(res){
                if(res.updated){
                    toastr.success('Updated');
                }

            }
        });

        console.log(this.state);
    }

    handleInputChange(e){
        this.setState({[e.target.name]: e.target.value});
    }

    render(){

        return (
            <div className="block">
                <h3>
                    User
                </h3>
                <form onSubmit={this.handleBlogConfig.bind(this)}>
                    <div className="form-group">
                        <label>Email</label>
                        <input className="form-control"
                               name="email"
                               value={this.state.email}
                               onChange={this.handleInputChange.bind(this)}
                               placeholder="Email"/>
                    </div>

                    <div className="form-group">
                        <label>Name</label>
                        <input className="form-control"
                               name="name"
                               value={this.state.name}
                               onChange={this.handleInputChange.bind(this)}
                               placeholder="Name"/>
                    </div>

                    <div className="form-group">
                        <label>Password</label>
                        <input type="password"
                               className="form-control"
                               name="password"
                               value={this.state.password}
                               onChange={this.handleInputChange.bind(this)}
                               placeholder="Password"/>
                    </div>

                    <div className="form-group">
                        <button className="btn btn-default">Update</button>
                    </div>


                </form>
            </div>
        );
    }
}


export default UserConfig;