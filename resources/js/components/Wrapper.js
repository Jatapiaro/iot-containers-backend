import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter, Route, Redirect, Switch } from 'react-router-dom';

// Components
import Navbar from './Navbar/Navbar';

// Home
import Home from './../pages/Home';

// Profile
import Me from './../pages/profile/Me';

// Services
import HttpService from '../services/HttpService';
import UserService from '../services/UserService';

// Toast
import { ToastContainer } from 'react-toastify';

export default class Wrapper extends Component {

    constructor(props) {
        super(props);
        this.httpService = new HttpService();
        this.userService = new UserService(this.httpService);
    }

    render() {
        return (
            <React.Fragment>
                <Navbar />
                <div>
                    <BrowserRouter>
                        <div className="content">
                            <Switch>

                                <Route path="/"
                                    render={(props) =>
                                        <Home
                                            {...props}
                                        />
                                    }
                                    exact={true} />

                                {/* ============= Profile =========== */}
                                <Route
                                    path="/me"
                                    render={(props) =>
                                        <Me
                                            userService={this.userService}
                                            {...props}
                                        />
                                    }
                                    exact={true} />

                            </Switch>
                        </div>
                    </BrowserRouter>
                </div>
                <ToastContainer />
            </React.Fragment>
        );
    }
}

if (document.getElementById('app')) {
    ReactDOM.render(<Wrapper />, document.getElementById('app'));
}
